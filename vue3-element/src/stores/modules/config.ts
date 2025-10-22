import { defineStore } from 'pinia';
import { 
  getSysConfigByKey, 
  getConfigsByType, 
  getConfigsByKeys, 
  getConfigsByTypes 
} from '@/api/system/sysConfig';

import storage from '@/utils/storage';


// 用于读取配置，并缓存到本地存储，避免频繁请求(Vue3与Uniapp通用)


// 本地存储键名
const STORAGE_CONFIG_KEY = 'sys_config';
const STORAGE_TYPE_KEY = 'sys_config_types';

// 配置过期时间 (默认24小时)
const CONFIG_EXPIRE_SECONDS = 24 * 60 * 60; // 转换为秒，与storage默认单位保持一致

// 定义状态类型
interface ConfigState {
  // 单个配置缓存
  configCache: Record<string, string>;
  // 按类型分组的配置缓存
  typeCache: Record<string, Record<string, string>>;
  // 加载状态
  loading: boolean;
}

export const useConfigStore = defineStore('config', {
  // 定义状态
  state: (): ConfigState => {
    // 尝试从本地存储初始化数据
    try {
      // 读取缓存数据（storage.get会自动处理过期逻辑）
      const configData = storage.get(STORAGE_CONFIG_KEY);
      const typeData = storage.get(STORAGE_TYPE_KEY);
      
      if (configData && typeData) {
        return {
          configCache: typeof configData === 'string' ? JSON.parse(configData) : configData,
          typeCache: typeof typeData === 'string' ? JSON.parse(typeData) : typeData,
          loading: false
        };
      }
    } catch (e) {
      console.error('读取本地配置失败', e);
    }
    
    // 默认返回空对象
    return {
      configCache: {},
      typeCache: {},
      loading: false
    };
  },
  
  // 定义 getters
  getters: {
    // 获取指定配置，如果不存在返回null
    getConfigValue: (state) => (key: string): string | null => {
      return state.configCache[key] || null;
    },
    
    // 获取指定类型的所有配置
    getTypeConfigs: (state) => (type: string): Record<string, string> => {
      return state.typeCache[type] || {};
    },
    
    // 检查配置是否已缓存
    hasConfig: (state) => (key: string): boolean => {
      return key in state.configCache;
    },
    
    // 检查类型是否已缓存
    hasTypeCache: (state) => (type: string): boolean => {
      return type in state.typeCache;
    }
  },
  
  // 定义 actions
  actions: {
    // 保存到本地存储
    saveToStorage() {
      try {
        // 保存数据，使用storage内置的过期时间机制
        // 注意：storage.set会自动对对象进行JSON.stringify，所以这里直接传入对象
        storage.set(STORAGE_CONFIG_KEY, this.configCache, CONFIG_EXPIRE_SECONDS);
        storage.set(STORAGE_TYPE_KEY, this.typeCache, CONFIG_EXPIRE_SECONDS);
      } catch (e) {
        console.error('保存配置到本地存储失败', e);
      }
    },
    
    // 获取单个配置
    async fetchConfig(key: string): Promise<string | null> {
      // 如果已缓存，直接返回
      if (this.configCache[key] !== undefined) {
        return this.configCache[key];
      }
      
      this.loading = true;
      
      try {
        const res = await getSysConfigByKey(key);
        if (res.code === 10000 && res.data) {
          // 类型安全转换
          const value = String(res.data);
          this.configCache[key] = value;
          
          // 保存到本地存储
          this.saveToStorage();
          return value;
        }
        return null;
      } catch (error) {
        console.error(`获取配置[${key}]失败:`, error);
        return null;
      } finally {
        this.loading = false;
      }
    },

    // 获取指定类型的所有配置
    async fetchConfigsByType(type: string): Promise<Record<string, string>> {
      // 如果已缓存，直接返回
      if (this.typeCache[type]) {
        return this.typeCache[type];
      }
      
      this.loading = true;
      
      try {
        const res = await getConfigsByType(type);
        if (res.code === 10000 && res.data) {
          // 安全地转换和存储数据
          const configs: Record<string, string> = {};
          
          // 将响应数据转换为字符串记录
          Object.entries(res.data as Record<string, any>).forEach(([k, v]) => {
            configs[k] = String(v);
            this.configCache[type + ':' + k] = String(v);
          });
          
          // 更新类型缓存
          this.typeCache[type] = configs;
          
          // 保存到本地存储
          this.saveToStorage();
          
          return configs;
        }
        return {};
      } catch (error) {
        console.error(`获取配置类型[${type}]失败:`, error);
        return {};
      } finally {
        this.loading = false;
      }
    },

    // 获取多个配置
    async fetchConfigsByKeys(keys: string[]): Promise<Record<string, string | null>> {
      // 过滤未缓存的键
      const uncachedKeys = keys.filter(key => this.configCache[key] === undefined);
      
      // 所有键已缓存，直接返回
      if (uncachedKeys.length === 0) {
        return keys.reduce((result, key) => {
          result[key] = this.configCache[key];
          return result;
        }, {} as Record<string, string>);
      }
      
      this.loading = true;
      
      try {
        const res = await getConfigsByKeys(uncachedKeys);
        if (res.code === 10000 && res.data) {
          // 安全地更新缓存
          Object.entries(res.data as Record<string, any>).forEach(([k, v]) => {
            this.configCache[k] = String(v);
          });
          
          // 保存到本地存储
          this.saveToStorage();
        }
        
        // 返回所有键的值
        return keys.reduce((result, key) => {
          result[key] = this.configCache[key] || null;
          return result;
        }, {} as Record<string, string | null>);
      } catch (error) {
        console.error(`批量获取配置失败:`, error);
        return keys.reduce((result, key) => {
          result[key] = this.configCache[key] || null;
          return result;
        }, {} as Record<string, string | null>);
      } finally {
        this.loading = false;
      }
    },

    // 获取多个类型的配置
    async fetchConfigsByTypes(types: string[]): Promise<Record<string, Record<string, string>>> {
      // 过滤未缓存的类型
      const uncachedTypes = types.filter(type => !this.typeCache[type]);
      
      // 所有类型已缓存，直接返回
      if (uncachedTypes.length === 0) {
        return types.reduce((result, type) => {
          result[type] = this.typeCache[type];
          return result;
        }, {} as Record<string, Record<string, string>>);
      }
      
      this.loading = true;
      
      try {
        const res = await getConfigsByTypes(uncachedTypes);
        if (res.code === 10000 && res.data) {
          // 类型安全处理
          const typesData = res.data as Record<string, Record<string, any>>;
          
          // 更新类型缓存和配置缓存
          Object.entries(typesData).forEach(([type, configs]) => {
            const typeConfigs: Record<string, string> = {};
            
            Object.entries(configs).forEach(([k, v]) => {
              const strValue = String(v);
              typeConfigs[k] = strValue;
              this.configCache[type + ':' + k] = strValue;
            });
            
            this.typeCache[type] = typeConfigs;
          });
          
          // 保存到本地存储
          this.saveToStorage();
        }
        
        // 返回所有请求的类型配置
        return types.reduce((result, type) => {
          result[type] = this.typeCache[type] || {};
          return result;
        }, {} as Record<string, Record<string, string>>);
      } catch (error) {
        console.error(`批量获取配置类型失败:`, error);
        return types.reduce((result, type) => {
          result[type] = this.typeCache[type] || {};
          return result;
        }, {} as Record<string, Record<string, string>>);
      } finally {
        this.loading = false;
      }
    },

    // 清除全部缓存
    clearCache() {
      this.configCache = {};
      this.typeCache = {};
      
      // 清除本地存储
      try {
        storage.remove(STORAGE_CONFIG_KEY);
        storage.remove(STORAGE_TYPE_KEY);
      } catch (e) {
        console.error('清除本地存储失败', e);
      }
    },
    
    // 清除指定类型的缓存
    clearTypeCache(type: string) {
      if (this.typeCache[type]) {
        // 获取该类型下所有配置键
        const keys = Object.keys(this.typeCache[type]);
        
        // 从配置缓存中移除
        keys.forEach(key => {
          delete this.configCache[key];
        });
        
        // 从类型缓存中移除
        delete this.typeCache[type];
        
        // 更新本地存储
        this.saveToStorage();
      }
    },
    
    // 强制刷新所有配置
    async refreshAllConfigs() {
      // 清除缓存
      this.clearCache();
      
      // 重新获取所有已知类型
      const types = Object.keys(this.typeCache);
      if (types.length > 0) {
        await this.fetchConfigsByTypes(types);
      }
    }
  }
});