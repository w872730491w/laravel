<script setup lang="ts">
import { type ComponentSchema, type PageManager } from 'lanyunit-epic-designer'

const { componentSchema } = defineProps<{
    componentSchema: ComponentSchema
}>()

const children = computed(() => {
    const children = componentSchema.children || []
    return children
})

const pageManager = inject<PageManager>('pageManager')!
</script>

<template>
    <div class="my-4">
        <template v-if="pageManager.isDesignMode.value && !children.length">
            <div class="flex h-10 items-center justify-center rounded-(--radius) border border-(--border) text-sm text-gray-500">
                操作区内容
            </div>
        </template>
        <NSpace align="center">
            <slot name="edit-node">
                <template v-for="subcomponentSchema in children" :key="subcomponentSchema.id">
                    <!-- EBuildr组件通过node插槽渲染 start -->
                    <slot name="node" :componentSchema="subcomponentSchema" />
                </template>
            </slot>
        </NSpace>
    </div>
</template>
