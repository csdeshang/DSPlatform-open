<template>
  <el-cascader
    v-model="selectedValue"
    :options="areaOptions"
    :props="cascaderProps"
    :loading="loading"
    :placeholder="placeholder"
    @change="handleChange"
    clearable
  ></el-cascader>
</template>

<script lang="ts" setup>
import { ref, watch, onMounted } from 'vue'
import { ElMessage } from 'element-plus'
import { getSysAreaTree } from '@/api/system/sysArea'

// 定义组件属性
const props = defineProps({
  modelValue: {
    type: Number,
    default: 0
  },
  placeholder: {
    type: String,
    default: '请选择地区'
  }
})

// 定义组件事件
const emit = defineEmits(['update:modelValue', 'change'])

// 内部状态
const selectedValue = ref(props.modelValue)
const areaOptions = ref([])
const loading = ref(false)
// 地区映射，仅存储名称信息
const areaNameMap = ref<Record<number, string[]>>({})

// 级联选择器配置
const cascaderProps = {
  value: 'id',
  label: 'name',
  children: 'children',
  checkStrictly: true,
  emitPath: false
}

// 加载地区树数据
const loadAreaTree = async () => {
  if (loading.value) return
  
  try {
    loading.value = true
    const res = await getSysAreaTree()
    
    if (res.code === 10000 && res.data) {
      areaOptions.value = res.data
      // 构建地区名称映射
      buildAreaNameMap(res.data)
    } else {
      ElMessage.error('获取地区数据失败')
    }
  } catch (error) {
    console.error('获取地区树失败:', error)
    ElMessage.error('获取地区数据失败')
  } finally {
    loading.value = false
  }
}

// 递归构建地区名称映射
const buildAreaNameMap = (areas: any[], parentNames: string[] = []) => {
  if (!areas || !areas.length) return
  
  areas.forEach(area => {
    const currentNames = [...parentNames, area.name]
    // 只存储地区ID和对应的名称数组（省市县）
    areaNameMap.value[area.id] = currentNames
    
    // 递归处理子地区
    if (area.children && area.children.length > 0) {
      buildAreaNameMap(area.children, currentNames)
    }
  })
}

// 选择处理函数
const handleChange = (value: number) => {
  if (!value) {
    // 处理清空的情况
    emit('update:modelValue', 0)
    emit('change', { id: 0, names: [] })
    return
  }
  
  // 从映射中获取地区名称数组
  const names = areaNameMap.value[value] || []
  
  // 构建返回对象：只包含ID和名称数组
  const result = {
    id: value,
    names: names // 这是一个数组，包含省市县的名称，例如 ['北京市', '朝阳区']
  }
  
  emit('update:modelValue', value)
  emit('change', result)
}

// 监听外部传入的值变化
watch(() => props.modelValue, (newVal) => {
  selectedValue.value = newVal
})

// 初始化
onMounted(() => {
  loadAreaTree()
})
</script>

<style scoped>
/* 可以添加自定义样式 */
</style>
