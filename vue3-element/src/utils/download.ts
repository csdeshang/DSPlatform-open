/**
 * 浏览器下载 Blob 文件。
 * 导出接口在业务失败时可能仍返回 JSON（responseType=blob），此处会识别并抛错。
 */
export async function downloadBlob(data: Blob, filename: string): Promise<void> {
  const blob = data instanceof Blob ? data : new Blob([data as any])
  const buffer = await blob.arrayBuffer()
  const head = new TextDecoder().decode(buffer.slice(0, Math.min(buffer.byteLength, 256))).trimStart()

  if (head.startsWith('{')) {
    try {
      const json = JSON.parse(new TextDecoder().decode(buffer))
      if (json && typeof json.code !== 'undefined' && Number(json.code) !== 10000) {
        throw new Error(json.message || json.msg || '导出失败')
      }
    } catch (e) {
      if (e instanceof Error && !(e instanceof SyntaxError)) {
        throw e
      }
    }
  }

  const fileBlob = new Blob([buffer], {
    type: blob.type || 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
  })
  const url = URL.createObjectURL(fileBlob)
  const link = document.createElement('a')
  link.href = url
  link.download = filename
  link.style.display = 'none'
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
  URL.revokeObjectURL(url)
}
