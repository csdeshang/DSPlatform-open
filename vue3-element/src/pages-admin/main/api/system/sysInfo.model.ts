/**
 * 系统环境信息（对齐 SysInfo::getEnvironmentInfo）
 */
export interface SystemEnvironment {
  php_version?: string
  server_software?: string
  server_os?: string
  framework_version?: string
  database_version?: string
  domain?: string
  install_time?: string
  server_time?: string
  timezone?: string
  zlib?: string
  gd?: string
  curl?: string
  file_uploads?: string
  upload_max_filesize?: string
  max_execution_time?: string | number
  memory_limit?: string
}

/**
 * 可升级版本项
 */
export interface SystemUpgradeVersion {
  version?: string
  date?: string
  download_url?: string
}

/**
 * 版本更新信息
 */
export interface SystemUpdateInfo {
  update_available?: boolean
  message?: string
  upgrade_versions?: SystemUpgradeVersion[]
}

/**
 * 系统版本信息
 */
export interface SystemVersion {
  current_version?: string
  update_info?: SystemUpdateInfo
}

/**
 * getSystemInfo 返回数据
 */
export interface SystemInfo {
  environment?: SystemEnvironment
  version?: SystemVersion
}
