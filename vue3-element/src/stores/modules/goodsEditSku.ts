import { defineStore } from 'pinia'
import { ref, reactive, computed, nextTick } from 'vue'
import { cloneDeep } from 'lodash-es'
import { ElMessage } from 'element-plus'

// 规格值接口
export interface SpecValue {
  id: string
  spec_value_name: string
}

// 规格项接口
export interface SpecFormat {
  id: string
  spec_id?: string | number
  goods_id?: string | number
  spec_name: string
  values: SpecValue[]
}

// SKU项接口
export interface SkuItem {
  id?: string | number
  spec_name: string
  sku_spec: SpecValue[]
  sku_image: string
  sku_price: string
  market_price: string
  cost_price: string
  sku_stock: string
  sku_code: string
  is_default: number
}

// 规格表格项接口
export interface SpecTableItem {
  index: number
  colSpan: number
  rowSpan: number
  spec_value_name: string
}

// 商品参数接口
export interface GoodsParameter {
  name: string
  value: string
  sort: number
}

export const useGoodsEditSkuStore = defineStore('goodsEditSku', () => {
  // 加载状态
  const loading = ref(false)

  // 规格相关数据
  const goodsSpecFormat = reactive<SpecFormat[]>([])
  const goodsSkuItems = reactive<Record<string, SkuItem>>({})
  const goodsSpecItems = reactive<SpecTableItem[]>([])
  const goodsSkuStockSum = ref(0)

  // 批量设置SKU数据
  const batchSkuData = reactive({
    sku_image: '',
    sku_price: '',
    market_price: '',
    cost_price: '',
    sku_stock: '',
    sku_code: '',
  })

  // 规格选中状态
  const skuCheckboxGroup = reactive({
    isSelectAll: false,// 是否全选
    isPartiallySelected: false,// 是否部分选中
    selectedSkuIds: [] as string[],// 选中的规格
  })

  // 生成随机ID
  const generateRandom = (len: number = 5) => {
    return Number(Math.random().toString().substr(3, len) + Date.now()).toString(36)
  }

  // 清空所有规格数据的方法
  const clearAllSpecData = () => {
    // 清空规格格式数据
    goodsSpecFormat.splice(0, goodsSpecFormat.length)
    // 清空SKU项目数据
    Object.keys(goodsSkuItems).forEach(key => delete goodsSkuItems[key])
    // 清空规格表格数据
    goodsSpecItems.splice(0, goodsSpecItems.length)
    // 重置库存总和
    goodsSkuStockSum.value = 0
    // 重置批量设置数据
    Object.keys(batchSkuData).forEach(key => {
      batchSkuData[key as keyof typeof batchSkuData] = ''
    })
    // 重置选中状态
    skuCheckboxGroup.isSelectAll = false
    skuCheckboxGroup.isPartiallySelected = false
    skuCheckboxGroup.selectedSkuIds = []
  }

  // 初始化商品编辑信息(主要用于处理商品多规格，因其他平台数据可能不一致，单规格数据基本一致)
  const initGoodsEditInfo = (goodsData: any) => {
    try {
      // 先清空所有现有数据
      clearAllSpecData()

      // 商品为多规格
      if (goodsData.spec_type === 'multiple') {

        // 多规格
        const specList = goodsData.goodsSpecList
        specList.forEach((item: any) => {
          const values: any = []
          item.spec_value = item.spec_value.split(',')
          item.spec_value.forEach((v: any) => {
            values.push({
              id: generateRandom(),
              spec_value_name: v
            })
          })
          goodsSpecFormat.push({
            id: generateRandom(),
            spec_id: item.id,
            goods_id: item.goods_id,
            spec_name: item.spec_name,
            values
          })
        })

        // 刷新规格组合
        refreshGoodsSkuItems()

        const skuList = goodsData.goodsSkuList

        for (const key in goodsSkuItems) {
          for (let i = 0; i < skuList.length; i++) {
            const item = skuList[i];
            if (goodsSkuItems[key].spec_name == item.sku_spec_format.replace(/,/g, ' ')) {
              goodsSkuItems[key].id = item.id;
              goodsSkuItems[key].sku_image = item.sku_image;
              goodsSkuItems[key].sku_code = item.sku_code;
              goodsSkuItems[key].sku_price = item.sku_price;
              goodsSkuItems[key].sku_stock = item.sku_stock;
              goodsSkuItems[key].market_price = item.market_price;
              goodsSkuItems[key].cost_price = item.cost_price;
              goodsSkuItems[key].is_default = item.is_default;
              break;
            }
          }
        }

        // 刷新规格表格
        nextTick(() => {
          refreshSkuTable()
        })
      }
    } catch (error) {
      console.error('初始化规格数据失败:', error)
      ElMessage.error('初始化规格数据失败，请刷新页面重试')
    }
  }

  // 刷新商品规格数据(生成规格组合)
  const refreshGoodsSkuItems = () => {
    // 过滤有效的规格项
    const validSpecs = goodsSpecFormat.filter(spec =>
      spec.spec_name &&
      spec.values.length > 0 &&
      spec.values.some(v => v.spec_value_name && v.spec_value_name.trim() !== '')
    )

    // 如果没有有效规格项，清空SKU数据
    if (validSpecs.length === 0) {
      Object.keys(goodsSkuItems).forEach(key => delete goodsSkuItems[key])
      return
    }

    // 保存现有SKU数据(用于后续合并)
    const tempGoodsSkuItems = cloneDeep(goodsSkuItems)

    // 清空现有SKU数据
    Object.keys(goodsSkuItems).forEach(key => delete goodsSkuItems[key])

    // 生成所有规格组合
    const combinations = generateSkuCombinations(validSpecs)

    // 创建规格组合SKU数据
    combinations.forEach((combo, index) => {
      const key = `sku_${index}`
      const specName = combo.map(item => item.spec_value_name).join(' ')

      // 查找现有匹配的SKU
      const existingKey = Object.keys(tempGoodsSkuItems).find(k => {
        const existingNames = tempGoodsSkuItems[k].spec_name.split(' ').filter(Boolean).sort()
        const newNames = specName.split(' ').filter(Boolean).sort()

        // 检查规格名称集合是否相等
        return existingNames.length === newNames.length &&
          existingNames.every((name, i) => name === newNames[i])
      })

      // 创建SKU项
      goodsSkuItems[key] = {
        spec_name: specName,
        sku_spec: combo,
        sku_image: '',
        sku_price: '',
        market_price: '',
        cost_price: '',
        sku_stock: '',
        sku_code: '',
        is_default: 0
      }

      // 合并现有SKU数据
      if (existingKey) {
        const existingData = tempGoodsSkuItems[existingKey]
        Object.assign(goodsSkuItems[key], {
          id: existingData.id,
          sku_image: existingData.sku_image,
          sku_price: existingData.sku_price,
          market_price: existingData.market_price,
          cost_price: existingData.cost_price,
          sku_stock: existingData.sku_stock,
          sku_code: existingData.sku_code,
          is_default: existingData.is_default
        })
      }
    })

    // 确保有一个默认规格
    const keys = Object.keys(goodsSkuItems)
    if (keys.length > 0) {
      const hasDefault = keys.some(key => goodsSkuItems[key].is_default === 1)
      if (!hasDefault) {
        goodsSkuItems[keys[0]].is_default = 1
      }
    }
  }

  // 生成规格组合
  const generateSkuCombinations = (specs: SpecFormat[]): SpecValue[][] => {
    const result: SpecValue[][] = []

    // 递归生成所有组合
    const generateCombos = (index: number, current: SpecValue[]) => {
      if (index >= specs.length) {
        result.push([...current])
        return
      }

      const spec = specs[index]
      const validValues = spec.values.filter(v => v.spec_value_name && v.spec_value_name.trim() !== '')

      for (const value of validValues) {
        current.push(value)
        generateCombos(index + 1, current)
        current.pop()
      }
    }

    generateCombos(0, [])
    return result
  }

  // 刷新规格表格(计算单元格合并)
  const refreshSkuTable = () => {
    // 计算有效规格项数量
    const validSpecs = goodsSpecFormat.filter(spec =>
      spec.spec_name &&
      spec.values.some(v => v.spec_value_name && v.spec_value_name.trim() !== '')
    )

    const validSpecsCount = validSpecs.length

    // 如果没有有效规格，清空规格表格数据
    if (validSpecsCount === 0) {
      goodsSpecItems.splice(0, goodsSpecItems.length)
      return
    }

    let row = 1 // 跨行数
    const tempSpecItems: SpecTableItem[] = []

    // 从后向前计算单元格合并
    for (let i = validSpecsCount - 1; i >= 0; i--) {
      const spec = validSpecs[i]
      if (!spec) continue

      const validValues = spec.values.filter(v => v.spec_value_name && v.spec_value_name.trim() !== '')

      // 为每个规格值创建单元格
      for (let k = 0; k < Object.keys(goodsSkuItems).length;) {
        for (const value of validValues) {
          tempSpecItems.push({
            index: k,
            colSpan: i,
            rowSpan: row,
            spec_value_name: value.spec_value_name
          })
          k += row
        }
      }

      // 计算下一个规格的跨行数
      row *= validValues.length || 1
    }

    // 反转顺序以匹配表格渲染
    tempSpecItems.reverse()

    // 更新规格表格数据
    goodsSpecItems.splice(0, goodsSpecItems.length, ...tempSpecItems)
  }

  // 更新总库存
  const updateSkuStockSum = () => {
    let stockSum = 0

    // 计算所有SKU库存总和
    for (const key in goodsSkuItems) {
      const stock = parseInt(goodsSkuItems[key].sku_stock) || 0
      stockSum += stock
    }

    // 更新总库存（在组件中使用）
    goodsSkuStockSum.value = stockSum
  }

  // 添加规格项
  const addSpecItem = () => {
    // 创建新规格项
    goodsSpecFormat.push({
      id: generateRandom(),
      spec_name: '',
      values: [{
        id: generateRandom(),
        spec_value_name: ''
      }]
    })

    // 刷新规格组合
    nextTick(() => {
      refreshGoodsSkuItems()
      refreshSkuTable()
      updateSkuStockSum()

    })
  }

  // 删除规格项
  const deleteSpecItem = (index: number) => {
    // 确保不删除最后一个规格项
    if (goodsSpecFormat.length <= 1) {
      ElMessage.warning('至少保留一个规格项')
      return
    }

    goodsSpecFormat.splice(index, 1)

    // 刷新规格组合和表格
    nextTick(() => {
      refreshGoodsSkuItems()
      refreshSkuTable()
      updateSkuStockSum()
    })
  }

  // 添加规格值
  const addSpecValue = (specIndex: number) => {
    goodsSpecFormat[specIndex].values.push({
      id: generateRandom(),
      spec_value_name: ''
    })

    // 添加规格值后自动触发更新
    nextTick(() => {
      refreshGoodsSkuItems()
      refreshSkuTable()
      updateSkuStockSum()
    })
  }

  // 删除规格值
  const deleteSpecValue = (specIndex: number, valueIndex: number) => {
    // 确保不删除最后一个规格值
    if (goodsSpecFormat[specIndex].values.length <= 1) {
      ElMessage.warning('每个规格至少保留一个规格值')
      return
    }

    goodsSpecFormat[specIndex].values.splice(valueIndex, 1)

    // 刷新规格组合和表格
    nextTick(() => {
      refreshGoodsSkuItems()
      refreshSkuTable()
      updateSkuStockSum()
    })
  }

  // 设置默认Sku
  const setDefaultSku = (value: number, key: string) => {
    for (const k in goodsSkuItems) {
      goodsSkuItems[k].is_default = k === key ? value : 0
    }
  }

  // 处理全选
  const handleSkuSelectAllChange = (value: boolean) => {
    skuCheckboxGroup.isPartiallySelected = false

    if (value) {
      skuCheckboxGroup.selectedSkuIds = Object.keys(goodsSkuItems)
    } else {
      skuCheckboxGroup.selectedSkuIds = []
    }
  }

  // 处理选择变化
  const handleSkuSelectionChange = () => {
    const totalCount = Object.keys(goodsSkuItems).length
    const selectedCount = skuCheckboxGroup.selectedSkuIds.length

    skuCheckboxGroup.isSelectAll = selectedCount === totalCount
    skuCheckboxGroup.isPartiallySelected = selectedCount > 0 && selectedCount < totalCount
  }

  // 批量设置SKU
  const batchSetSku = () => {
    if (skuCheckboxGroup.selectedSkuIds.length === 0) {
      ElMessage.warning('请选择要修改的规格')
      return
    }

    // 遍历选中的SKU进行批量更新
    skuCheckboxGroup.selectedSkuIds.forEach(id => {
      if (goodsSkuItems[id]) {
        // 只更新有值的字段
        for (const key in batchSkuData) {
          const value = batchSkuData[key as keyof typeof batchSkuData]
          if (value !== '' && value !== null && value !== undefined) {
            // 使用类型断言解决类型问题
            ; (goodsSkuItems[id] as any)[key] = value
          }
        }
      }
    })

    // 清空批量设置数据
    Object.keys(batchSkuData).forEach(key => {
      batchSkuData[key as keyof typeof batchSkuData] = ''
    })

    ElMessage.success('批量更新成功')
  }

  // 监听规格数据变化
  const handleSpecDataChanged = () => {
    nextTick(() => {
      refreshGoodsSkuItems()
      refreshSkuTable()
      updateSkuStockSum()
    })
  }



  // 验证规格数据
  const validateSpecData = () => {
    // 验证规格项
    if (goodsSpecFormat.length === 0) {
      ElMessage.warning('请添加规格项')
      return false
    }

    // 验证规格名称
    for (let i = 0; i < goodsSpecFormat.length; i++) {
      const spec = goodsSpecFormat[i]
      if (!spec.spec_name) {
        ElMessage.warning(`第${i + 1}个规格项名称不能为空`)
        return false
      }

      // 验证规格值
      const validValues = spec.values.filter(v => v.spec_value_name)
      if (validValues.length === 0) {
        ElMessage.warning(`规格"${spec.spec_name}"至少需要一个规格值`)
        return false
      }
    }

    // 验证SKU数据
    for (const key in goodsSkuItems) {
      const item = goodsSkuItems[key]
      if (!item.sku_price) {
        const specName = item.spec_name || '规格组合'
        ElMessage.warning(`${specName}的价格不能为空`)
        return false
      }

      if (!item.sku_stock) {
        const specName = item.spec_name || '规格组合'
        ElMessage.warning(`${specName}的库存不能为空`)
        return false
      }
    }

    return true
  }

  return {
    // 状态
    loading,
    // 规格
    goodsSpecFormat,
    // SKU
    goodsSkuItems,
    // 规格表格
    goodsSpecItems,
    // 批量设置
    batchSkuData,
    // 规格选中
    skuCheckboxGroup,
    // 库存
    goodsSkuStockSum,

    // 方法
    generateRandom,
    // 初始化商品编辑信息
    initGoodsEditInfo,
    // 刷新规格组合
    refreshGoodsSkuItems,
    // 刷新规格表格
    refreshSkuTable,
    // 更新总库存
    updateSkuStockSum,
    // 添加规格项
    addSpecItem,
    // 删除规格项
    deleteSpecItem,
    // 添加规格值
    addSpecValue,
    // 删除规格值
    deleteSpecValue,
    // 设置默认Sku
    setDefaultSku,
    // 规格选中
    handleSkuSelectAllChange,
    // 规格选中变化
    handleSkuSelectionChange,
    // 批量设置
    batchSetSku,
    // 监听规格数据变化
    handleSpecDataChanged,
    // 验证规格数据
    validateSpecData
  }
}) 