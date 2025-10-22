<template>
    <div class="flex flex-row" style="width: 100%;">
        <div class="flex flex-col border-r" style="width:180px;">
            <div class="min-h-0">
                <el-scrollbar height="600px">
                    <el-tree ref="treeRef" node-key="id" :data="attachment_cate_list" empty-text=""
                        :highlight-current="true" :props="{
                            label: 'name'
                        }" :expand-on-click-node="false" :current-node-key="current_cid"
                        @node-click="handleAttachmentCateSelect">

                        <template v-slot="{ data }">
                            <div class="flex flex-1 items-center pr-[10px]">
                                <Icon icon="element Folder" :size="20" />
                                <el-tooltip placement="top" :content="data.name">
                                    <span class="flex-1">
                                        {{ data.name }}
                                    </span>
                                </el-tooltip>
                                <el-dropdown v-if="data.id > 0" :hide-on-click="false">
                                    <span class="m-r-10">···</span>
                                    <template #dropdown>
                                        <el-dropdown-menu>
                                            <popover-input
                                                @confirm="handleUpdateAttachmentCate($event, data.id)"
                                                size="default" :value="data.name" width="400px"
                                                :limit="20" show-limit teleported>
                                                <div>
                                                    <el-dropdown-item>命名分组</el-dropdown-item>
                                                </div>
                                            </popover-input>
                                            <popover-input
                                                @confirm="handleCreateAttachmentCate($event, data.id)"
                                                size="default" width="400px" :limit="20" show-limit teleported>
                                                <div>
                                                    <el-dropdown-item>添加分组</el-dropdown-item>
                                                </div>
                                            </popover-input>
                                            <div
                                                @click="handleDeleteAttachmentCate(data.id, data?.children?.length)">
                                                <el-dropdown-item>删除分组</el-dropdown-item>
                                            </div>
                                        </el-dropdown-menu>
                                    </template>
                                </el-dropdown>
                            </div>
                        </template>

                    </el-tree>
                </el-scrollbar>
            </div>
            <div class="flex justify-center">
                <popover-input @confirm="handleCreateAttachmentCate" size="default" width="400px" :limit="20" show-limit
                    teleported>
                    <el-button class="w-[100px]"> 添加分组 </el-button>
                </popover-input>
            </div>
        </div>

        <div class="flex flex-1 flex-col pl-[10px]">
            <div class="flex flex-col">

                <div class="flex flex-row">
                    <div> 全选 <el-switch v-model="isSelectAll" /></div>



                    <div class="ml-[10px]">
                        <el-button type="primary" :disabled="!Object.keys(selectedFile).length">使用选中素材</el-button>
                    </div>


                    <div class="ml-[10px]">
                        <upload-btn  :uploadData="{ cid: current_cid }" :type="type"
                            :uploadLimit="20" :show-progress="true" @allSuccess="reloadAttachmentFileList">
                            <el-button type="primary">上传</el-button>
                        </upload-btn>
                    </div>

                    <div class="ml-[10px]">
                        <el-button type="primary" :disabled="!Object.keys(selectedFile).length"
                            @click="batchDeleteAttachmentFile">删除选中素材</el-button>
                    </div>


                    <div class="ml-[10px]" style="width:150px;">
                        <el-tree-select @change="batchMoveAttachmentFile" :disabled="!Object.keys(selectedFile).length"
                            placeholder="选中图片移动至" v-model="tableData.moveCateId" node-key="id"
                            :data="attachment_cate_list" :props="{
                                label: 'name'
                            }" :render-after-expand="false" />
                    </div>

                    <div class="flex ml-auto">
                        <Icon icon="element Plus" :size="18" @click="tableData.show_type = 'grid'" />
                        <Icon icon="element Minus" :size="18" @click="tableData.show_type = 'list'" />
                    </div>
                </div>

                <div class="flex flex-row mt-[10px]">
                    <div class="ml-[10px]">
                        <el-input v-model="tableData.searchParam.name" style="width:150px"
                            placeholder="请输入名称" @input="reloadAttachmentFileList" clearable
                            @clear="reloadAttachmentFileList" />
                    </div>

                </div>


            </div>

            <div class="flex">
                <el-scrollbar v-show="tableData.show_type == 'grid'">
                    <div class="grid grid-cols-5 gap-4 p-4">
                        <div v-for="row in tableData.data" :key="row.id"
                            class="flex flex-col items-center p-2 hover:bg-gray-100 rounded">
                            <div @click="selectFile(row)"
                                class="relative cursor-pointer aspect-square flex items-center justify-center w-[100px] h-[100px]"
                                :class="{
                                    'border-2 border-transparent': !selectedFile[row.id],
                                    'border-2 border-red-500': !!selectedFile[row.id]
                                }">
                                <el-image style="width:100%; height: 100%;object-fit: contain;" :src="formatFileUrl(row.path)" fit="contain" v-if="type == 'image'"
                                    :alt="row.name">
                                </el-image>
                                <video :src="formatFileUrl(row.path)" v-if="type == 'video'" controls/>
                                <div class="absolute top-2 right-2 bg-gray-700/80 px-2 py-1 rounded text-sm text-white"
                                    v-show="selectedFile[row.id]">
                                    {{ getFileIndex(row.id) }}
                                </div>
                            </div>
                            <div class="mt-2 text-center w-full">
                                <div>
                                    {{ row.name }}
                                </div>
                                <div class="flex justify-between">
                                    <popover-input @confirm="handleRenameAttachmentFile($event, row.id)"
                                        size="default" :value="row.name" width="400px" :limit="50" show-limit
                                        teleported>
                                        <el-button type="primary" link> 重命名 </el-button>
                                    </popover-input>
                                    <el-button type="danger" link
                                        @click="handleDeleteAttachmentFile(row.id)">
                                        删除
                                    </el-button>
                                </div>

                            </div>
                        </div>
                    </div>
                </el-scrollbar>


                <el-table :data="tableData.data" size="large" v-loading="tableData.loading" width="100%" height="550"
                    v-show="tableData.show_type == 'list'">


                    <el-table-column label="URL" width="150">
                        <template #default="{ row }">
                            <div @click="selectFile(row)"
                                class="relative cursor-pointer aspect-square flex items-center justify-center w-[100px] h-[100px]"
                                :class="{
                                    'border-2 border-transparent': !selectedFile[row.id],
                                    'border-2 border-red-500': !!selectedFile[row.id]
                                }">
                                <el-image style="width:100%; height: 100%;object-fit: contain;" :src="formatFileUrl(row.path)" fit="contain" v-if="type == 'image'"
                                    :alt="row.name">
                                </el-image>
                                <video :src="formatFileUrl(row.path)" v-if="type == 'video'" controls/>
                                <div class="absolute top-2 right-2 bg-gray-700/80 px-2 py-1 rounded text-sm text-white"
                                    v-show="selectedFile[row.id]">
                                    {{ getFileIndex(row.id) }}
                                </div>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column label="名称" prop="name" />
                    <el-table-column label="大小" width="100" prop="size" />
                    <el-table-column label="存储" width="100" prop="upload_type" />
                    <el-table-column label="上传时间" width="100" prop="create_at" />

                    <el-table-column label="操作" align="right" fixed="right" width="130">
                        <template #default="{ row }">

                            <popover-input @confirm="handleRenameAttachmentFile($event, row.id)"
                                size="default" :value="row.name" width="400px" :limit="50" show-limit
                                teleported>
                                <el-button type="primary" link> 重命名 </el-button>
                            </popover-input>
                            <el-button type="danger" link @click="handleDeleteAttachmentFile(row.id)">
                                删除
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </div>

            <div class="flex justify-end mt-[20px]">
                <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                    layout="total, sizes, prev, pager, next, jumper" :total="tableData.total"
                    @size-change="handleGetAttachmentFilePages()" @current-change="handleGetAttachmentFilePages" />
            </div>


        </div>


    </div>


</template>
<script lang="ts" setup>
import { computed, ref, watch } from 'vue'
import { useAttachmentCate, useAttachmentFile } from './list'

import { formatFileUrl } from '@/utils/util'
import popoverInput from '@/components/popover-input/index.vue'
import Icon from '@/components/icon/index.vue'
import uploadBtn from '@/components/attachment/upload-btn.vue'

const props = defineProps({
    // 选择数量限制
    limit: {
        type: Number,
        default: 1
    },
    type: {
        type: String,
        default: 'image'
    },
    pageSize: {
        type: Number,
        default: 10
    }
})


const {
    treeRef,
    current_cid,
    attachment_cate_list,
    handleGetAttachmentCateList,
    handleCreateAttachmentCate,
    handleUpdateAttachmentCate,
    handleDeleteAttachmentCate,
    handleAttachmentCateSelect
} = useAttachmentCate(props.type)

const {
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
} = useAttachmentFile(current_cid, props.type, props.limit, props.pageSize)




const initData = async () => {
    handleGetAttachmentCateList()
    treeRef.value?.setCurrentKey(current_cid.value)
    handleGetAttachmentFilePages()
}
initData()

watch(current_cid, () => {
   
    reloadAttachmentFileList()
})


defineExpose({
    selectedFile,
    selectedFileIndex
})


</script>
