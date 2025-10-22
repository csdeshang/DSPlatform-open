import { reactive, toRaw } from "vue"

interface Options {
    page_current?: number
    page_size?: number
    requestFun: (_arg: any) => Promise<any>
    searchParams?: Record<any, any>
}

export function usePagination(options: Options) {

    const {
        page_current = 1,
        page_size = 10,
        requestFun,
        searchParams = {},
    } = options


    const tableData = reactive({
        page_current: page_current,
        page_size: page_size,
        total: 0,
        loading: true,
        data: [],
    })
    
    const searchParamsInit: Record<any, any> = Object.assign({}, toRaw(searchParams))

    // 请求分页接口
    const getTableList = () => {
        tableData.loading = true
        return requestFun({
            page_current: tableData.page_current,
            page_size: tableData.page_size,
            ...searchParams,
        }).then((res: any) => {
                tableData.loading = false
                tableData.data = res.data.data
                tableData.total = res.data.total
            })
            .catch((error: any) => {
                console.error(error)
            })
            .finally(() => {
                tableData.loading = false
            })
    }
    // 重置页面
    const resetPage = () => {
        tableData.page_current = 1
        getTableList()
    }
    // 重置查询参数
    const resetSearchParams = () => {
        Object.keys(searchParamsInit).forEach((item) => {
            searchParams[item] = searchParamsInit[item]
        })
        getTableList()
    }
    return {
        tableData,
        getTableList,
        resetSearchParams,
        resetPage
    }


}