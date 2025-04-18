import { useColorMode } from '@vueuse/core'

const { system, store } = useColorMode({
    attribute: 'class',
    initialValue: 'light',
    storageKey: 'lanyun_theme',
})

export const useTheme = () => {
    return {
        theme: computed(() => (store.value === 'auto' ? system.value : store.value)),
        mode: store,
    }
}
