<script setup lang="ts">
import { type ComponentSchema, type PageManager } from 'lanyunit-epic-designer'
import { cloneDeep } from 'lodash-es'
import type { FormInst } from 'naive-ui'
import { provideKey } from '..'

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

const { search, getList } = inject(provideKey, {
    search: ref({}),
    getList: () => {},
    loading: ref(false),
    data: ref([]),
    paginationProps: ref({}),
})!

const searchForm = reactive(search.value)

let defaultSearch = cloneDeep(search.value)

const formRef = useTemplateRef<FormInst>('formRef')

const handleSubmit = () => {
    getList()
}

const resetSearch = () => {
    for (const key in searchForm) {
        searchForm[key] = defaultSearch[key] ?? null
    }
    getList()
}

provide('formData', searchForm)
</script>

<template>
    <NForm
        ref="formRef"
        :model="search"
        inline
        label-placement="left"
        class="epic-list-search flex-wrap"
        :class="{ 'design-mode': pageManager.isDesignMode.value }"
        @submit.prevent="handleSubmit"
    >
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
                    <NButton attr-type="submit" type="primary">搜索</NButton>
                    <NButton @click="resetSearch">重置</NButton>
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
.epic-list-search.design-mode:deep(> .edit-draggable-widget) {
    margin-right: 18px;
}
</style>
