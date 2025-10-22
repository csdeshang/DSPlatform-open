<template>
  <div class="min-h-screen flex items-center justify-center login-bg" :style="backgroundStyle">
    <div class="max-w-4xl w-full flex overflow-hidden rounded-xl shadow-2xl">
      <!-- 左侧图片区域 -->
      <div class="hidden md:block w-1/2 bg-indigo-600 p-12">
        <div class="h-full flex flex-col justify-between">
          <div>
            <h1 class="text-4xl font-bold text-white mb-6">后台管理系统</h1>
            <p class="text-indigo-200">高效、安全的企业级管理平台</p>
          </div>
          <div class="text-indigo-200 text-sm">
            © {{ currentYear }} 管理系统 版权所有
          </div>
        </div>
      </div>

      <!-- 右侧登录表单 -->
      <div class="w-full md:w-1/2 bg-white p-12">
        <div class="mb-10">
          <h2 class="text-3xl font-bold text-gray-800">欢迎登录</h2>
          <p class="text-gray-500 mt-2">请输入您的账号和密码</p>
        </div>

        <el-form :model="form" ref="formRef" :rules="formRules" class="space-y-6">
          <el-form-item prop="username" class="mb-6">
            <el-input v-model="form.username" placeholder="请输入账户" @keyup.enter="handleLogin" class="h-12 rounded-lg">
              <template #prepend>
                <Icon icon="element User" :size="20" class="text-gray-400" />
              </template>
            </el-input>
          </el-form-item>

          <el-form-item prop="password" class="mb-6">
            <el-input v-model="form.password" placeholder="请输入密码" type="password" @keyup.enter="handleLogin"
              :show-password="true" class="h-12 rounded-lg">
              <template #prepend>
                <Icon icon="element Lock" :size="20" class="text-gray-400" />
              </template>
            </el-input>
          </el-form-item>

          <el-form-item>
            <el-button type="primary" @click="handleLogin" :loading="isLoading"
              class="w-full h-12 text-base rounded-lg bg-indigo-600 hover:bg-indigo-700">
              {{ isLoading ? '登录中' : '登录' }}
            </el-button>
          </el-form-item>
        </el-form>

        <div class="mt-8 text-center text-sm text-gray-500">
          <p>登录即表示您同意我们的 <a href="#" class="text-indigo-600 hover:underline">服务条款</a> 和 <a href="#"
              class="text-indigo-600 hover:underline">隐私政策</a></p>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import Icon from '@/components/icon/index.vue'
import { reactive, shallowRef, computed, ref } from 'vue'
import type { FormInstance, FormRules } from 'element-plus'
import useUserInfoStore from '@/stores/modules/userInfo'
import { useRoute, useRouter } from "vue-router";

const userInfoStore = useUserInfoStore()
const route = useRoute()
const router = useRouter()

// 获取当前年份
const currentYear = computed(() => new Date().getFullYear());

// 随机背景图
// 导入背景图片
import bg1 from '@/assets/login/bg_1.jpg';
import bg2 from '@/assets/login/bg_2.jpg';
import bg3 from '@/assets/login/bg_3.jpg';
import bg4 from '@/assets/login/bg_4.jpg';
import bg5 from '@/assets/login/bg_5.jpg';
import bg6 from '@/assets/login/bg_6.jpg';
import bg7 from '@/assets/login/bg_7.jpg';
import bg8 from '@/assets/login/bg_8.jpg';
const randomBgNumber = Math.floor(Math.random() * 8) + 1;
const backgrounds = [bg1, bg2, bg3, bg4, bg5, bg6, bg7, bg8];
const backgroundStyle = computed(() => {
  return {
    backgroundImage: `url(${backgrounds[randomBgNumber - 1]})`
  }
});

const form = reactive({
  username: '',
  password: ''
})

const formRef = shallowRef<FormInstance>()
const formRules = reactive<FormRules>({
  username: [
    { required: true, message: '请输入用户名', trigger: 'blur' }
  ],
  password: [
    { required: true, message: '请输入密码', trigger: 'blur' }
  ]
})

// 登录状态
const isLoading = ref(false)

// 登录处理
const handleLogin = async () => {
  if (isLoading.value) return

  try {
    isLoading.value = true

    // 表单验证
    await formRef.value?.validate()

    // 登录请求
    await userInfoStore.login(form)

    // 登录成功后跳转
    const { query } = route;
    if (query.redirect && typeof query.redirect === 'string') {
      // 如果有重定向参数，则跳转到重定向路径
      router.push(query.redirect);
    } else {
      // 否则跳转到主页
      router.push('/admin/dashboard');
    }
  } catch (error) {
    console.error('登录失败:', error)
  } finally {
    isLoading.value = false
  }
}
</script>

<style scoped lang="scss">
.login-bg {
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center;
}


:deep(.el-input-group__prepend) {
  background-color: white;
}

:deep(.el-form-item__error) {
  margin-top: 4px;
}
</style>
