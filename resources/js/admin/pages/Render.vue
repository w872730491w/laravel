<script setup lang="ts">
import type { PageSchema } from 'lanyunit-epic-designer';

const pageSchema = ref<PageSchema>({
    schemas: [
        {
            componentProps: {
                style: {
                    padding: '16px',
                },
            },
            id: 'root',
            label: '页面',
            type: 'page',
            children: [
                {
                    label: '列表',
                    type: 'list',
                    componentProps: {
                        api: 'admin.system.admin.list',
                        columns: [
                            {
                                key: 'ID',
                                title: 'id',
                                ellipsis: 1,
                                align: 'left',
                                titleAlign: 'left',
                                allowExport: true,
                                fixed: '',
                                width: 80,
                                showSearch: true,
                                searchComponent: 'input',
                            },
                            {
                                key: 'nickname',
                                title: '昵称',
                                ellipsis: 1,
                                align: 'left',
                                titleAlign: 'left',
                                allowExport: true,
                                fixed: '',
                                width: 80,
                                showSearch: true,
                                searchComponent: 'input',
                            },
                            {
                                key: 'username',
                                title: '用户名',
                                ellipsis: 1,
                                align: 'left',
                                titleAlign: 'left',
                                allowExport: true,
                                fixed: '',
                                width: null,
                                showSearch: true,
                                searchComponent: 'input',
                            },
                        ],
                        selection: false,
                        selectionMultiple: false,
                    },
                    children: [
                        {
                            type: 'list-search',
                            children: [
                                {
                                    id: 'list-search-ID',
                                    type: 'input',
                                    field: 'ID',
                                    componentProps: {
                                        placeholder: '搜索id',
                                    },
                                    editConstraints: {
                                        copyable: false,
                                        deleteable: false,
                                    },
                                },
                                {
                                    id: 'list-search-nickname',
                                    type: 'input',
                                    field: 'nickname',
                                    componentProps: {
                                        placeholder: '搜索昵称',
                                    },
                                    editConstraints: {
                                        copyable: false,
                                        deleteable: false,
                                    },
                                },
                                {
                                    id: 'list-search-username',
                                    type: 'input',
                                    field: 'username',
                                    componentProps: {
                                        placeholder: '搜索用户名',
                                    },
                                    editConstraints: {
                                        copyable: false,
                                        deleteable: false,
                                    },
                                },
                            ],
                            componentProps: {},
                            show: true,
                            id: 'list-search_lezkbe6s',
                        },
                        {
                            type: 'list-actions',
                            children: [
                                {
                                    id: 'button_umgb2t',
                                    type: 'button',
                                    label: '添加',
                                    componentProps: {
                                        type: 'primary',
                                        size: 'medium',
                                    },
                                    icon: 'line-md:plus',
                                },
                            ],
                            componentProps: {},
                            show: true,
                            id: 'list-actions_ht2pju42',
                        },
                    ],
                    id: 'list_lwhmaidm',
                },
            ],
        },
    ],
    script: "const { defineExpose, find } = epic;\n  \n  function test (){\n      console.log('test')\n  }\n  \n  // 通过defineExpose暴露的函数或者属性\n  defineExpose({\n   test\n  })",
})
</script>

<template>
    <div>
        <DesignerBuilder :page-schema="pageSchema" />
    </div>
</template>
