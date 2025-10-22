# vue-element-admin

This template should help get you started developing with Vue 3 in Vite.

## Recommended IDE Setup

[VSCode](https://code.visualstudio.com/) + [Volar](https://marketplace.visualstudio.com/items?itemName=Vue.volar) (and disable Vetur).

## Type Support for `.vue` Imports in TS

TypeScript cannot handle type information for `.vue` imports by default, so we replace the `tsc` CLI with `vue-tsc` for type checking. In editors, we need [Volar](https://marketplace.visualstudio.com/items?itemName=Vue.volar) to make the TypeScript language service aware of `.vue` types.

## Customize configuration

See [Vite Configuration Reference](https://vitejs.dev/config/).

## Project Setup

```sh
npm install
```

### Compile and Hot-Reload for Development

```sh
npm run dev
```

### Type-Check, Compile and Minify for Production

```sh
npm run build
```

### Run Unit Tests with [Vitest](https://vitest.dev/)

```sh
npm run test:unit
```

### Lint with [ESLint](https://eslint.org/)

```sh
npm run lint
```

# 图标选择器组件

一个功能强大的Vue 3图标选择器组件，支持Element Plus图标、Iconfont图标、SVG图标和本地图标。

## 特性

- 支持多种图标来源：Element Plus、Iconfont、SVG和本地图标
- 动态加载Iconfont图标
- 支持搜索过滤
- 分页显示
- 响应式设计
- 自定义预览
- 简洁美观的UI

## 安装

确保您的项目中已安装以下依赖：

```bash
npm install element-plus @element-plus/icons-vue @vueuse/core
```

## 使用方法

### 基本用法

```vue
<template>
  <div>
    <IconPicker v-model="selectedIcon" />
    <div v-if="selectedIcon" class="preview">
      <p>选中的图标：</p>
      <Icon :icon="selectedIcon" :size="32" />
      <span>{{ selectedIcon }}</span>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import IconPicker from '@/components/icon-picker/index.vue';
import Icon from '@/components/icon/index.vue';

const selectedIcon = ref('');
</script>
```

### 属性

| 属性名 | 类型 | 默认值 | 说明 |
|--------|------|--------|------|
| modelValue | String | '' | 选中的图标，格式为 "类型 名称"，如 "element Plus" |
| iconfontIcons | Array | [] | 自定义Iconfont图标列表 |
| svgIcons | Array | [] | 自定义SVG图标列表 |
| localIcons | Array | [] | 自定义本地图标列表 |
| showPreview | Boolean | true | 是否显示预览（如果为false，则点击图标直接选择） |
| svgIconsDir | String | '/src/assets/icons/svg/' | SVG图标目录 |
| localIconsDir | String | '/src/assets/icons/local/' | 本地图标目录 |

### 事件

| 事件名 | 参数 | 说明 |
|--------|------|------|
| update:modelValue | value | 当选中图标变化时触发 |
| change | value | 当选中图标变化时触发 |

## 图标格式

组件支持以下格式的图标：

1. **Element Plus图标**：`element IconName`
   例如：`element Plus`, `element Search`

2. **Iconfont图标**：`iconfont icon-name`
   例如：`iconfont icon-user`, `iconfont icon-setting`

3. **SVG图标**：`svg icon-name`
   例如：`svg dashboard`, `svg user`

4. **本地图标**：`local filename.ext`
   例如：`local logo.png`, `local icon.svg`

## 目录结构

为了使组件正常工作，请确保您的项目中有以下目录结构：

```
src/
├── assets/
│   └── icons/
│       ├── svg/        # SVG图标目录
│       └── local/      # 本地图标目录
├── styles/
│   └── iconfont/       # Iconfont样式目录
│       └── iconfont.css
└── components/
    ├── icon/           # 图标组件
    │   └── index.vue
    └── icon-picker/    # 图标选择器组件
        └── index.vue
```

## 自定义图标

### 添加SVG图标

将SVG文件放置在 `/src/assets/icons/svg/` 目录下，组件会自动加载。

### 添加本地图标

将图片文件(png/jpg/svg等)放置在 `/src/assets/icons/local/` 目录下，组件会自动加载。

### 添加Iconfont图标

1. 从 [iconfont.cn](https://www.iconfont.cn/) 下载图标
2. 将生成的CSS文件放置在 `/src/styles/iconfont/iconfont.css`
3. 在主样式文件中导入：
   ```scss
   // src/styles/index.scss
   @use './iconfont/iconfont.css';
   ```

## 注意事项

- 确保在使用此组件前已正确配置Vite以支持SVG和图片文件的导入
- 对于生产环境，建议使用构建时预编译的方式加载图标，以提高性能
- 组件使用了`import.meta.glob`，需要Vite 2.0+支持

## 示例

```vue
<template>
  <div class="icon-demo">
    <h2>图标选择器演示</h2>
    
    <div class="form-item">
      <label>选择图标：</label>
      <IconPicker v-model="icon" />
    </div>
    
    <div v-if="icon" class="icon-preview">
      <h3>预览</h3>
      <div class="preview-box">
        <Icon :icon="icon" :size="48" />
        <p>{{ icon }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import IconPicker from '@/components/icon-picker/index.vue';
import Icon from '@/components/icon/index.vue';

const icon = ref('element Star');
</script>

<style scoped>
.icon-demo {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
}

.form-item {
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.icon-preview {
  margin-top: 30px;
  border: 1px solid #ebeef5;
  border-radius: 4px;
  padding: 20px;
}

.preview-box {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 20px;
}
</style>
```
