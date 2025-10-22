<template>
  <div class="gaode-map-container">
    <!-- 高德地图容器 -->
    <div ref="mapContainer" class="map-instance"></div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch } from "vue";
import { debounce } from 'lodash';
import AMapLoader from "@amap/amap-jsapi-loader";

// 在组件内部定义坐标接口
interface Coordinates {
  latitude: string;
  longitude: string;
  address?: string;
}

interface GaoDeConfig {
  browser_key: string;
  browser_secret: string;
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

// 地图实例和标记
const mapContainer = ref<HTMLDivElement | null>(null);
const map = ref<any>(null);
const marker = ref<any>(null);
const geocoder = ref<any>(null);
// AMap API引用
const AMapAPI = ref<any>(null);

// 解析配置JSON字符串
const parseConfig = (): GaoDeConfig | null => {
  try {
    return JSON.parse(props.config);
  } catch (e) {
    console.error('解析高德地图配置失败:', e);
    return null;
  }
};

// 初始化地图
const initMap = async () => {
  if (!mapContainer.value) return;
  
  try {
    // 解析配置获取API Key
    const config = parseConfig();
    if (!config || !config.browser_key) {
      console.error('缺少高德地图API密钥');
      return;
    }
    
    // 使用AMapLoader加载高德地图
    AMapAPI.value = await AMapLoader.load({
      key: config.browser_key, // 申请好的Web端开发者Key
      version: "2.0",          // 指定要加载的 JSAPI 的版本
      plugins: [              // 需要使用的的插件列表
        'AMap.ToolBar',
        'AMap.Scale',
        'AMap.Geocoder'
      ]
    });
    
    // 创建地图实例
    map.value = new AMapAPI.value.Map(mapContainer.value, {
      viewMode: '2D',          // 默认使用2D模式
      zoom: 13,                // 初始缩放级别
      resizeEnable: true,      // 是否监控地图容器尺寸变化
    });
    
    // 添加控件
    map.value.addControl(new AMapAPI.value.ToolBar());
    map.value.addControl(new AMapAPI.value.Scale());
    
    // 创建地理编码实例
    geocoder.value = new AMapAPI.value.Geocoder({
      city: "全国", // 城市，默认："全国"
      radius: 1000 // 范围，默认：500
    });
    
    // 判断是否有初始坐标
    if (props.coordinates.latitude && props.coordinates.longitude) {
      // 如果有坐标则展示
      updateMapFromCoordinates();
    } else {
      // 如果没有则设置默认中心点（北京）
      map.value.setCenter([116.397428, 39.90923]);
    }
    
    // 点击地图事件
    map.value.on('click', handleMapClick);
    
  } catch (error) {
    console.error('初始化高德地图失败:', error);
  }
};

// 创建标记点
const createMarker = (position: [number, number]) => {
  if (!map.value || !AMapAPI.value) return;
  
  try {
    // 清除旧标记
    clearMarker();
    
    // 创建新标记
    marker.value = new AMapAPI.value.Marker({
      position: position,
      draggable: false,  // 可拖动
      cursor: 'move',    // 鼠标悬停点标记时的鼠标样式
      icon: new AMapAPI.value.Icon({
        size: new AMapAPI.value.Size(25, 34),  // 图标尺寸
        image: '//a.amap.com/jsapi_demos/static/demo-center/icons/poi-marker-default.png',  // 图标的取图地址
        imageSize: new AMapAPI.value.Size(25, 34)  // 图标所用图片大小
      }),
      offset: new AMapAPI.value.Pixel(-13, -30)  // 设置偏移量
    });
    
    // 将标记添加到地图
    map.value.add(marker.value);
    
    // 添加拖拽结束事件（如果标记可拖拽）
    if (marker.value.draggable) {
      marker.value.on('dragend', (e: any) => {
        const position = marker.value.getPosition();
        getAddressByLocation([position.lng, position.lat]);
      });
    }
  } catch (error) {
    console.error('创建标记失败:', error);
  }
};

// 清除标记
const clearMarker = () => {
  if (!map.value || !marker.value) return;
  
  map.value.remove(marker.value);
  marker.value = null;
};

// 处理地图点击事件
const handleMapClick = debounce((e: any) => {
  // 获取点击位置的经纬度
  const lng = e.lnglat.getLng();
  const lat = e.lnglat.getLat();
  
  // 创建标记
  createMarker([lng, lat]);
  
  // 获取地址
  getAddressByLocation([lng, lat]);
}, 300);

// 根据经纬度获取地址
const getAddressByLocation = (lnglat: [number, number]) => {
  if (!geocoder.value) return;
  
  geocoder.value.getAddress(lnglat, (status: string, result: any) => {
    if (status === 'complete' && result.info === 'OK') {
      // 成功获取地址
      const address = result.regeocode.formattedAddress;
      
      // 发送坐标变更事件
      emit('change', {
        longitude: lnglat[0].toString(),
        latitude: lnglat[1].toString(),
        address: address
      });
    } else {
      // 地址获取失败，只发送坐标
      emit('change', {
        longitude: lnglat[0].toString(),
        latitude: lnglat[1].toString()
      });
    }
  });
};

// 根据props中的坐标更新地图
const updateMapFromCoordinates = () => {
  if (!map.value || !props.coordinates.latitude || !props.coordinates.longitude) return;
  
  try {
    // 转换为数值类型
    const lng = parseFloat(props.coordinates.longitude);
    const lat = parseFloat(props.coordinates.latitude);
    
    if (isNaN(lng) || isNaN(lat)) {
      console.error('无效的经纬度值');
      return;
    }
    
    // 设置地图中心点
    map.value.setCenter([lng, lat]);
    
    // 添加标记
    createMarker([lng, lat]);
    
  } catch (error) {
    console.error('更新地图位置失败:', error);
  }
};

// 监听坐标变化
watch(() => props.coordinates, () => {
  if (map.value) {
    updateMapFromCoordinates();
  }
}, { deep: true });

// 生命周期钩子
onMounted(() => {
  initMap();
});

onUnmounted(() => {
  // 销毁地图
  if (map.value) {
    map.value.destroy();
    map.value = null;
  }
});
</script>

<style scoped>
.gaode-map-container {
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
