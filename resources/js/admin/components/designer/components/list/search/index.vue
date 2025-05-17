<script setup lang="ts">
import { type ComponentSchema, type PageManager } from 'lanyunit-epic-designer'

const props = withDefaults(
    defineProps<{
        componentSchema: ComponentSchema
    }>(),
    {},
)

const children = computed(() => {
    return props.componentSchema.children ?? []
})

const pageManager = inject<PageManager>('pageManager')!
</script>

<template>
    <NForm inline label-placement="left" class="epic-list-search">
        <template v-if="pageManager.isDesignMode.value && !children.length">
            <div
                class="flex h-10 w-full items-center justify-center rounded-(--radius) border border-(--border) text-sm text-gray-500"
            >
                搜索区内容
            </div>
        </template>
        <template v-else>
            <slot name="edit-node">
                <template v-for="subcomponentSchema in children" :key="subcomponentSchema.type">
                    <!-- EBuildr组件通过node插槽渲染 start -->
                    <slot name="node" :componentSchema="subcomponentSchema" />
                </template>
            </slot>
            <NFormItem>
                <NSpace>
                    <NButton type="primary">搜索</NButton>
                    <NButton>重置</NButton>
                </NSpace>
            </NFormItem>
        </template>
    </NForm>
</template>

<style scoped>
.epic-list-search:deep(.epic-draggable-range) {
    display: inline-flex;
    align-items: flex-start;
    width: auto;
    flex: 1;
    min-height: 58px;
    height: auto;
}
</style>
