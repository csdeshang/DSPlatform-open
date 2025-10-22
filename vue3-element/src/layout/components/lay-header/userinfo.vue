<template>
  <div class="userinfo-container">
    <el-dropdown trigger="click" @command="handleCommand">
      <div class="flex items-center cursor-pointer">
        <span class="username">{{ userInfoStore.userInfo.username }}</span>
        <el-icon class="ml-1">
          <ArrowDown />
        </el-icon>
      </div>
      <template #dropdown>
        <el-dropdown-menu>
          <el-dropdown-item command="edit-password">修改密码</el-dropdown-item>
          <el-dropdown-item divided command="logout">退出登录</el-dropdown-item>
        </el-dropdown-menu>
      </template>
    </el-dropdown>
    <edit-password ref="editPasswordDialog" />
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { ElMessageBox } from 'element-plus'
import { ArrowDown } from '@element-plus/icons-vue'
import useUserInfoStore from '@/stores/modules/userInfo'
import EditPassword from './edit-password.vue'


const userInfoStore = useUserInfoStore()

// 处理下拉菜单命令
const handleCommand = (command: string) => {
  if (command === 'logout') {
    ElMessageBox.confirm('确定要退出登录吗?', '提示', {
      confirmButtonText: '确定',
      cancelButtonText: '取消',
      type: 'warning'
    }).then(() => {
      userInfoStore.logout()
    }).catch(() => { })
  } else if (command === 'edit-password') {
    handleEditPassword()
  }
}


// 修改密码弹窗
const editPasswordDialog: Record<string, any> | null = ref(null)
const handleEditPassword = () => {
    editPasswordDialog.value?.openDialog()
}
</script>

<style scoped>
.userinfo-container {
  display: flex;
  align-items: center;
  height: 100%;
}

/* 确保用户名在深色背景下可见 */
.username {
  color: var(--app-header-text);
  font-size: 14px;
  font-weight: 500;
}

/* 确保下拉图标在深色背景下可见 */
.el-icon {
  color: var(--app-header-text);
  font-size: 12px;
  margin-left: 4px;
}

/* 确保下拉菜单样式正确 */
:deep(.el-dropdown-menu) {
  padding: 5px 0;
}

:deep(.el-dropdown-menu__item) {
  line-height: 36px;
  padding: 0 16px;
  font-size: 14px;
}

/* 鼠标悬停效果 */
.userinfo-container:hover .username,
.userinfo-container:hover .el-icon {
  opacity: 0.8;
}
</style>