import '@/styles/index.scss'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from '@/router'
import ElementPlus from 'element-plus'
import * as ElementPlusIconsVue from '@element-plus/icons-vue'
import 'virtual:svg-icons-register'


/**
 * 全局注册element-icon
 * @param app 
 */
export function useElementIcon(app: App): void {
    for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
        app.component(key, component)
    }
}

const app = createApp(App)


useElementIcon(app)


app.use(createPinia())
app.use(router)
app.use(ElementPlus)




app.mount('#app')
