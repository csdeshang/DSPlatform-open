import { ElMessage, ElMessageBox, type ElTree } from "element-plus";
import { reactive, ref, shallowRef, toRaw, watch } from "vue";

// admin 与 user 的接口 共用 ， 所有图片 附件 分类 接口
import {
    //附件分类
    getAttachmentCateList,
    createAttachmentCate,
    updateAttachmentCate,
    deleteAttachmentCate,

    // 附件
    getAttachmentFilePages,
    deleteAttachmentFile,
    updateBatchAttachmentFile,

} from '@/api/attachment'

export function useAttachmentCate(type: string) {

    const treeRef = shallowRef<InstanceType<typeof ElTree>>()

    // 附件分类列表
    const attachment_cate_list = ref([]);
    // 当前选中附件分类ID
    const current_cid = ref('');

    // 获取分组列表
    const handleGetAttachmentCateList = async () => {
        const res = await getAttachmentCateList({
            type
        })
        const item: any[] = [
            {
                name: '全部',
                pid: 0,
                id: ''
            },
            {
                name: '未分组',
                pid: 0,
                id: 0
            }
        ]
        
        attachment_cate_list.value = res.data
        attachment_cate_list.value.unshift(...item)
        setTimeout(() => {
            treeRef.value?.setCurrentKey(current_cid.value)
        }, 0)
    }


    // 添加分组
    const handleCreateAttachmentCate = async (name: string, pid: number) => {
        await createAttachmentCate({
            type,
            name: name,
            pid: pid
        })
        handleGetAttachmentCateList()
    }

    // 编辑分组
    const handleUpdateAttachmentCate = async (name: string, id: number) => {
        await updateAttachmentCate({
            id,
            name: name
        })
        handleGetAttachmentCateList()
    }

    // 删除分组
    const handleDeleteAttachmentCate = async (cid: number) => {
        ElMessageBox.confirm('您确定是否删除此分类', 'Warning',
            {
                confirmButtonText: '确认',
                cancelButtonText: '取消',
                type: 'warning'
            }
        ).then(() => {
            deleteAttachmentCate(cid).then(() => {
                current_cid.value = ''
                handleGetAttachmentCateList()
            }).catch(() => {
            })
        })
    }


    //选中分类
    const handleAttachmentCateSelect = (item: any) => {
        current_cid.value = item.id
    }

    return {
        treeRef,
        current_cid,
        attachment_cate_list,
        handleGetAttachmentCateList,
        handleCreateAttachmentCate,
        handleUpdateAttachmentCate,
        handleDeleteAttachmentCate,
        handleAttachmentCateSelect
    }

}


export function useAttachmentFile(
    current_cid: string,
    type: string,
    limit: number,
    page_size: number

) {

    const tableData = reactive({
        moveCateId: 0,//移动分类ID
        show_type: 'grid', //表格展示方式
        page_current: 1,
        page_size: page_size,
        total: 0,
        loading: true,
        data: [],
        searchParam: {
            cid: current_cid,
            type: type,
            name: '',
        }

    })


    // 获取附件列表
    const handleGetAttachmentFilePages = async () => {
        
        getAttachmentFilePages({
            page_current: tableData.page_current,
            page_size: tableData.page_size,
            ...tableData.searchParam,
        }).then(res => {
            tableData.loading = false

            tableData.data = res.data.data
            tableData.total = res.data.total

            clearSelected()

        }).catch(() => {
            tableData.loading = false
        })
    }
    //文件重命名
    const handleRenameAttachmentFile = async (name: string, ids: string) => {
        await updateBatchAttachmentFile({
            ids,
            name
        })
        handleGetAttachmentFilePages()
    }
    //重新加载页面
    const reloadAttachmentFileList = async () => {
        tableData.page_current = 1
        handleGetAttachmentFilePages()
    }
    // 单个删除
    const handleDeleteAttachmentFile = async (ids: string) => {

        ElMessageBox.confirm('确定要删除该文件吗？', 'Warning',
            {
                confirmButtonText: '确认',
                cancelButtonText: '取消',
                type: 'warning'
            }
        ).then(() => {
            deleteAttachmentFile({ ids }).then(() => {
                handleGetAttachmentFilePages()
                clearSelected()
            }).catch(() => {
            })
        })
    }


    //批量删除 选中
    const batchDeleteAttachmentFile = async () => {
        const ids = Object.keys(toRaw(selectedFile))
        ElMessageBox.confirm('确定要删除该文件吗？', 'Warning',
            {
                confirmButtonText: '确认',
                cancelButtonText: '取消',
                type: 'warning'
            }
        ).then(() => {
            deleteAttachmentFile({ ids }).then(() => {
                handleGetAttachmentFilePages()
                clearSelected()
            }).catch(() => {
            })
        })
    }

    //批量移动
    const batchMoveAttachmentFile = async () => {
        const ids = Object.keys(toRaw(selectedFile))
        ElMessageBox.confirm('确定要移动选择文件吗？', 'Warning',
            {
                confirmButtonText: '确认',
                cancelButtonText: '取消',
                type: 'warning'
            }
        ).then(() => {
            updateBatchAttachmentFile({
                ids,
                cid: tableData.moveCateId
            }).then(() => {
                tableData.moveCateId = 0
                handleGetAttachmentFilePages()
                clearSelected()
            }).catch(() => {
                tableData.moveCateId = 0
            })
        })
    }

    // 选择文件
    const selectedFile: Record<string, any> = reactive({})
    const selectedFileIndex: Record<string, any> = reactive([])
    const selectFile = (data: any) => {
        if (selectedFile[data.id]) {
            delete selectedFile[data.id]
            selectedFileIndex.splice(selectedFileIndex.indexOf(data.id), 1);
        } else {
            const keys = Object.keys(toRaw(selectedFile))
            const length = keys.length
            if (limit == 1 && length == limit) {
                delete selectedFile[keys[0]]
                selectedFileIndex.splice(selectedFileIndex.indexOf(keys[0]), 1);
            } else if (limit && length >= limit) {
                ElMessage.info('只能选择' + limit + '个文件')
                return
            }
            selectedFile[data.id] = toRaw(data)
            selectedFileIndex.push(data.id);
        }
    }

    // 获取当前选中文件的下标
    const getFileIndex = (id: any) => {
        let index = selectedFileIndex.indexOf(id);
        if (index == -1) return 0;
        return index + 1;
    }

    // 是否全部选中
    const isSelectAll = ref(false)
    watch(isSelectAll, () => {
        if (isSelectAll.value) {
            const keys = Object.keys(toRaw(selectedFile))
            tableData.data.forEach((item: Record<string, any>) => {
                if (!keys.some(key => key == item.id)) {
                    selectedFile[item.id] = toRaw(item)
                    selectedFileIndex.push(item.id)
                } else {
                    console.log('已存在')
                }
            })
        } else {
            clearSelected()
        }
    })
    //清空选中文件
    const clearSelected = () => {
        const keys = Object.keys(toRaw(selectedFile))
        if (keys.length) {
            keys.forEach((key) => {
                delete selectedFile[key]
                selectedFileIndex.splice(selectedFileIndex.indexOf(key), 1)
            })
            isSelectAll.value = false
        }

    }

    return {
        tableData,
        reloadAttachmentFileList,
        handleGetAttachmentFilePages,
        handleRenameAttachmentFile,
        handleDeleteAttachmentFile,
        batchDeleteAttachmentFile,
        batchMoveAttachmentFile,

        //文件选择
        selectedFile,
        selectedFileIndex,
        selectFile,
        isSelectAll,
        getFileIndex,
    }
}



