import request, { API_BASE_URLS } from '@/utils/request';


// 获取商品评论列表
export function getTblGoodsCommentPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-goods/comments/pages`, { params })
}

// 切换商品评论字段状态
export function toggleTblGoodsCommentField(params: Record<string, any>) {
    return request.patch(`${API_BASE_URLS.ADMIN}/tbl-goods/comments/${params.id}/toggle-field`, params)
} 