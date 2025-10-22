# IconPicker 图标选择器

这是一个基于 Icon 组件的图标选择器，支持选择 Element Plus 图标、Iconfont 图标、SVG 图标和本地图片图标。

## 基本用法

```vue
<template>
  <IconPicker v-model="selectedIcon" @change="handleIconChange" />
</template>

<script setup>
import { ref } from 'vue';
import IconPicker from '@/components/icon-picker/index.vue';

const selectedIcon = ref('');

const handleIconChange = (icon) => {
  console.log('选择的图标:', icon);
};
</script>
```

## 属性

| 属性名 | 类型 | 默认值 | 说明 |
|--------|------|--------|------|
| modelValue | String | '' | 选中的图标，格式为 "类型 名称" |
| iconfontIcons | Array | [] | 自定义 Iconfont 图标列表 |
| svgIcons | Array | [] | 自定义 SVG 图标列表 |
| localIcons | Array | [] | 自定义本地图标列表 |

## 事件

| 事件名 | 说明 | 参数 |
|--------|------|------|
| update:modelValue | 选中图标时触发 | icon: String |
| change | 选中图标时触发 | icon: String |

## 示例

### 基本用法

```vue
<IconPicker v-model="selectedIcon" />
```

### 设置默认值

```vue
<IconPicker v-model="selectedIcon" />

<script setup>
import { ref } from 'vue';

const selectedIcon = ref('element Plus');
</script>
```

### 自定义图标列表

```vue
<IconPicker 
  v-model="selectedIcon" 
  :iconfont-icons="iconfontIcons"
  :svg-icons="svgIcons"
  :local-icons="localIcons"
/>

<script setup>
import { ref } from 'vue';

const selectedIcon = ref('');
const iconfontIcons = ref(['icon-user', 'icon-setting', 'icon-home']);
const svgIcons = ref(['dashboard', 'user', 'setting']);
const localIcons = ref(['logo.png', 'avatar.jpg', 'icon.svg']);
</script>
```

### 在表单中使用

```vue
<el-form :model="form" label-width="100px">
  <el-form-item label="菜单图标">
    <IconPicker v-model="form.icon" />
  </el-form-item>
</el-form>

<script setup>
import { reactive } from 'vue';

const form = reactive({
  icon: 'element Menu'
});
</script>
```

## 注意事项

1. 该组件依赖于 Icon 组件，确保已正确引入和配置 Icon 组件
2. 如果需要使用自定义图标列表，请确保提供正确的图标名称格式
3. 对于 Iconfont 图标，需要提供完整的类名（如 'icon-user'）
4. 对于本地图标，可以提供文件名或完整路径 