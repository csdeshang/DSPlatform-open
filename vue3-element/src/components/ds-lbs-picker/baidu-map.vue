<template>
  <div class="baidu-map-container">
    <!-- 百度地图容器 -->
    <div ref="mapContainer" class="map-instance"></div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch } from 'vue';
import { debounce } from 'lodash';

// 类型声明 - 解决TypeScript错误
declare global {
  interface Window {
    BMap: any;
    initBaiduMap: () => void;
  }
}

// 在组件内部定义坐标接口
interface Coordinates {
  latitude: string;
  longitude: string;
  address?: string;
}

interface BaiduConfig {
  service_ak: string;
  browser_ak: string
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
const parseConfig = (): BaiduConfig | null => {
  try {
    return JSON.parse(props.config);
  } catch (e) {
    console.error('解析百度地图配置失败:', e);
    return null;
  }
};



// 地图实例和标记
const mapContainer = ref<HTMLDivElement | null>(null);
const map = ref<any>(null);
const currentMarker = ref<any>(null);



// 加载百度地图脚本
const loadBaiduMapScript = () => {
  return new Promise((resolve, reject) => {
    if (window.BMap) {
      resolve(window.BMap);
      return;
    }

    // 解析配置获取API Key
    const config = parseConfig();
    if (!config) {
      reject(new Error('百度地图配置解析失败'));
      return;
    }

    const apiKey = config.browser_ak || '';
    if (!apiKey) {
      reject(new Error('缺少百度地图API密钥'));
      return;
    }

    // 全局回调函数
    window.initBaiduMap = () => {
      resolve(window.BMap);
    };

    // 创建script标签
    const script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = `https://api.map.baidu.com/api?v=3.0&ak=${apiKey}&callback=initBaiduMap&s=1`;
    script.onerror = reject;
    document.head.appendChild(script);
  });
};

// 初始化地图
const initMap = async () => {
  try {
    if (!window.BMap) {
      await loadBaiduMapScript();
    }

    if (mapContainer.value && !map.value) {
      // 创建地图实例
      map.value = new window.BMap.Map(mapContainer.value);

      // 检查是否有初始坐标
      if (props.coordinates && props.coordinates.latitude && props.coordinates.longitude) {
        // 如果有初始坐标，优先使用初始坐标设置中心点
        try {
          const initialPoint = new window.BMap.Point(
            parseFloat(props.coordinates.longitude),
            parseFloat(props.coordinates.latitude)
          );
          map.value.centerAndZoom(initialPoint, 16); // 使用更好的缩放级别
          addMarker(initialPoint);
        } catch (error) {
          console.error('初始化坐标设置失败:', error);
          // 如果初始坐标设置失败，使用默认中心点
          setDefaultCenter();
        }
      } else {
        // 没有初始坐标，使用默认中心点
        setDefaultCenter();
      }

      // 添加常用控件
      map.value.addControl(new window.BMap.NavigationControl());
      map.value.addControl(new window.BMap.ScaleControl());
      map.value.enableScrollWheelZoom();

      // 点击地图事件 - 使用防抖
      map.value.addEventListener('click', debouncedMapClick);
    }
  } catch (error) {
    console.error('初始化百度地图失败:', error);
  }
};

// 设置默认中心点函数
const setDefaultCenter = () => {
  if (!map.value) return;

  // 默认中心点 - 北京
  const defaultPoint = new window.BMap.Point(116.404, 39.915);
  map.value.centerAndZoom(defaultPoint, 16);
};

// 处理地图点击 - 普通函数
const handleMapClick = (e: any) => {
  const point = e.point;

  // 清除旧标记
  clearMarker();

  // 添加新标记
  addMarker(point);

  // 获取地址信息并发送坐标
  getAddressFromPoint(point);
};

// 创建防抖版本的地图点击处理函数 - 防抖延迟300ms
const debouncedMapClick = debounce(handleMapClick, 300);

// 添加标记
const addMarker = (point: any) => {
  if (map.value) {
    const marker = new window.BMap.Marker(point);
    map.value.addOverlay(marker);
    currentMarker.value = marker;

    // 居中显示但不改变缩放级别
    map.value.panTo(point);
  }
};

// 清除标记
const clearMarker = () => {
  if (map.value && currentMarker.value) {
    map.value.removeOverlay(currentMarker.value);
    currentMarker.value = null;
  }
};

// 根据坐标点获取地址 - 使用防抖
const getAddressFromPoint = debounce((point: any) => {
  if (!map.value) return;

  const geoc = new window.BMap.Geocoder();
  geoc.getLocation(point, (result: any) => {
    if (result) {
      const location = {
        latitude: point.lat.toString(),
        longitude: point.lng.toString(),
        address: result.address || '',
      };

      emit('change', location);
    } else {
      // 如果地址解析失败，也发送坐标信息
      const location = {
        latitude: point.lat.toString(),
        longitude: point.lng.toString()
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
    const point = new window.BMap.Point(
      parseFloat(props.coordinates.longitude),
      parseFloat(props.coordinates.latitude)
    );

    // 清除旧标记
    clearMarker();

    // 设置中心点和标记
    map.value.centerAndZoom(point);
    addMarker(point);
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
    map.value.removeEventListener('click', debouncedMapClick);
  }
  // 取消所有挂起的防抖函数
  debouncedMapClick.cancel();
  getAddressFromPoint.cancel();
  updateMapFromCoordinates.cancel();
});
</script>

<style scoped>
.baidu-map-container {
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