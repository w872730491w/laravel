<script setup lang="ts">
import type { DataTableColumns } from 'naive-ui'

interface Permission {
    id: number
    pid: number
    type: 0 | 1
    route: string
    sort: number
    status: 0 | 1
    status_text: string
    name: string
    display_name: string
    icon: string
    created_at: string
    updated_at: string
}

const props = defineProps<{
    data: Permission[]
}>()

const columns: DataTableColumns<Permission> = [
    {
        key: 'display_name',
        title: '显示名称',
        width: 180,
        ellipsis: {
            tooltip: true,
        },
    },
    {
        key: 'name',
        title: '标识',
    },
    {
        key: 'status',
        title: '状态',
        render: (row) => row.status_text,
    },
]

const data = ref<Permission[]>(props.data)

const rowKey = (row: Permission) => row.id
</script>

<template>
    <div class="h-full p-4">
        <div class="bg-background h-full w-full overflow-y-auto rounded p-4">
            <NDataTable
                bordered
                :single-line="false"
                :columns="columns"
                :data="data"
                :row-key="rowKey"
                flex-height
                class="h-full"
                default-expand-all
            />
        </div>
    </div>
</template>
