<template>
    <el-dialog v-model="dialogVisible" :title="formData.id ? '编辑配送费规则' : '新增配送费规则'" width="800px" destroy-on-close>
        <el-form ref="formRef" :model="formData" :rules="rules" label-width="120px" class="mt-[20px]">
            <el-form-item label="规则名称" prop="rule_name">
                <el-input v-model="formData.rule_name" placeholder="请输入规则名称" />
            </el-form-item>

            <el-form-item label="基础配送费" prop="base_fee">
                <el-input v-model="formData.base_fee" type="number" placeholder="请输入基础配送费" class="w-[180px]">
                    <template #append>元</template>
                </el-input>
                <span class="ml-2 text-gray-400 text-sm">(所有订单的固定基础费用)</span>
            </el-form-item>

            <el-form-item label="距离费用计算" prop="distance_fee_type">
                <el-radio-group v-model="formData.distance_fee_type">
                    <el-radio :label="1">阶梯式</el-radio>
                    <el-radio :label="2">连续式</el-radio>
                </el-radio-group>
                <div class="text-gray-400 text-sm mt-1 w-full">
                    <p v-if="formData.distance_fee_type === 1">阶梯式：根据配送距离选择对应的费用(如0-3公里3元，3-5公里5元)</p>
                    <p v-else>连续式：超出起始公里数后，每增加1公里增加对应费用</p>
                </div>
            </el-form-item>

            <el-form-item label="距离规则" prop="distance_rules">
                <div class="w-full">
                    <div v-if="formData.distance_fee_type === 1">
                        <div v-for="(item, index) in distanceRules.step" :key="index" class="flex gap-2 mb-2">
                            <el-input v-model="item.min" type="number" placeholder="最小距离" class="w-[150px]">
                                <template #append>公里</template>
                            </el-input>
                            至
                            <el-input v-model="item.max" type="number" placeholder="最大距离" class="w-[150px]">
                                <template #append>公里</template>
                            </el-input>
                            <el-input v-model="item.fee" type="number" placeholder="费用" class="w-[150px]">
                                <template #append>元</template>
                            </el-input>
                            <el-button type="danger" @click="removeDistanceStepRule(index)"
                                v-if="distanceRules.step.length > 1">删除</el-button>
                        </div>
                    </div>
                    <div v-else>
                        <div class="flex gap-2 mb-2">
                            <el-input v-model="distanceRules.continuous.start_value" type="number" placeholder="起始公里数" class="w-[150px]">
                                <template #append>公里</template>
                            </el-input>
                            <span class="line-height-[32px]">内不额外收费，超出后每公里加</span>
                            <el-input v-model="distanceRules.continuous.per_unit_fee" type="number" placeholder="每公里费用" class="w-[150px]">
                                <template #append>元</template>
                            </el-input>
                        </div>
                    </div>
                    <el-button v-if="formData.distance_fee_type === 1" type="primary" @click="addDistanceStepRule">添加距离规则</el-button>
                </div>
            </el-form-item>

            <el-form-item label="重量费用计算" prop="weight_fee_type">
                <el-radio-group v-model="formData.weight_fee_type">
                    <el-radio :label="0">不计算</el-radio>
                    <el-radio :label="1">阶梯式</el-radio>
                    <el-radio :label="2">连续式</el-radio>
                </el-radio-group>
                <div v-if="formData.weight_fee_type !== 0" class="text-gray-400 text-sm mt-1 w-full">
                    <p v-if="formData.weight_fee_type === 1">阶梯式：根据订单重量选择对应的费用(如0-5公斤0元，5-10公斤5元)</p>
                    <p v-else>连续式：超出起始重量后，每增加1公斤增加对应费用</p>
                </div>
            </el-form-item>

            <el-form-item label="重量规则" prop="weight_rules" v-if="formData.weight_fee_type !== 0">
                <div class="w-full">
                    <div v-if="formData.weight_fee_type === 1">
                        <div v-for="(item, index) in weightRules.step" :key="index" class="flex gap-2 mb-2">
                            <el-input v-model="item.min" type="number" placeholder="最小重量" class="w-[150px]">
                                <template #append>公斤</template>
                            </el-input>
                            <el-input v-model="item.max" type="number" placeholder="最大重量" class="w-[150px]">
                                <template #append>公斤</template>
                            </el-input>
                            <el-input v-model="item.fee" type="number" placeholder="费用" class="w-[150px]">
                                <template #append>元</template>
                            </el-input>
                            <el-button type="danger" @click="removeWeightStepRule(index)"
                                v-if="weightRules.step.length > 1">删除</el-button>
                        </div>
                    </div>
                    <div v-else>
                        <div class="flex gap-2 mb-2">
                            <el-input v-model="weightRules.continuous.start_value" type="number" placeholder="起始重量" class="w-[150px]">
                                <template #append>公斤</template>
                            </el-input>
                            <span class="line-height-[32px]">内不额外收费，超出后每公斤加</span>
                            <el-input v-model="weightRules.continuous.per_unit_fee" type="number" placeholder="每公斤费用" class="w-[150px]">
                                <template #append>元</template>
                            </el-input>
                        </div>
                    </div>
                    <el-button v-if="formData.weight_fee_type === 1" type="primary" @click="addWeightStepRule">添加重量规则</el-button>
                </div>
            </el-form-item>

            <el-form-item label="时段费用计算" prop="time_period_fee_type">
                <el-radio-group v-model="formData.time_period_fee_type">
                    <el-radio :label="0">不计算</el-radio>
                    <el-radio :label="1">按时段加价</el-radio>
                </el-radio-group>
            </el-form-item>

            <el-form-item label="时段规则" prop="time_period_rules" v-if="formData.time_period_fee_type !== 0">
                <div class="w-full">
                    <div v-for="(item, index) in timePeriodRules" :key="index" class="flex gap-2 mb-2">
                        <el-time-picker v-model="item.start" format="HH:mm" placeholder="开始时间" class="w-[140px]" />
                        <el-time-picker v-model="item.end" format="HH:mm" placeholder="结束时间" class="w-[140px]" />
                        <el-input v-model="item.fee" type="number" placeholder="加价费用" class="w-[120px]">
                            <template #append>元</template>
                        </el-input>
                        <el-button type="danger" @click="removeTimePeriodRule(index)"
                            v-if="timePeriodRules.length > 1">删除</el-button>
                    </div>
                    <el-button type="primary" @click="addTimePeriodRule">添加时段规则</el-button>
                </div>
            </el-form-item>

            <el-form-item label="天气费用计算" prop="weather_fee_type">
                <el-radio-group v-model="formData.weather_fee_type">
                    <el-radio :label="0">不计算</el-radio>
                    <el-radio :label="1">恶劣天气加价</el-radio>
                </el-radio-group>
            </el-form-item>

            <el-form-item label="天气规则" prop="weather_rules" v-if="formData.weather_fee_type !== 0">
                <div class="w-full">
                    <div v-for="(item, index) in weatherRules" :key="index" class="flex gap-2 mb-2">
                        <el-select v-model="item.type" placeholder="天气类型" class="w-[150px]">
                            <el-option label="雨天" value="rain" />
                            <el-option label="雪天" value="snow" />
                            <el-option label="高温" value="hot" />
                            <el-option label="低温" value="cold" />
                        </el-select>
                        <el-input v-model="item.fee" type="number" placeholder="加价费用" class="w-[120px]">
                            <template #append>元</template>
                        </el-input>
                        <el-button type="danger" @click="removeWeatherRule(index)"
                            v-if="weatherRules.length > 1">删除</el-button>
                    </div>
                    <el-button type="primary" @click="addWeatherRule">添加天气规则</el-button>
                </div>
            </el-form-item>

            <el-form-item label="平台抽成比例" prop="rider_fee_rate">
                <el-input v-model="formData.rider_fee_rate" type="number" class="w-[200px]">
                    <template #append>%</template>
                </el-input>
            </el-form-item>

            <el-form-item label="适用区域" prop="area_id">
                <el-select v-model="formData.area_id" placeholder="请选择适用区域">
                    <el-option label="全部区域" :value="0" />
                    <!-- 这里可以根据实际情况添加更多区域选项 -->
                </el-select>
            </el-form-item>

            <el-form-item label="是否启用" prop="is_enabled">
                <el-switch v-model="formData.is_enabled" :active-value="1" :inactive-value="0" />
            </el-form-item>
        </el-form>

        <template #footer>
            <div class="flex justify-end">
                <el-button @click="dialogVisible = false">取消</el-button>
                <el-button type="primary" @click="submitForm" :loading="submitting">确定</el-button>
            </div>
        </template>
    </el-dialog>
</template>


<script lang="ts" setup>
import { ref, reactive } from 'vue';
import type { FormInstance } from 'element-plus';
import { ElMessage } from 'element-plus';
import { addRiderFeeRule, updateRiderFeeRule } from '@/pages-admin/main/api/rider/feeRule';

const emit = defineEmits(['refresh']);

const dialogVisible = ref(false);
const formRef = ref<FormInstance>();
const submitting = ref(false);

// 表单数据
const formData = reactive({
    id: undefined as number | undefined,
    rule_name: '',
    base_fee: 0,
    distance_fee_type: 1,
    weight_fee_type: 0,
    time_period_fee_type: 0,
    weather_fee_type: 0,
    rider_fee_rate: 0,
    area_id: 0,
    is_enabled: 1
});

// 规则类型接口
interface StepRule {
    min: number;
    max: number;
    fee: number;
}

interface ContinuousRule {
    start_value: number;
    per_unit_fee: number;
}

interface TimePeriodRule {
    start: string;
    end: string;
    fee: number;
}

interface WeatherRule {
    type: string;
    fee: number;
}

// 距离规则
const distanceRules = reactive({
    step: [{ min: 0, max: 3, fee: 0 }] as StepRule[],
    continuous: {
        start_value: 3,
        per_unit_fee: 1
    } as ContinuousRule
});

// 重量规则
const weightRules = reactive({
    step: [{ min: 0, max: 5, fee: 0 }] as StepRule[],
    continuous: {
        start_value: 5,
        per_unit_fee: 1
    } as ContinuousRule
});

// 时段规则
const timePeriodRules = ref<TimePeriodRule[]>([{ start: '', end: '', fee: 0 }]);

// 天气规则
const weatherRules = ref<WeatherRule[]>([{ type: 'rain', fee: 0 }]);

// 验证规则
const rules = {
    rule_name: [{ required: true, message: '请输入规则名称', trigger: 'blur' }],
    base_fee: [{ required: true, message: '请设置基础配送费', trigger: 'blur' }]
};

// 添加距离阶梯规则
const addDistanceStepRule = () => {
    const lastRule = distanceRules.step[distanceRules.step.length - 1];
    distanceRules.step.push({
        min: lastRule.max,
        max: lastRule.max + 2,
        fee: 0
    });
};

// 删除距离阶梯规则
const removeDistanceStepRule = (index: number) => {
    distanceRules.step.splice(index, 1);
};

// 添加重量阶梯规则
const addWeightStepRule = () => {
    const lastRule = weightRules.step[weightRules.step.length - 1];
    weightRules.step.push({
        min: lastRule.max,
        max: lastRule.max + 5,
        fee: 0
    });
};

// 删除重量阶梯规则
const removeWeightStepRule = (index: number) => {
    weightRules.step.splice(index, 1);
};

// 添加时段规则
const addTimePeriodRule = () => {
    timePeriodRules.value.push({
        start: '',
        end: '',
        fee: 0
    });
};

// 删除时段规则
const removeTimePeriodRule = (index: number) => {
    timePeriodRules.value.splice(index, 1);
};

// 添加天气规则
const addWeatherRule = () => {
    weatherRules.value.push({
        type: 'rain',
        fee: 0
    });
};

// 删除天气规则
const removeWeatherRule = (index: number) => {
    weatherRules.value.splice(index, 1);
};

// 打开弹窗
interface RuleData {
    id?: number;
    rule_name?: string;
    base_fee?: number;
    distance_fee_type?: number;
    weight_fee_type?: number;
    time_period_fee_type?: number;
    weather_fee_type?: number;
    rider_fee_rate?: number;
    area_id?: number;
    is_enabled?: number;
    distance_rules?: string | any;
    weight_rules?: string | any;
    time_period_rules?: string | any;
    weather_rules?: string | any;
    [key: string]: any;
}

const openDialog = (data?: RuleData) => {
    resetForm();
    
    if (data) {
        // 编辑模式
        Object.keys(formData).forEach(key => {
            if (data[key] !== undefined) {
                (formData as any)[key] = data[key];
            }
        });
        
        // 处理距离规则数据
        if (data.distance_rules) {
            try {
                // 检查是否为字符串格式（兼容旧数据）
                const distanceData = typeof data.distance_rules === 'string' ? 
                    JSON.parse(data.distance_rules) : data.distance_rules;
                
                // 直接获取数据
                if (distanceData.step && distanceData.continuous) {
                    distanceRules.step = distanceData.step;
                    distanceRules.continuous = distanceData.continuous;
                }
            } catch (e) {
                console.error('解析distance_rules失败', e);
            }
        }
        
        // 处理重量规则数据
        if (data.weight_rules && formData.weight_fee_type !== 0) {
            try {
                // 检查是否为字符串格式（兼容旧数据）
                const weightData = typeof data.weight_rules === 'string' ? 
                    JSON.parse(data.weight_rules) : data.weight_rules;
                
                // 直接获取数据
                if (weightData.step && weightData.continuous) {
                    weightRules.step = weightData.step;
                    weightRules.continuous = weightData.continuous;
                }
            } catch (e) {
                console.error('解析weight_rules失败', e);
            }
        }
        
        // 处理时段规则数据
        if (data.time_period_rules && formData.time_period_fee_type !== 0) {
            try {
                // 检查是否为字符串格式（兼容旧数据）
                timePeriodRules.value = typeof data.time_period_rules === 'string' ? 
                    JSON.parse(data.time_period_rules) : data.time_period_rules;
            } catch (e) {
                console.error('解析time_period_rules失败', e);
            }
        }
        
        // 处理天气规则数据
        if (data.weather_rules && formData.weather_fee_type !== 0) {
            try {
                // 检查是否为字符串格式（兼容旧数据）
                weatherRules.value = typeof data.weather_rules === 'string' ? 
                    JSON.parse(data.weather_rules) : data.weather_rules;
            } catch (e) {
                console.error('解析weather_rules失败', e);
            }
        }
    }
    
    dialogVisible.value = true;
};

// 重置表单
const resetForm = () => {
    // 重置表单数据
    formData.id = undefined;
    formData.rule_name = '';
    formData.base_fee = 0;
    formData.distance_fee_type = 1;
    formData.weight_fee_type = 0;
    formData.time_period_fee_type = 0;
    formData.weather_fee_type = 0;
    formData.rider_fee_rate = 0;
    formData.area_id = 0;
    formData.is_enabled = 1;
    
    // 重置规则
    distanceRules.step = [{ min: 0, max: 3, fee: 0 }];
    distanceRules.continuous = {
        start_value: 3,
        per_unit_fee: 1
    };
    
    weightRules.step = [{ min: 0, max: 5, fee: 0 }];
    weightRules.continuous = {
        start_value: 5,
        per_unit_fee: 1
    };
    
    timePeriodRules.value = [{ start: '', end: '', fee: 0 }];
    weatherRules.value = [{ type: 'rain', fee: 0 }];
};

// 提交表单
const submitForm = async () => {
    if (!formRef.value) return;
    
    await formRef.value.validate(async (valid, fields) => {
        if (!valid) return;
        
        submitting.value = true;
        
        try {
            // 构建距离规则数据 - 同时保存两种规则数据
            const distanceRulesData = {
                step: distanceRules.step,
                continuous: distanceRules.continuous
            };
            
            // 构建重量规则数据 - 同时保存两种规则数据
            let weightRulesData = null;
            if (formData.weight_fee_type !== 0) {
                weightRulesData = {
                    step: weightRules.step,
                    continuous: weightRules.continuous
                };
            }
            
            // 构建提交数据
            const submitData = {
                ...formData,
                distance_rules: distanceRulesData,
                weight_rules: formData.weight_fee_type !== 0 ? weightRulesData : null,
                time_period_rules: formData.time_period_fee_type !== 0 ? timePeriodRules.value : null,
                weather_rules: formData.weather_fee_type !== 0 ? weatherRules.value : null
            };
            
            if (formData.id) {
                // 更新
                const res = await updateRiderFeeRule(submitData);
                if (res.code === 10000) {
                    ElMessage.success('更新成功');
                }
            } else {
                // 新增
                const res = await addRiderFeeRule(submitData);
                if (res.code === 10000) {
                    ElMessage.success('添加成功');
                }
            }
            
            dialogVisible.value = false;
            emit('refresh');
        } catch (error) {
            console.error(error);
            ElMessage.error(formData.id ? '更新失败' : '添加失败');
        } finally {
            submitting.value = false;
        }
    });
};

defineExpose({
    openDialog
});
</script>