<template>
  <div class="tencent-map-container">
    <!-- 腾讯地图容器 -->
    <div ref="mapContainer" class="map-instance"></div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch } from 'vue';
import { debounce } from 'lodash';

// 类型声明 - 解决TypeScript错误
declare global {
  interface Window {
    qq: any;
    initTencentMap: () => void;
  }
}

// 在组件内部定义坐标接口
interface Coordinates {
  latitude: string;
  longitude: string;
  address?: string;
}

interface TencentConfig {
  service_key: string;
}

// 定义props
const props = defineProps({
  config: {
    type: String,
    required: true
  },
  coordinates: {
    type: Object as () => Coordinates,
    required: true
  }
});

// 统一使用change事件
const emit = defineEmits(['change']);

// 解析配置JSON字符串
const parseConfig = (): TencentConfig | null => {
  try {
    return JSON.parse(props.config);
  } catch (e) {
    console.error('解析腾讯地图配置失败:', e);
    return null;
  }
};

// 地图实例和标记
const mapContainer = ref<HTMLDivElement | null>(null);
const map = ref<any>(null);
const currentMarker = ref<any>(null);

// 加载腾讯地图脚本
const loadTencentMapScript = () => {
  return new Promise((resolve, reject) => {
    if (window.qq && window.qq.maps) {
      resolve(window.qq.maps);
      return;
    }

    // 解析配置获取API Key
    const config = parseConfig();
    if (!config) {
      reject(new Error('腾讯地图配置解析失败'));
      return;
    }

    // 腾讯地图只使用service_key
    const apiKey = config.service_key || '';
    if (!apiKey) {
      reject(new Error('缺少腾讯地图API密钥，请检查配置中的service_key字段'));
      return;
    }

    // 设置超时机制
    const timeout = setTimeout(() => {
      reject(new Error('腾讯地图API加载超时'));
    }, 10000); // 10秒超时

    // 全局回调函数
    window.initTencentMap = () => {
      clearTimeout(timeout);
      
      // 检查API是否正确加载
      if (window.qq && window.qq.maps) {
        resolve(window.qq.maps);
      } else {
        reject(new Error('腾讯地图标准API加载失败'));
      }
    };

    // 创建script标签 - 使用标准版本，GL版本可能有兼容性问题
    const script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = `https://map.qq.com/api/js?v=2.exp&key=${apiKey}&callback=initTencentMap`;
    script.charset = 'utf-8';
    script.async = true;
    script.defer = true;
    script.onerror = () => {
      clearTimeout(timeout);
      reject(new Error('腾讯地图标准脚本加载失败'));
    };
    document.head.appendChild(script);
  });
};

// 初始化地图
const initMap = async () => {
  try {
    if (!window.qq || !window.qq.maps) {
      await loadTencentMapScript();
    }

    // 再次检查API是否可用
    if (!window.qq || !window.qq.maps) {
      throw new Error('腾讯地图API未正确加载');
    }

    if (mapContainer.value && !map.value) {
      // 创建地图实例
      map.value = new window.qq.maps.Map(mapContainer.value, {
        zoom: 16,
        center: new window.qq.maps.LatLng(39.915, 116.404) // 默认北京
      });

      // 检查是否有初始坐标
      if (props.coordinates && props.coordinates.latitude && props.coordinates.longitude) {
        // 如果有初始坐标，优先使用初始坐标设置中心点
        try {
          const initialCenter = new window.qq.maps.LatLng(
            parseFloat(props.coordinates.latitude),
            parseFloat(props.coordinates.longitude)
          );
          map.value.setCenter(initialCenter);
          addMarker(initialCenter);
        } catch (error) {
          console.error('初始化坐标设置失败:', error);
          // 如果初始坐标设置失败，使用默认中心点
          setDefaultCenter();
        }
      } else {
        // 没有初始坐标，使用默认中心点
        setDefaultCenter();
      }

      // 点击地图事件 - 使用防抖
      window.qq.maps.event.addListener(map.value, 'click', debouncedMapClick);
    }
  } catch (error) {
    console.error('初始化腾讯地图失败:', error);
  }
};

// 设置默认中心点函数
const setDefaultCenter = () => {
  if (!map.value) return;

  // 默认中心点 - 北京
  const defaultCenter = new window.qq.maps.LatLng(39.915, 116.404);
  map.value.setCenter(defaultCenter);
};

// 处理地图点击 - 普通函数
const handleMapClick = (e: any) => {
  const latLng = e.latLng;

  // 清除旧标记
  clearMarker();

  // 添加新标记
  addMarker(latLng);

  // 获取地址信息并发送坐标
  getAddressFromLatLng(latLng);
};

// 创建防抖版本的地图点击处理函数 - 防抖延迟300ms
const debouncedMapClick = debounce(handleMapClick, 300);

// 添加标记 - 腾讯地图标准版本
const addMarker = (latLng: any) => {
  if (map.value) {
    const marker = new window.qq.maps.Marker({
      position: latLng,
      map: map.value,
      title: '选中位置' // 标记标题
    });
    currentMarker.value = marker;

    // 居中显示但不改变缩放级别
    map.value.panTo(latLng);
  }
};

// 清除标记
const clearMarker = () => {
  if (map.value && currentMarker.value) {
    currentMarker.value.setMap(null);
    currentMarker.value = null;
  }
};

// 根据坐标点获取地址 - 腾讯地图标准版本
const getAddressFromLatLng = debounce((latLng: any) => {
  if (!map.value) return;

  const geocoder = new window.qq.maps.Geocoder();
  geocoder.getAddress(latLng, (result: any, status: any) => {
    if (status === window.qq.maps.GeocoderStatus.OK && result) {
      const location = {
        latitude: latLng.getLat().toString(),
        longitude: latLng.getLng().toString(),
        address: result.address || '',
      };
      emit('change', location);
    } else {
      // 如果地址解析失败，也发送坐标信息
      const location = {
        latitude: latLng.getLat().toString(),
        longitude: latLng.getLng().toString()
      };
      emit('change', location);
    }
  });
}, 300);

// 根据坐标更新地图显示 - 加入防抖
const updateMapFromCoordinates = debounce(() => {
  if (!map.value || !props.coordinates.latitude || !props.coordinates.longitude) return;

  try {
    // 创建坐标点
    const latLng = new window.qq.maps.LatLng(
      parseFloat(props.coordinates.latitude),
      parseFloat(props.coordinates.longitude)
    );

    // 清除旧标记
    clearMarker();

    // 设置中心点和标记
    map.value.setCenter(latLng);
    addMarker(latLng);
  } catch (error) {
    console.error('更新地图位置失败:', error);
  }
}, 300);

// 监听坐标变化
watch(() => props.coordinates, () => {
  if (map.value) {
    updateMapFromCoordinates();
  }
}, { deep: true });

// 生命周期
onMounted(() => {
  initMap();
});

onUnmounted(() => {
  // 清理事件监听器和防抖函数
  if (map.value) {
    window.qq.maps.event.removeListener(map.value, 'click', debouncedMapClick);
  }
  // 取消所有挂起的防抖函数
  debouncedMapClick.cancel();
  getAddressFromLatLng.cancel();
  updateMapFromCoordinates.cancel();
});
</script>

<style scoped>
.tencent-map-container {
  position: relative;
  width: 100%;
  height: 100%;
  min-height: 300px;
}

.map-instance {
  width: 100%;
  height: 100%;
  min-height: 300px;
}
</style>