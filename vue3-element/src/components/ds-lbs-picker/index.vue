<template>
    <div v-if="lsbConfig?.lbs_is_enabled == '1'">
        <el-form-item label="店铺坐标">
            <!-- 分开显示经纬度输入框 -->
            <div class="coordinate-inputs">
                <el-input 
                    v-model="localCoordinates.longitude" 
                    placeholder="经度"
                    @input="handleInputChange"
                >
                    <template #prepend>经度</template>
                </el-input>
                <el-input 
                    v-model="localCoordinates.latitude" 
                    placeholder="纬度"
                    @input="handleInputChange"
                >
                    <template #prepend>纬度</template>
                </el-input>
            </div>

            <!-- 地图容器 -->
            <div class="map-container">
                <!-- 地图组件 - 统一使用change事件 -->
                <BaiduMap 
                    v-if="lsbConfig.lbs_default_provider === 'baidu'" 
                    :config="lsbConfig.lbs_config_baidu" 
                    :coordinates="localCoordinates" 
                    @change="handleMapChange"
                />
                <GaodeMap 
                    v-if="lsbConfig.lbs_default_provider === 'gaode'" 
                    :config="lsbConfig.lbs_config_gaode" 
                    :coordinates="localCoordinates" 
                    @change="handleMapChange"
                />
                <TencentMap 
                    v-if="lsbConfig.lbs_default_provider === 'tencent'" 
                    :config="lsbConfig.lbs_config_tencent" 
                    :coordinates="localCoordinates" 
                    @change="handleMapChange"
                />
                <TiandituMap 
                    v-if="lsbConfig.lbs_default_provider === 'tianditu'" 
                    :config="lsbConfig.lbs_config_tianditu" 
                    :coordinates="localCoordinates" 
                    @change="handleMapChange"
                />
            </div>
        </el-form-item>
    </div>




</template>

<script setup lang="ts">
import { onMounted, reactive, ref, watch } from 'vue';
import BaiduMap from './baidu-map.vue';
import GaodeMap from './gaode-map.vue';
import TencentMap from './tencent-map.vue';
import TiandituMap from './tianditu-map.vue';
import { useConfigStore } from '@/stores/modules/config';

// 统一的坐标接口
interface Coordinates {
    latitude: string;
    longitude: string;
    address?: string;   // 可选的地址信息
    name?: string;      // 可选的位置名称
}

const props = defineProps({
    // 对象形式的坐标参数
    coordinates: {
        type: Object as () => Coordinates,
        default: () => ({ latitude: '', longitude: '' })
    }
});

// 统一使用change事件
const emit = defineEmits(['change']);

// 内部状态 - 使用响应式对象
const localCoordinates = reactive<Coordinates>({
    latitude: '',
    longitude: '',
    address: '',
});

const isLoading = ref(true);

// 监听外部传入的coordinates变化
watch(() => props.coordinates, (newVal) => {
    if (newVal) {
        // 只复制存在的属性
        Object.keys(newVal).forEach(key => {
            if (key in localCoordinates && newVal[key as keyof Coordinates] !== undefined) {
                (localCoordinates as any)[key] = newVal[key as keyof Coordinates];
            }
        });
    }
}, { immediate: true, deep: true });

// 处理输入框变化
const handleInputChange = () => {
    emitChange();
};

// 统一处理地图组件的change事件
const handleMapChange = (coordinates: Coordinates) => {
    // 更新本地坐标对象
    Object.keys(coordinates).forEach(key => {
        if (key in localCoordinates) {
            (localCoordinates as any)[key] = coordinates[key as keyof Coordinates];
        }
    });
    
    emitChange();
};

// 触发change事件
const emitChange = () => {
    const coordinates = { ...localCoordinates };
    // 主要使用change事件
    emit('change', coordinates);
};

interface LsbConfig {
    lbs_is_enabled: string;
    lbs_default_provider: string;
    lbs_config_tianditu: string;
    lbs_config_gaode: string;
    lbs_config_tencent: string;
    lbs_config_baidu: string;
}

// 获取地图配置信息
const configStore = useConfigStore();
const lsbConfig = ref<LsbConfig | null>(null);

async function fetchLsbConfig() {
    isLoading.value = true;
    try {
        // 获取指定类型的所有配置
        const res = await configStore.fetchConfigsByType('lbs');
        lsbConfig.value = res;
    } catch (error) {
        console.error('获取LBS配置失败:', error);
    } finally {
        isLoading.value = false;
    }
}

onMounted(() => {
    fetchLsbConfig();
});
</script>

<style scoped>
.coordinate-inputs {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}

.coordinate-inputs .el-input {
    flex: 1;
}

.map-container {
    width: 100%;
    height: 600px;
    border: 1px solid #dcdfe6;
    border-radius: 4px;
    margin-top: 10px;
    overflow: hidden;
}


</style>
