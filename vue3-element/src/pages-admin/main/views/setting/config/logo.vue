<template>
    <div>
        
        <PickerImage v-model="formData.site_logo" :limit=1></PickerImage>
        formData.site_logo：{{ formData.site_logo }}



        上传测试 单个
        <PickerImage v-model="formData.dange" :limit=1 type="image"></PickerImage>
        上传测试 多个
        <PickerImage v-model="formData.duoge" :limit=100 type="image" ></PickerImage>

        Video
        <PickerImage v-model="formData.video" :limit=1 type="video"></PickerImage>

        {{ formData.video }}

        Video 多个
        <PickerImage v-model="formData.duoge_video" :limit=5 type="video"></PickerImage>

        {{ formData.duoge_video }}

    </div>
</template>

<script lang="ts" setup>
import { reactive } from 'vue';

import PickerImage from '@/components/attachment/picker-image.vue'


const formData = reactive<Record<string, string>>({
    site_name: '',
    site_logo: '',
    dange: '',
    duoge: '',
    video: '',
    duoge_video: '',
})
</script>