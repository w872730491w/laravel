<script setup lang="tsx">
import { NSwitch, type DataTableColumns } from 'naive-ui'
import PermissionForm from './form.vue'

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
        width: 80,
        align: 'center',
        render: (row) => {
            return <NSwitch v-model:value={row.status}></NSwitch>
        },
    },
]

const data = ref<Permission[]>(props.data)

const rowKey = (row: Permission) => row.id

const { open } = useForm()
</script>

<template>
    <div class="h-full p-4">
        <div class="bg-background flex h-full w-full flex-col overflow-y-auto rounded p-4">
            <NForm inline label-placement="left">
                <NFormItem label="名称">
                    <NInput placeholder="请输入名称" />
                </NFormItem>
                <NFormItem>
                    <NButton type="primary">搜索</NButton>
                </NFormItem>
            </NForm>
            <div class="my-4">
                <NButton type="primary" @click="open('add')">
                    <template #icon>
                        <Icon name="ic:baseline-plus" />
                    </template>
                    添加权限
                </NButton>
            </div>
            <NDataTable
                bordered
                :single-line="false"
                :columns="columns"
                :data="data"
                :row-key="rowKey"
                flex-height
                class="flex-1"
                default-expand-all
            />
        </div>
        <PermissionForm />
    </div>
</template>
