<script setup lang="ts">
import type { PageSchema } from 'lanyunit-epic-designer'

const pageSchema = ref<PageSchema>({
    schemas: [
        {
            label: '列表',
            type: 'list',
            componentProps: {
                api: 'admin.system.admin.list',
                columns: [
                    {
                        key: 'id',
                        title: 'ID',
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
                        width: 80,
                        showSearch: true,
                        searchComponent: 'input',
                    },
                ],
                actions: [
                    {
                        id: 'button_78muek',
                        type: 'button',
                        label: '按钮',
                        show: 'console.log(row);\nreturn row.id === 1',
                        componentProps: {
                            type: 'primary',
                            size: 'medium',
                        },
                        icon: 'mingcute:edit-line',
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
                            id: 'list-search-id',
                            type: 'input',
                            field: 'id',
                            input: true,
                            componentProps: {
                                bindModel: 'value',
                                placeholder: '搜索ID',
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
                            input: true,
                            componentProps: {
                                bindModel: 'value',
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
                            input: true,
                            componentProps: {
                                bindModel: 'value',
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
                    id: 'list-search_ggsa8qz2',
                },
                {
                    type: 'list-actions',
                    children: [
                        {
                            id: 'button_ad4a0u',
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
                    id: 'list-actions_u1kp8667',
                },
            ],
            id: 'list_d43p1vf4',
        },
    ],
    script: "const { defineExpose, find } = epic;\n  \n  function test (){\n      console.log('test')\n  }\n  \n  // 通过defineExpose暴露的函数或者属性\n  defineExpose({\n   test\n  })",
})
</script>

<template>
    <div class="h-full p-4">
        <DesignerBuilder :page-schema="pageSchema" class="h-full" />
    </div>
</template>
