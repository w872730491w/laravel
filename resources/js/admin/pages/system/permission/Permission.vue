<script setup lang="tsx">
import { NButton, NSpace, NSwitch, type DataTableColumns } from 'naive-ui'
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
            return <NSwitch value={row.status}></NSwitch>
        },
    },
    {
        key: 'action',
        title: '操作',
        minWidth: 80,
        align: 'center',
        render: (row) => {
            return (
                <NSpace>
                    <NButton
                        size="small"
                        onClick={() => {
                            open('edit', { id: row.id })
                        }}
                    >
                        修改
                    </NButton>
                </NSpace>
            )
        },
    },
]

const { open } = useForm()

const rowKey = (row: Permission) => {
    return row.id
}
</script>

<template>
    <div class="h-full p-4">
        <List url="admin.system.permission.list" :row-key="rowKey" :columns="columns">
            <template #search="{ search }">
                <NFormItem label="显示名称">
                    <NInput v-model:value="search.name" placeholder="搜索显示名称" />
                </NFormItem>
            </template>
            <template #actions>
                <NButton type="primary" @click="open('add')">
                    <template #icon>
                        <Icon name="ic:baseline-plus" />
                    </template>
                    添加权限
                </NButton>
            </template>
        </List>
        <PermissionForm />
    </div>
</template>
