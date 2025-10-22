import { defineStore } from 'pinia'
import { updateEditablePage, getEditablePageInfo } from '@/pages-admin/main/api/editable/editable'
import { ElMessage } from 'element-plus'

// 定义页面配置数据结构
interface PageConfig {
    globalSettings: {
        title: string;
        backgroundColor: string;
        // 其他页面级别设置...
    };
    elementsConfig: Array<{
        diyName: string;
        type: string;
        settings: Record<string, any>;
        // 其他元素级别配置...
    }>;
    selectedElementIndex: number | null;

}

// 定义可编辑页面数据结构
interface EditablePage {
    id: number;
    platform: string;
    store_id: number;
    title: string;
    type: string;
    page_config: string;
    is_default: number;
    create_at: number;
    update_at: number;
}

const useEditableStore = defineStore('editable', {
    state: () => {
        return {
            // 完整的页面配置
            pageConfig: {
                // 页面设置
                globalSettings: {
                    title: '我的页面',
                    backgroundColor: '#f5f5f5'
                },
                // 画布元素
                elementsConfig: [],
                // 选中元素索引
                selectedElementIndex: null,

            },
            // 选中元素tab
            selectedElementTab: 'content',
            // 当前页面ID
            currentPageId: null,
            // 页面加载状态
            loading: false,
            // 预览页面是否准备好
            previewReady: false
        }
    },
    getters: {
        // 获取选中的元素
        selectedElement: (state) => {
            return state.pageConfig.selectedElementIndex !== null ?
                state.pageConfig.elementsConfig[state.pageConfig.selectedElementIndex] : null;
        },
        // 获取页面设置
        globalSettings: (state) => {
            return state.pageConfig.globalSettings;
        },
        // 获取元素配置
        elementsConfig: (state) => {
            return state.pageConfig.elementsConfig;
        },
        // 获取选中元素索引
        selectedElementIndex: (state) => {
            return state.pageConfig.selectedElementIndex;
        }
    },
    actions: {
        // 处理拖拽开始
        handleDragStart(event: DragEvent, component: any) {
            event.dataTransfer?.setData('componentType', JSON.stringify(component));
        },

        // 处理拖拽放置
        handleDrop(event: DragEvent) {
            try {
                const componentData = JSON.parse(event.dataTransfer?.getData('componentType') || '{}');
                this.addComponent(componentData);
            } catch (error) {
                console.error('拖拽组件时出错:', error);
            }
        },

        // 选择元素
        selectElement(index) {
            this.pageConfig.selectedElementIndex = index;
        },

        // 取消选择元素
        deselectElement() {
            this.pageConfig.selectedElementIndex = null;
        },

        // 移动元素
        moveElement(index, direction) {
            const newIndex = index + direction;
            if (newIndex >= 0 && newIndex < this.pageConfig.elementsConfig.length) {
                const element = this.pageConfig.elementsConfig.splice(index, 1)[0];
                this.pageConfig.elementsConfig.splice(newIndex, 0, element);
                this.pageConfig.selectedElementIndex = newIndex;
            }
        },

        // 复制元素
        duplicateElement(index) {
            const element = JSON.parse(JSON.stringify(this.pageConfig.elementsConfig[index]));
            this.pageConfig.elementsConfig.splice(index + 1, 0, element);
            this.pageConfig.selectedElementIndex = index + 1;
        },

        // 删除元素
        removeElement(index) {
            this.pageConfig.elementsConfig.splice(index, 1);
            if (this.pageConfig.elementsConfig.length === 0 || this.pageConfig.selectedElementIndex >= this.pageConfig.elementsConfig.length) {
                this.pageConfig.selectedElementIndex = null;
            }
        },

        // 加载页面数据
        async loadPageData(id) {
            this.loading = true;
            try {
                const response = await getEditablePageInfo(id);
                if (response.code === 10000 && response.data) {
                    this.currentPageId = id;

                    // 定义默认配置
                    const defaultPageConfig = {
                        globalSettings: {
                            title: response.data.title,
                            backgroundColor: '#f5f5f5'
                        },
                        elementsConfig: [],
                        selectedElementIndex: null // 或者设置为 0
                    };

                    // 解析页面配置
                    let pageConfig;
                    try {
                        pageConfig = JSON.parse(response.data.page_config);
                    } catch (e) {
                        console.error('解析页面配置失败:', e);
                        pageConfig = defaultPageConfig;
                    }


                    // 检查 pageConfig 是否有效
                    if (!pageConfig || !pageConfig.elementsConfig) {
                        console.warn('pageConfig 为空，使用默认配置');
                        pageConfig = defaultPageConfig; // 再次使用默认配置
                    }




                    this.pageConfig = pageConfig;

                    // 更新全局设置中的标题
                    this.pageConfig.globalSettings.title = response.data.title || '未命名页面';

                    ElMessage.success('页面加载成功');
                    return true;
                } else {
                    ElMessage.error(response.message || '页面加载失败');
                    return false;
                }
            } catch (error) {
                console.error('加载页面失败:', error);
                ElMessage.error('加载失败，请重试');
                return false;
            } finally {
                this.loading = false;
            }
        },

        // 保存页面数据
        async savePageData() {
            try {
                if (!this.currentPageId) {
                    ElMessage.error('页面ID不存在，无法保存');
                    return false;
                }

                // 创建一个副本，移除 selectedElementIndex
                const saveData = JSON.parse(JSON.stringify(this.pageConfig));
                saveData.selectedElementIndex = null; // 保存时清除选中状态

                const params = {
                    id: this.currentPageId,
                    title: this.pageConfig.globalSettings.title,
                    page_config: JSON.stringify(saveData),
                    update_at: Math.floor(Date.now() / 1000) // 当前时间戳（秒）
                };

                const response = await updateEditablePage(params);

                if (response.code === 10000) {
                    ElMessage.success('页面保存成功');
                    return true;
                } else {
                    ElMessage.error(response.message || '保存失败');
                    return false;
                }
            } catch (error) {
                console.error('保存页面失败:', error);
                ElMessage.error('保存失败，请重试');
                return false;
            }
        },

        // 设置预览页面准备状态
        setPreviewReady(ready) {
            this.previewReady = ready;
        },

        // 发送数据到预览
        sendDataToPreview(previewIframe: HTMLIFrameElement) {
            if (!previewIframe || !previewIframe.contentWindow) {
                console.warn('预览iframe不可用');
                return false;
            }
            
            if (!this.previewReady) {
                console.log('预览页面尚未准备好，无法发送数据');
                return false;
            }
            
            try {
                // 创建数据副本，避免引用问题
                const dataToSend = JSON.parse(JSON.stringify(this.pageConfig));
                previewIframe.contentWindow.postMessage(JSON.stringify(dataToSend), '*');
                return true;
            } catch (error) {
                console.error('发送数据到预览时出错:', error);
                return false;
            }
        },

        // 处理接收到的消息
        handleMessage(event: MessageEvent) {
            // 这里可以添加安全性检查，确保消息来源是可信的
            // if (event.origin !== 'http://your-child-origin.com') return;
            console.log('处理接收到的消息:', event.data);

            try {
                const data = typeof event.data === 'string' ? JSON.parse(event.data) : event.data;

                // 处理预览页面加载完成的消息
                if (data.type === 'PREVIEW_MOUNTED' && data.status === 'ready') {
                    console.log('预览页面已准备就绪');
                    this.setPreviewReady(true);
                    return;
                }

                // 更新接收到的消息
                if (data.globalSettings) {
                    Object.assign(this.pageConfig.globalSettings, data.globalSettings);
                }
                if (data.elementsConfig) {
                    // 清空当前数组并添加新元素
                    this.pageConfig.elementsConfig.splice(0, this.pageConfig.elementsConfig.length, ...data.elementsConfig);
                }
                if ('selectedElementIndex' in data) {
                    this.pageConfig.selectedElementIndex = data.selectedElementIndex;
                }
            } catch (error) {
                console.error('处理消息时出错:', error);
            }
        },

        // 添加组件（用于双击添加）
        addComponent(component: any) {
            // 深拷贝组件数据，避免修改原始数据
            const newComponent = JSON.parse(JSON.stringify(component));

            // 初始化组件设置（具体的初始化会由组件自己完成）
            newComponent.settings = newComponent.settings || {};

            this.pageConfig.elementsConfig.push(newComponent);
            this.pageConfig.selectedElementIndex = this.pageConfig.elementsConfig.length - 1;
        }
    }
})

export default useEditableStore