<script setup lang="ts" generic="T extends RowData">
import { usePage } from '@inertiajs/vue3'
import { useEventBus } from '@vueuse/core'
import type { PaginationProps } from 'naive-ui'
import type { CreateRowKey, RowData, TableColumns } from 'naive-ui/es/data-table/src/interface'
import type { ValidRouteName } from 'ziggy-js'

const props = withDefaults(
    defineProps<{
        url: ValidRouteName
        columns: TableColumns<T>
        rowKey?: CreateRowKey<T>
    }>(),
    {
        rowKey: (row: RowData) => row.id,
    },
)

const page = usePage()

const tableEl = useTemplateRef('tableEl')

const data = shallowRef<T[]>([])

const showPagination = ref(true)
const pagination = ref<PaginationProps>({
    page: 1,
    pageSize: 20,
})

const search = ref<Record<string, any>>({})

const loading = ref(false)

const getList = () => {
    loading.value = true
    const page = pagination.value
    useApiPost(props.url, {
        page: page.page,
        limit: page.pageSize,
    })
        .then((res) => {
            if (Array.isArray(res)) {
                showPagination.value = false
                data.value = res
            } else {
                showPagination.value = true
                data.value = res.rows
                pagination.value.itemCount = res.total
            }
        })
        .finally(() => {
            loading.value = false
        })
}

const resetList = () => {
    pagination.value.page = 1
    search.value = {}
    getList()
}

onMounted(() => {
    getList()
})

const bus = useEventBus<string>(page.url)

bus.on((event: string) => {
    if (event === 'reload') {
        getList()
    }
})
</script>

<template>
    <div class="bg-background flex h-full w-full flex-col overflow-y-auto rounded p-4">
        <NForm v-if="$slots.search" inline label-placement="left">
            <slot name="search" :search="search"></slot>
            <NFormItem>
                <NSpace>
                    <NButton type="primary">搜索</NButton>
                    <NButton @click="resetList">重置</NButton>
                </NSpace>
            </NFormItem>
        </NForm>
        <div v-if="$slots.actions" class="my-4">
            <slot name="actions"></slot>
        </div>
        <NDataTable
            ref="tableEl"
            :loading="loading"
            bordered
            :single-line="false"
            :columns="columns"
            :data="data"
            :row-key="rowKey"
            :pagination="showPagination ? pagination : false"
            flex-height
            class="flex-1"
            default-expand-all
        />
    </div>
</template>
