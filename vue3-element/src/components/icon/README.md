# Icon 图标组件

这个图标组件是一个灵活的图标显示解决方案，支持多种类型的图标，包括 Element Plus 图标、Iconfont 图标、SVG 图标和本地图片图标。

## 基本用法

```vue
<Icon icon="element Plus" />
```

## 图标类型

组件支持以下几种图标类型：

### 1. Element Plus 图标

使用 Element Plus 内置的图标

```vue
<Icon icon="element Plus" />
<Icon icon="element Setting" />
```

### 2. Iconfont 图标

使用阿里 Iconfont 图标库的图标

```vue
<Icon icon="iconfont icon-user" />
<Icon icon="iconfont icon-setting" />
```

### 3. SVG 图标

使用 SVG Sprite 方式引入的图标

```vue
<Icon icon="svg user" />
<Icon icon="svg setting" />
```

### 4. 本地图片图标

使用本地图片作为图标

```vue
<!-- 使用默认基础路径 -->
<Icon icon="local user.png" />

<!-- 使用完整路径 -->
<Icon icon="local /src/assets/icons/custom.png" />

<!-- 使用网络图片 -->
<Icon icon="local https://example.com/icon.png" />
```

## 属性

| 属性名 | 类型 | 默认值 | 说明 |
|--------|------|--------|------|
| icon | String | '' | 图标名称，格式为 "类型 名称" |
| size | Number/String | 16 | 图标大小，可以是数字或带单位的字符串 |
| color | String | '' | 图标颜色（对本地图片图标无效） |
| class | String/Object/Array | '' | 自定义类名 |
| spin | Boolean | false | 是否启用旋转动画 |
| customStyle | Object | {} | 自定义样式对象 |
| onClick | Function | null | 点击事件处理函数 |
| localIconBasePath | String | '/src/assets/icons/' | 本地图标的基础路径 |

## 事件

| 事件名 | 说明 | 参数 |
|--------|------|------|
| click | 点击图标时触发 | event: MouseEvent |

## 示例

### 基本用法

```vue
<Icon icon="element Plus" />
```

### 设置大小和颜色

```vue
<Icon icon="element Setting" size="24" color="red" />
```

### 使用旋转动画

```vue
<Icon icon="element Loading" spin />
```

### 自定义样式

```vue
<Icon 
  icon="element Star" 
  :customStyle="{ backgroundColor: '#f0f0f0', padding: '5px' }" 
/>
```

### 绑定点击事件

```vue
<Icon icon="element Delete" @click="handleDelete" />
```

### 使用 Iconfont 图标

```vue
<Icon icon="iconfont icon-user" size="20" color="blue" />
```

### 使用 SVG 图标

```vue
<Icon icon="svg dashboard" size="22" color="green" />
```

### 使用本地图片图标

```vue
<Icon icon="local logo.png" size="32" />
<Icon icon="local /custom/path/icon.png" size="40" />
```

## 注意事项

1. 使用 Element Plus 图标时，需要确保已安装 `@element-plus/icons-vue` 包
2. 使用 Iconfont 图标时，需要先在项目中引入 Iconfont 的样式文件
3. 使用 SVG 图标时，需要先配置 SVG Sprite 加载器
4. 使用本地图片图标时，可以通过 `localIconBasePath` 属性设置基础路径

## 高级用法

### 动态图标

```vue
<script setup>
import { ref } from 'vue'

const dynamicIcon = ref('element Setting')
const dynamicSize = ref(20)
const dynamicColor = ref('blue')

const changeIcon = () => {
  dynamicIcon.value = 'element Star'
  dynamicSize.value = 24
  dynamicColor.value = 'orange'
}
</script>

<template>
  <Icon :icon="dynamicIcon" :size="dynamicSize" :color="dynamicColor" />
  <el-button @click="changeIcon">更换图标</el-button>
</template>
```

### 结合其他组件

```vue
<el-button type="primary">
  <Icon icon="element Plus" size="16" color="white" class="mr-1" />
  添加
</el-button>
```

### 在菜单中使用

```vue
<el-menu-item>
  <Icon icon="element HomeFilled" size="18" />
  <span>首页</span>
</el-menu-item>
```

### 在表单中使用

```vue
<el-form-item label="图标">
  <div class="flex items-center">
    <Icon :icon="form.icon" size="24" class="mr-2" />
    <el-input v-model="form.icon" placeholder="请输入图标名称" />
  </div>
</el-form-item>
```