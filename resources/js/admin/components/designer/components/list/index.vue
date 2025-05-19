<script setup lang="tsx">
import { pluginManager, type ComponentSchema, type PageManager } from 'lanyunit-epic-designer'
import { NButton, type PaginationProps } from 'naive-ui'
import { provideKey } from './index'
import { isNil, omitBy } from 'lodash-es'
import Icon from '@admin/components/Icon/index.vue'

const { componentSchema } = defineProps<{
    componentSchema: ComponentSchema
}>()

defineOptions({
    inheritAttrs: false,
})

const pageManager = inject<PageManager>('pageManager')!

const fieldColumns = computed(() => {
    return componentSchema.componentProps.columns
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
    if (componentSchema.componentProps.selection) {
        columns.unshift({
            type: 'selection',
            multiple: componentSchema.componentProps.selectionMultiple,
            align: 'center',
        })
    }
    if (componentSchema.componentProps.actions?.length) {
        columns.push({
            key: 'actions',
            title: '操作',
            width: 100,
            fixed: 'right',
            render: (row: Record<string, any>) => {
                return componentSchema.componentProps.actions
                    .filter((v: any) => {
                        if (!pageManager.isDesignMode.value && v.show) {
                            try {
                                return new Function('row', v.show)(row)
                            } catch (error) {
                                console.error(error)
                                return false
                            }
                        }
                        return true
                    })
                    .map((action: ComponentSchema) => {
                        const disabled = () => {
                            if (!action.disable) {
                                return false
                            }
                            try {
                                return !!new Function('row', action.disable)(row)
                            } catch (error) {
                                console.error(error)
                                return true
                            }
                        }
                        return (
                            <NButton {...action.componentProps} disabled={disabled()}>
                                {{
                                    default: () => action.label,
                                    icon: () => (action.icon ? <Icon name={action.icon} /> : null),
                                }}
                            </NButton>
                        )
                    })
            },
        })
    }
    return columns
})

const loading = ref(false)
const data = ref<Record<string, any>[]>([])
const paginationProps = ref<PaginationProps>({
    page: 1,
    pageSize: 10,
    itemCount: 0,
    simple: false,
    showSizePicker: true,
    showQuickJumper: true,
    prefix: ({ itemCount }) => `共 ${itemCount} 条`,
    suffix: () => '页',
})
const search = ref<Record<string, any>>({})

function getList() {
    loading.value = true
    const post = {
        page: paginationProps.value.page,
        limit: paginationProps.value.pageSize,
        search: omitBy(search.value, (value) => {
            return isNil(value) || value === ''
        }),
    }
    useApiPost(componentSchema.componentProps.api, post)
        .then(({ rows, total }) => {
            data.value = rows
            if (total) {
                paginationProps.value.itemCount = total
            }
        })
        .finally(() => {
            loading.value = false
        })
}

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

onMounted(() => {
    if (pageManager.isDesignMode.value) {
        return
    }
    getList()
})

const rowKey = (row: Record<string, any>) => {
    return row[fieldColumns.value[0].key]
}

provide(provideKey, {
    getList,
    loading,
    data,
    paginationProps: paginationProps as Ref<Record<string, any>>,
    search,
})

defineExpose({
    getList,
})
</script>

<template>
    <div class="bg-background flex h-full w-full flex-col overflow-y-auto rounded p-4">
        <slot name="edit-node">
            <template v-for="subcomponentSchema in componentSchema.children" :key="subcomponentSchema.id">
                <!-- EBuildr组件通过node插槽渲染 start -->
                <slot name="node" :componentSchema="subcomponentSchema"></slot>
            </template>
        </slot>
        <NDataTable
            :columns="columns"
            :data="data"
            :row-key="rowKey"
            :loading="loading"
            :single-line="false"
            :pagination="paginationProps"
            bordered
            size="small"
            :flex-height="!pageManager.isDesignMode.value"
            :class="{ 'flex-1': !pageManager.isDesignMode.value }"
            default-expand-all
        />
        <slot></slot>
    </div>
</template>
