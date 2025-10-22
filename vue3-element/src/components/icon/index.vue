<template>
	<el-icon v-if="getIcoType === 'element'" :style="iconStyle" :class="['icon el-icon', customClass]">
		<component :is="getIconName"/>
	</el-icon>
	<i v-else-if="getIcoType === 'iconfont'" :style="iconStyle" :class="[getIcoType, getIconName, customClass]"></i>
	<svg v-else-if="getIcoType === 'svg'" :style="iconStyle" :class="['svg-icon', customClass]" aria-hidden="true">
		<use :xlink:href="`#${getIconName}`"/>
	</svg>
	<img v-else-if="getIcoType === 'local'" :src="getLocalIconPath" :style="iconStyle" :class="['local-icon', customClass]" />
	<el-icon v-else :style="iconStyle" :class="customClass">
		<component :is="fallbackIcon"/>
	</el-icon>
</template>

<script lang="ts" setup>
import { computed } from 'vue';
import * as ElementPlusIcons from '@element-plus/icons-vue';

// 定义图标类型
type IconType = 'element' | 'iconfont' | 'svg' | 'local' | '';

// 定义父组件传过来的值
const props = defineProps({
	// 图标名称，格式为 "类型 名称"，如 "element Plus" 或 "iconfont icon-user" 或 "local /path/to/icon.png"
	icon: {
		type: String,
		default: ''
	},
	// 图标大小
	size: {
		type: [Number, String],
		default: 16
	},
	// 图标颜色
	color: {
		type: String,
		default: ''
	},
	// 自定义类名
	class: {
		type: [String, Object, Array],
		default: ''
	},
	// 旋转动画
	spin: {
		type: Boolean,
		default: false
	},
	// 自定义样式
	customStyle: {
		type: Object,
		default: () => ({})
	},
	// 本地图标基础路径
	localIconBasePath: {
		type: String,
		default: '/src/assets/icons/local/'
	}
});

// 获取图标类型
const getIcoType = computed<IconType>(() => {
	if (!props.icon) return '';
	const parts = props.icon.split(' ');
	return (parts[0] as IconType) || '';
});

// 获取图标名称
const getIconName = computed(() => {
	if (!props.icon) return '';
	const parts = props.icon.split(' ');
	return parts.length > 1 ? parts[1] : '';
});

// 获取本地图标路径
const getLocalIconPath = computed(() => {
	if (getIcoType.value !== 'local' || !getIconName.value) return '';
	
	// 如果图标名称已经是完整路径（以 / 或 http 开头）
	if (getIconName.value.startsWith('/') || getIconName.value.startsWith('http')) {
		return getIconName.value;
	}
	
	// 否则拼接基础路径
	return `${props.localIconBasePath}${getIconName.value}`;
});

// 自定义类名
const customClass = computed(() => {
	const classes = [props.class];
	if (props.spin) classes.push('icon-spin');
	return classes;
});

// 图标样式
const iconStyle = computed(() => {
	const style: Record<string, string> = {
		...props.customStyle
	};
	
	if (typeof props.size === 'number') {
		style.fontSize = `${props.size}px`;
		// 为本地图标设置宽高
		if (getIcoType.value === 'local') {
			style.width = `${props.size}px`;
			style.height = `${props.size}px`;
		}
	} else {
		style.fontSize = props.size;
		// 为本地图标设置宽高
		if (getIcoType.value === 'local') {
			style.width = props.size;
			style.height = props.size;
		}
	}
	
	if (props.color && getIcoType.value !== 'local') {
		style.color = props.color;
	}
	
	return style;
});

// 备用图标
const fallbackIcon = computed(() => {
	// 尝试从Element Plus图标库中查找
	if (getIconName.value && (ElementPlusIcons as any)[getIconName.value]) {
		return (ElementPlusIcons as any)[getIconName.value];
	}
	return 'Memo'; // 默认图标
});
</script>

<style scoped>
.icon {
	display: inline-flex;
	align-items: center;
	justify-content: center;
}

.iconfont{
	margin-right: 10px;
}

.svg-icon {
	width: 1em;
	height: 1em;
	fill: currentColor;
	overflow: hidden;
}

.local-icon {
	display: inline-block;
	object-fit: contain;
	vertical-align: middle;
}

.icon-spin {
	animation: icon-spin 2s infinite linear;
}

@keyframes icon-spin {
	from {
		transform: rotate(0deg);
	}
	to {
		transform: rotate(360deg);
	}
}
</style>