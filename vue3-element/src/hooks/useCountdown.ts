import { ref, onUnmounted, computed } from 'vue';

interface CountdownOptions {
    initialTime?: number;        // 初始倒计时时间（秒）
    autoStart?: boolean;         // 是否自动开始倒计时
    endText?: string;            // 倒计时结束显示的文本
    runningFormat?: string;      // 倒计时中的文本格式 (使用 {time} 作为替换符)
    resetOnUnmount?: boolean;    // 组件卸载时是否重置
}

/**
 * 倒计时Hook，用于验证码发送等场景
 * @param options 倒计时配置选项
 */
export function useCountdown(options: CountdownOptions = {}) {
    const {
        initialTime = 60,
        autoStart = false,
        endText = '发送验证码',
        runningFormat = '{time}秒后重发',
        resetOnUnmount = true
    } = options;

    const time = ref(initialTime);
    const isRunning = ref(false);
    const isPaused = ref(false);
    const timer = ref<ReturnType<typeof setInterval> | null>(null);

    // 计算显示文本
    const text = computed(() => {
        if (!isRunning.value) return endText.toString();
        return runningFormat.replace('{time}', time.value.toString());
    });



    // 是否可以发送（倒计时结束或未开始）
    const canSend = computed(() => !isRunning.value || time.value <= 0);

    /**
     * 开始倒计时
     */
    const start = () => {
        if (isRunning.value) return;

        reset();
        isRunning.value = true;
        isPaused.value = false;

        timer.value = setInterval(() => {
            if (time.value <= 1) {
                stop();
            } else {
                time.value--;
            }
        }, 1000);
    };

    /**
     * 停止倒计时
     */
    const stop = () => {
        if (timer.value) {
            clearInterval(timer.value);
            timer.value = null;
        }
        isRunning.value = false;
    };

    /**
     * 暂停倒计时
     */
    const pause = () => {
        if (!isRunning.value) return;

        if (timer.value) {
            clearInterval(timer.value);
            timer.value = null;
        }
        isPaused.value = true;
    };

    /**
     * 恢复倒计时
     */
    const resume = () => {
        if (!isPaused.value) return;

        isPaused.value = false;

        timer.value = setInterval(() => {
            if (time.value <= 1) {
                stop();
            } else {
                time.value--;
            }
        }, 1000);
    };

    /**
     * 重置倒计时
     */
    const reset = () => {
        stop();
        time.value = initialTime;
    };

    /**
     * 针对验证码发送的便捷方法
     * @param sendFunction 发送验证码的异步函数
     * @param showLoading 是否显示加载状态
     * @returns 返回发送结果
     */
    const sendCode = async (sendFunction: () => Promise<any>, showLoading = true) => {
        if (isRunning.value && time.value > 0) {
            return { success: false, message: '请稍后再试' };
        }

        try {
            if (showLoading) {
                uni.showLoading({ title: '发送中...' });
            }

            const result = await sendFunction();

            if (result && result.code === 10000) {
                start();
                return { success: true, data: result.data };
            } else {
                return {
                    success: false,
                    message: (result && result.message) || '发送失败',
                    data: result
                };
            }
        } catch (error) {
            console.error('发送验证码失败:', error);
            return { success: false, message: '网络错误，请稍后再试', error };
        } finally {
            if (showLoading) {
                uni.hideLoading();
            }
        }
    };

    // 如果配置了自动开始，则立即开始倒计时
    if (autoStart) {
        start();
    }

    // 组件卸载时清理定时器
    onUnmounted(() => {
        if (timer.value) {
            clearInterval(timer.value);
        }

        if (resetOnUnmount) {
            reset();
        }
    });

    return {
        time,
        text,
        isRunning,
        isPaused,
        canSend,
        start,
        stop,
        pause,
        resume,
        reset,
        sendCode
    };
}