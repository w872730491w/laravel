<script setup lang="ts">
import { cn } from '@admin/lib/utils'
import type { ScrollAreaRootProps } from 'reka-ui'
import { ScrollAreaCorner, ScrollAreaRoot, ScrollAreaViewport } from 'reka-ui'
import type { HTMLAttributes } from 'vue'
import { computed } from 'vue'
import ScrollBar from './ScrollBar.vue'

const props = defineProps<
    ScrollAreaRootProps & {
        class?: HTMLAttributes['class']
        scrollbar?: boolean
        onWheel?: (event: WheelEvent) => void
    }
>()

const delegatedProps = computed(() => {
    const { class: _, onWheel, ...delegated } = props

    return delegated
})

const viewportRef = useTemplateRef('viewportRef')

defineExpose({
    el: viewportRef,
})
</script>

<template>
    <ScrollAreaRoot v-bind="delegatedProps" :class="cn('relative overflow-hidden', props.class)">
        <ScrollAreaViewport
            ref="viewportRef"
            class="scroll-area-viewport h-full w-full rounded-[inherit]"
            @wheel.prevent="onWheel"
        >
            <slot />
        </ScrollAreaViewport>
        <ScrollBar :class="{ 'pointer-events-none opacity-0': !props.scrollbar }" />
        <ScrollAreaCorner />
    </ScrollAreaRoot>
</template>

<style scoped>
:deep(.scroll-area-viewport > div) {
    height: 100%;
}
</style>
