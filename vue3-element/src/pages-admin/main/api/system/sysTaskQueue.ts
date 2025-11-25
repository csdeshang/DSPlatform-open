import request, { API_BASE_URLS } from '@/utils/request'

export function getSysTaskQueuePages(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/system/task-queues/pages`, { params })
}

export function getSysTaskQueueInfo(id: number) {
  return request.get(`${API_BASE_URLS.ADMIN}/system/task-queues/${id}`)
}

export function recoverOrphanedTasks() {
  return request.post(`${API_BASE_URLS.ADMIN}/system/task-queues/recover-orphaned`)
}

export function retryFailedTasks() {
  return request.post(`${API_BASE_URLS.ADMIN}/system/task-queues/retry-failed`)
}