<script setup lang="ts">
import type { ComponentSchema, PageManager } from 'lanyunit-epic-designer'

const props = withDefaults(
    defineProps<{
        componentSchema: ComponentSchema
    }>(),
    {},
)

defineOptions({
    inheritAttrs: false,
})

const pageManager = inject<PageManager>('pageManager')!

const fieldColumns = computed(() => {
    return props.componentSchema.componentProps.columns
})

const columns = computed(() => {
    const columns = fieldColumns.value.map((column: Record<string, any>) => {
        return {
            key: column.key,
            title: column.title,
            align: column.align,
            width: column.width,
            minWidth: column.minWidth,
            maxWidth: column.maxWidth,
            fixed: column.fixed === '' ? false : column.fixed,
            ellipsis:
                column.ellipsis === ''
                    ? false
                    : {
                          tooltip: true,
                          lineClamp: column.ellipsis,
                      },
            titleAlign: column.titleAlign === '' ? false : column.titleAlign,
            allowExport: column.allowExport,
        }
    })
    if (props.componentSchema.componentProps.selection) {
        columns.unshift({
            type: 'selection',
            multiple: props.componentSchema.componentProps.selectionMultiple,
            align: 'center',
        })
    }
    return columns
})

const data = ref<Record<string, any>[]>([])

if (pageManager.isDesignMode.value) {
    watch(
        () => fieldColumns.value,
        (columns) => {
            if (columns.length === 0) {
                data.value = []
                return
            }
            data.value = new Array(10).fill(0).map((_, index) => {
                return columns.reduce((acc: Record<string, any>, column: any) => {
                    acc[column.key] = `第${index}行${column.key}`
                    return acc
                }, {})
            })
        },
        {
            immediate: true,
            deep: true,
        },
    )
}

const rowKey = (row: Record<string, any>) => {
    return row[fieldColumns.value[0].key]
}

defineExpose({})
</script>

<template>
    <div class="bg-background flex h-full w-full flex-col overflow-y-auto rounded p-4">
        <slot name="edit-node">
            <template v-for="subcomponentSchema in props.componentSchema.children" :key="subcomponentSchema.id">
                <!-- EBuildr组件通过node插槽渲染 start -->
                <slot name="node" :componentSchema="subcomponentSchema"></slot>
            </template>
        </slot>
        <NDataTable
            size="small"
            bordered
            :single-line="false"
            flex-height
            class="flex-1"
            default-expand-all
            :columns="columns"
            :data="data"
            :row-key="rowKey"
        />
    </div>
</template>
