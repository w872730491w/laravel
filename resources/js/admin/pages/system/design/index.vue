<script setup lang="tsx">
import { NButton, NSpace, type DataTableColumns } from 'naive-ui'
import DesignForm from './form.vue'

const columns: DataTableColumns<Design> = [
    {
        key: 'id',
        title: 'ID',
    },
    {
        key: 'name',
        title: '名称',
    },
    {
        key: 'type',
        title: '类型',
        width: 80,
    },
    {
        key: 'action',
        title: '操作',
        minWidth: 80,
        align: 'center',
        render: (row) => {
            return (
                <NSpace>
                    <NButton size="small" onClick={() => {
                        open('edit', { id: row.id })
                    }}>
                        修改
                    </NButton>
                    <NButton size="small" onClick={() => {
                        open('edit', { id: row.id })
                    }}>
                        设计
                    </NButton>
                </NSpace>
            )
        },
    },
]

const rowKey = (row: Design) => {
    return row.id
}

const { open } = useForm()
</script>

<template>
    <div class="h-full p-4">
        <List url="admin.system.design.list" :row-key="rowKey" :columns="columns">
            <template #search="{ search }">
                <NFormItem label="名称">
                    <NInput v-model:value="search.name" placeholder="搜索名称" />
                </NFormItem>
            </template>
            <template #actions>
                <NButton type="primary" @click="open('add')">
                    <template #icon>
                        <Icon name="ic:baseline-plus" />
                    </template>
                    添加
                </NButton>
            </template>
        </List>
        <DesignForm />
    </div>
</template>
