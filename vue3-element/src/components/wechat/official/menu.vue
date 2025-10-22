<template>
    <div class="wechat-menu-container">
        <div class="menu-editor">
            <div class="menu-preview">
                <div class="preview-header">微信公众号菜单预览</div>
                <div class="preview-body">
                    <div class="menu-bar">
                        <div v-for="(item, index) in menuData.button" :key="index" class="menu-item"
                            :class="{ active: activeMenuIndex === index && activeSubMenuIndex === -1 }"
                            @click="selectMenu(index, -1)">
                            {{ item.name || '菜单名称' }}
                            <div class="sub-menu">
                                <div v-for="(subItem, subIndex) in item.sub_button" :key="subIndex"
                                    class="sub-menu-item"
                                    :class="{ active: activeMenuIndex === index && activeSubMenuIndex === subIndex }"
                                    @click.stop="selectMenu(index, subIndex)">
                                    {{ subItem.name || '子菜单名称' }}
                                </div>
                                <div class="sub-menu-item add-sub-item" @click.stop="addSubMenu(index)"
                                    v-if="(!item.sub_button || item.sub_button.length < 5)">
                                    + 添加子菜单
                                </div>
                            </div>
                        </div>
                        <div class="menu-item add-item" v-if="menuData.button.length < 3" @click="addMainMenu">
                            + 添加菜单
                        </div>
                    </div>
                </div>
            </div>
            <div class="menu-form">
                <div class="form-header">
                    <span v-if="activeMenuIndex === -1">添加菜单</span>
                    <span v-else>
                        {{ activeSubMenuIndex === -1
                            ? `配置主菜单: ${menuData.button[activeMenuIndex]?.name || '未命名'}`
                            : `配置子菜单: ${menuData.button[activeMenuIndex]?.sub_button[activeSubMenuIndex]?.name || '未命名'}` }}
                    </span>
                </div>
                <div class="form-body" v-if="activeMenuIndex !== -1">
                    <el-form label-width="80px">
                        <el-form-item label="菜单名称">
                            <el-input v-model="currentMenuItem.name" placeholder="请输入菜单名称" maxlength="8"
                                show-word-limit></el-input>
                            <div class="name-tip">主菜单不超过4个汉字或8个字符</div>
                        </el-form-item>
                        <template v-if="activeSubMenuIndex > -1 || !hasSubMenu(activeMenuIndex)">
                            <el-form-item label="菜单类型">
                                <el-radio-group v-model="currentMenuItem.type">
                                    <el-radio value="view">跳转网页</el-radio>
                                    <el-radio value="click">点击事件</el-radio>
                                    <el-radio value="miniprogram">小程序</el-radio>
                                </el-radio-group>
                            </el-form-item>
                            <el-form-item label="菜单内容" v-if="currentMenuItem.type === 'view'">
                                <el-input v-model="currentMenuItem.url" placeholder="请输入链接地址"></el-input>
                            </el-form-item>
                            <el-form-item label="菜单KEY" v-if="currentMenuItem.type === 'click'">
                                <el-input v-model="currentMenuItem.key" placeholder="请输入菜单KEY值"></el-input>
                            </el-form-item>
                            <template v-if="currentMenuItem.type === 'miniprogram'">
                                <el-form-item label="小程序ID">
                                    <el-input v-model="currentMenuItem.appid" placeholder="请输入小程序appid"></el-input>
                                </el-form-item>
                                <el-form-item label="页面路径">
                                    <el-input v-model="currentMenuItem.pagepath" placeholder="请输入小程序页面路径"></el-input>
                                </el-form-item>
                                <el-form-item label="备用网页">
                                    <el-input v-model="currentMenuItem.url" placeholder="请输入备用网页链接"></el-input>
                                </el-form-item>
                            </template>
                        </template>
                    </el-form>
                    <div class="action-buttons">
                        <el-button @click="deleteMenu" type="danger" v-if="activeMenuIndex !== -1">删除</el-button>
                    </div>
                </div>
            </div>
        </div>
        <div class="menu-footer">
            <el-button @click="saveMenus" type="primary" :loading="loading">保存菜单</el-button>
            <el-button @click="publishMenus" type="primary" :loading="loading">发布菜单</el-button>
            <el-button @click="resetMenus">重置菜单</el-button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, defineOptions, onMounted } from 'vue';
import { ElMessage, ElMessageBox } from 'element-plus';
// 导入API函数，需要创建对应的文件
import { getWechatOfficialMenu, updateWechatOfficialMenu, publishWechatOfficialMenu } from '@/api/wechat/wechatOfficial';

// 定义组件名称
defineOptions({
    name: 'WechatMenuEditor'
});




// 菜单数据
const menuData = reactive({
    button: [] as any[]
});

// 正在编辑的菜单索引
const activeMenuIndex = ref(-1);
const activeSubMenuIndex = ref(-1);

// 加载状态
const loading = ref(false);

// 当前编辑的菜单项
const currentMenuItem = computed(() => {
    if (activeMenuIndex.value === -1) {
        return {};
    }

    if (activeSubMenuIndex.value === -1) {
        return menuData.button[activeMenuIndex.value];
    } else {
        return menuData.button[activeMenuIndex.value].sub_button[activeSubMenuIndex.value];
    }
});

// 检查是否有子菜单
const hasSubMenu = (index: number) => {
    return menuData.button[index] &&
        menuData.button[index].sub_button &&
        menuData.button[index].sub_button.length > 0;
};

// 选择菜单项
const selectMenu = (mainIndex: number, subIndex: number) => {
    activeMenuIndex.value = mainIndex;
    activeSubMenuIndex.value = subIndex;
};

// 添加主菜单
const addMainMenu = () => {
    if (menuData.button.length >= 3) {
        ElMessage.warning('最多只能添加3个一级菜单');
        return;
    }

    menuData.button.push({
        name: '新菜单',
        type: 'view',
        url: '',
        sub_button: []
    });

    activeMenuIndex.value = menuData.button.length - 1;
    activeSubMenuIndex.value = -1;
};

// 添加子菜单
const addSubMenu = (mainIndex: number) => {
    const index = mainIndex !== undefined ? mainIndex : activeMenuIndex.value;
    
    if (index === -1) {
        ElMessage.warning('请先选择一个主菜单');
        return;
    }

    if (!menuData.button[index].sub_button) {
        menuData.button[index].sub_button = [];
    }

    if (menuData.button[index].sub_button.length >= 5) {
        ElMessage.warning('每个一级菜单下最多只能添加5个子菜单');
        return;
    }

    if (menuData.button[index].type) {
        delete menuData.button[index].type;
        delete menuData.button[index].url;
        delete menuData.button[index].key;
    }

    menuData.button[index].sub_button.push({
        name: '新子菜单',
        type: 'view',
        url: ''
    });

    activeMenuIndex.value = index;
    activeSubMenuIndex.value = menuData.button[index].sub_button.length - 1;
};

// 删除菜单
const deleteMenu = () => {
    ElMessageBox.confirm('确定要删除此菜单吗?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
    }).then(() => {
        if (activeSubMenuIndex.value === -1) {
            menuData.button.splice(activeMenuIndex.value, 1);
            activeMenuIndex.value = -1;
        } else {
            menuData.button[activeMenuIndex.value].sub_button.splice(activeSubMenuIndex.value, 1);
            activeSubMenuIndex.value = -1;
        }
        ElMessage.success('删除成功');
    }).catch(() => { });
};

// 保存菜单
const saveMenus = async () => {
    if (menuData.button.length === 0) {
        ElMessage.warning('请至少添加一个菜单');
        return;
    }

    for (const menu of menuData.button) {
        if (!menu.name || menu.name.trim() === '') {
            ElMessage.warning('菜单名称不能为空');
            return;
        }

        if (!hasSubMenu(menuData.button.indexOf(menu))) {
            if (!menu.type) {
                ElMessage.warning(`菜单 "${menu.name}" 的类型不能为空`);
                return;
            }

            if (menu.type === 'view' && (!menu.url || menu.url.trim() === '')) {
                ElMessage.warning(`菜单 "${menu.name}" 的URL不能为空`);
                return;
            }

            if (menu.type === 'click' && (!menu.key || menu.key.trim() === '')) {
                ElMessage.warning(`菜单 "${menu.name}" 的KEY不能为空`);
                return;
            }
        } else {
            for (const subMenu of menu.sub_button) {
                if (!subMenu.name || subMenu.name.trim() === '') {
                    ElMessage.warning(`子菜单名称不能为空`);
                    return;
                }

                if (!subMenu.type) {
                    ElMessage.warning(`子菜单 "${subMenu.name}" 的类型不能为空`);
                    return;
                }

                if (subMenu.type === 'view' && (!subMenu.url || subMenu.url.trim() === '')) {
                    ElMessage.warning(`子菜单 "${subMenu.name}" 的URL不能为空`);
                    return;
                }

                if (subMenu.type === 'click' && (!subMenu.key || subMenu.key.trim() === '')) {
                    ElMessage.warning(`子菜单 "${subMenu.name}" 的KEY不能为空`);
                    return;
                }
            }
        }
    }

    loading.value = true;
    try {
        await updateWechatOfficialMenu(menuData);
        ElMessage.success('菜单保存成功');
    } catch (error) {
        console.error('保存菜单失败:', error);
        ElMessage.error('菜单保存失败');
    } finally {
        loading.value = false;
    }
};

// 发布菜单
const publishMenus = async () => {
    loading.value = true;
    try {
        await publishWechatOfficialMenu({});
        ElMessage.success('菜单发布成功');
    } catch (error) {
        console.error('发布菜单失败:', error);
        ElMessage.error('菜单发布失败');
    } finally {
        loading.value = false;
    }
};

// 重置菜单
const resetMenus = () => {
    ElMessageBox.confirm('确定要重置菜单吗? 所有未保存的更改将丢失', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
    }).then(() => {
        fetchMenuData();
        activeMenuIndex.value = -1;
        activeSubMenuIndex.value = -1;
        ElMessage.success('重置成功');
    }).catch(() => { });
};

// 获取菜单数据
const fetchMenuData = async () => {
    loading.value = true;
    try {
        const res = await getWechatOfficialMenu();
        if (res && res.data) {
            menuData.button = Array.isArray(res.data.button) ? res.data.button : [];
        }
    } catch (error) {
        console.error('获取菜单失败:', error);
        ElMessage.error('获取菜单失败');
    } finally {
        loading.value = false;
    }
};

// 组件挂载时获取数据
onMounted(() => {
    fetchMenuData();
});
</script>

<style scoped lang="scss">
.wechat-menu-container {
    display: flex;
    flex-direction: column;
    height: 100%;

    .menu-editor {
        display: flex;
        flex: 1;
        border: 1px solid #ebeef5;
        border-radius: 4px;
        overflow: hidden;

        .menu-preview {
            width: 360px;
            height: 500px;
            background-color: #f5f7fa;
            border-right: 1px solid #ebeef5;
            display: flex;
            flex-direction: column;

            .preview-header {
                padding: 12px;
                background-color: #f0f2f5;
                border-bottom: 1px solid #ebeef5;
                font-weight: bold;
                text-align: center;
            }

            .preview-body {
                flex: 1;
                display: flex;
                flex-direction: column;
                position: relative;
                background-color: #f8f8f8;

                .menu-bar {
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    display: flex;
                    height: 50px;
                    background-color: #fafafa;
                    border-top: 1px solid #e6e6e6;

                    .menu-item {
                        flex: 1;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        position: relative;
                        border-right: 1px solid #e6e6e6;
                        cursor: pointer;
                        height: 100%;
                        font-size: 14px;
                        
                        &:last-child {
                            border-right: none;
                        }
                        
                        &.active {
                            background-color: #e6e6e6;
                        }
                        
                        &:hover {
                            background-color: #f0f0f0;
                        }

                        &.add-item {
                            color: #409eff;
                            font-weight: bold;
                        }
                        
                        .sub-menu {
                            position: absolute;
                            bottom: 50px;
                            left: 0;
                            width: 100%;
                            background-color: #fff;
                            border: 1px solid #e6e6e6;
            
                            
                            .sub-menu-item {
                                padding: 8px 12px;
                                text-align: center;
                                border-bottom: 1px solid #f0f0f0;
                                
                                &:last-child {
                                    border-bottom: none;
                                }
                                
                                &.active {
                                    background-color: #e6e6e6;
                                }
                                
                                &:hover {
                                    background-color: #f0f0f0;
                                }

                                &.add-sub-item {
                                    color: #409eff;
                                    font-weight: bold;
                                }
                            }
                        }

              
                    }
                }
            }
        }

        .menu-form {
            flex: 1;
            padding: 20px;
            background-color: #fff;
            display: flex;
            flex-direction: column;

            .form-header {
                margin-bottom: 20px;
                font-weight: bold;
                font-size: 16px;
                color: #303133;
            }

            .form-body {
                flex: 1;

                .name-tip {
                    font-size: 12px;
                    color: #909399;
                    margin-top: 5px;
                }

                .action-buttons {
                    margin-top: 20px;
                    display: flex;
                    justify-content: space-between;
                }
            }
        }
    }

    .menu-footer {
        margin-top: 20px;
        padding: 15px 0;
        display: flex;
        justify-content: center;
        gap: 20px;
    }
}
</style>
