<script setup lang="ts">
import type { ComponentSchema } from 'lanyunit-epic-designer'

const props = withDefaults(
    defineProps<{
        componentSchema: ComponentSchema
    }>(),
    {},
)

const children = computed(() => {
    return props.componentSchema.children ?? []
})
</script>

<template>
    <NForm inline label-placement="left" class="epic-list-search">
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
