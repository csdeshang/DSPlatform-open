<template>
    <el-dialog v-model="dialogVisible" :title="popTitle" width="560px" :destroy-on-close="true">

        <div v-loading="loading" class="px-10">
            <template v-if="!canAudit && userInfo.id">
                <el-alert type="warning" :title="auditTip" show-icon />
            </template>
            <template v-else-if="userInfo.id">
                <el-form label-width="100px">
                    <el-form-item label="会员名" prop="username">
                        <el-text>{{ userInfo.username }}</el-text>
                    </el-form-item>

                    <el-form-item label="真实姓名">
                        <el-text>{{ userInfo.idcard_name || '—' }}</el-text>
                    </el-form-item>

                    <el-form-item label="身份证号">
                        <el-text>{{ userInfo.idcard_number || '—' }}</el-text>
                    </el-form-item>

                    <el-form-item label="证件照片">
                        <div class="idcard-images">
                            <el-image v-if="userInfo.idcard_image1" :src="formatImageUrl(userInfo.idcard_image1)"
                                :preview-src-list="[formatImageUrl(userInfo.idcard_image1)]" fit="cover" class="idcard-img" />
                            <el-image v-if="userInfo.idcard_image2" :src="formatImageUrl(userInfo.idcard_image2)"
                                :preview-src-list="[formatImageUrl(userInfo.idcard_image2)]" fit="cover" class="idcard-img" />
                            <el-image v-if="userInfo.idcard_image3" :src="formatImageUrl(userInfo.idcard_image3)"
                                :preview-src-list="[formatImageUrl(userInfo.idcard_image3)]" fit="cover" class="idcard-img" />
                        </div>
                        <div class="form-text hint">手持身份证、正面、反面（点击可预览）</div>
                    </el-form-item>
                </el-form>
            </template>
        </div>

        <template #footer>
            <span class="dialog-footer">
                <el-button @click="dialogVisible = false">取消</el-button>
                <el-button v-if="canAudit" type="success" :loading="loading" @click="handleAudit(3)">通过</el-button>
                <el-button v-if="canAudit" type="danger" :loading="loading" @click="handleAudit(2)">拒绝</el-button>
            </span>
        </template>
    </el-dialog>
</template>


<script lang="ts" setup>
import { computed, reactive, ref } from 'vue';
import { formatImageUrl } from '@/utils/image'
import { getUserInfo, auditUserIdcard } from '@/pages-admin/main/api/user/user'

const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''

const userInfo = reactive({})

const canAudit = computed(() => userInfo.idcard_status === 1)
const auditTip = computed(() => {
    const status = userInfo.idcard_status;
    if (status === 0) return '该用户未提交实名认证';
    if (status === 2) return '该用户实名认证已拒绝';
    if (status === 3) return '该用户实名认证已通过';
    return '当前状态不可审核';
})

const emit = defineEmits(['complete'])


const handleAudit = (status: 2 | 3) => {
    if (loading.value || !userInfo.id) return;
    loading.value = true
    auditUserIdcard(userInfo.id, { idcard_status: status }).then(res => {
        loading.value = false
        dialogVisible.value = false
        emit('complete')
    }).catch(() => {
        loading.value = false
    })
}


const setDialogData = async (row: any = null) => {
    loading.value = true
    Object.assign(userInfo, {})
    popTitle = '实名认证审核'

    if (row) {
        const data = await (await getUserInfo(row.id)).data
        Object.assign(userInfo, data)
    }
    loading.value = false
}

defineExpose({
    openDialog: () => {
        dialogVisible.value = true
    },
    setDialogData
})




</script>

<style scoped>
.idcard-images {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.idcard-images .idcard-img {
    width: 120px;
    height: 80px;
    border-radius: 4px;
}

.hint {
    margin-top: 4px;
    color: var(--el-text-color-secondary);
    font-size: 12px;
}
</style>
