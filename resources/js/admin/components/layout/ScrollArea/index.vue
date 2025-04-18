<script setup lang="ts">
import { cn } from '@admin/lib/utils'
import { useElementSize, useScroll } from '@vueuse/core'
import type { HTMLAttributes } from 'vue'
import ScrollArea from './ScrollArea.vue'
import ScrollBar from './ScrollBar.vue'

const props = withDefaults(
    defineProps<{
        horizontal?: boolean
        scrollbar?: boolean
        mask?: boolean
        gradientColor?: string
        class?: HTMLAttributes['class']
        contentClass?: HTMLAttributes['class']
    }>(),
    {
        horizontal: false,
        scrollbar: true,
        mask: false,
        gradientColor: 'bg-background',
    },
)

const arrivedState = ref<{
    left: boolean
    right: boolean
    top: boolean
    bottom: boolean
}>()

const scrollAreaRef = useTemplateRef('scrollAreaRef')
const scrollContainerRef = useTemplateRef('scrollContainerRef')

function onWheel(event: WheelEvent) {
    if (props.horizontal) {
        scrollAreaRef.value?.el?.viewportElement?.scrollBy({
            left: event.deltaY || event.detail,
        })
    } else {
        scrollAreaRef.value?.el?.viewportElement?.scrollBy({
            top: event.deltaY || event.detail,
        })
    }
}

const showMaskStart = computed(() => {
    if (props.horizontal) {
        return !arrivedState.value?.left
    }
    return !arrivedState.value?.top
})
const showMaskEnd = computed(() => {
    if (props.horizontal) {
        return !arrivedState.value?.right
    }
    return !arrivedState.value?.bottom
})

onMounted(() => {
    const { arrivedState: arrivedStateValue } = useScroll(scrollAreaRef.value?.el?.viewportElement)
    watch(
        arrivedStateValue,
        (value) => {
            arrivedState.value = value
        },
        {
            immediate: true,
        },
    )
    const { width, height } = useElementSize(scrollContainerRef.value)
    watch(
        [width, height],
        () => {
            scrollAreaRef.value?.el?.viewportElement?.dispatchEvent(new Event('scroll'))
        },
        {
            immediate: true,
        },
    )
})

defineExpose({
    ref: scrollAreaRef,
    scrollTo,
})
</script>

<template>
    <div
        ref="scrollContainerRef"
        :class="
            cn(
                'relative flex overflow-hidden',
                'after:pointer-events-none after:absolute after:z-10 after:from-transparent after:opacity-0 after:transition-opacity after:content-[\'\']',
                'before:pointer-events-none before:absolute before:z-10 before:from-transparent before:opacity-0 before:transition-opacity before:content-[\'\']',
                {
                    'after:end-0 after:h-full after:w-12 after:bg-gradient-to-r after:to-[var(--mask-scroll-container-gradient-color)] after:rtl:bg-gradient-to-l':
                        props.horizontal,
                    'before:start-0 before:h-full before:w-12 before:bg-gradient-to-l before:to-[var(--mask-scroll-container-gradient-color)] before:rtl:bg-gradient-to-r':
                        props.horizontal,
                    'after:bottom-0 after:h-12 after:w-full after:bg-gradient-to-b after:to-[var(--mask-scroll-container-gradient-color)]':
                        !props.horizontal,
                    'before:h-12 before:w-full before:bg-gradient-to-t before:to-[var(--mask-scroll-container-gradient-color)]': !props.horizontal,
                    'before:opacity-100!': props.mask && showMaskStart,
                    'after:opacity-100!': props.mask && showMaskEnd,
                },
                props.class,
            )
        "
    >
        <ScrollArea ref="scrollAreaRef" :class="cn('relative z-0 flex-1', props.contentClass)" :scrollbar="props.scrollbar" :on-wheel="onWheel">
            <slot />
            <ScrollBar v-if="props.horizontal" orientation="horizontal" :class="{ 'pointer-events-none opacity-0': !props.scrollbar }" />
        </ScrollArea>
    </div>
</template>
