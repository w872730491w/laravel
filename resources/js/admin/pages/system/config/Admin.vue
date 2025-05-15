<script setup lang="ts">
import type { PageSchema } from 'lanyunit-epic-designer'
import { EBuilder } from 'lanyunit-epic-designer'

const defaultSchema: PageSchema = {
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
                        columns: [
                            {
                                key: 'id',
                                title: 'id',
                                ellipsis: 1,
                                align: 'left',
                                titleAlign: 'left',
                                allowExport: true,
                                fixed: '',
                                width: 80,
                            },
                        ],
                    },
                    children: [
                        {
                            type: 'list-search',
                            children: [],
                            componentProps: {},
                            show: true,
                            id: 'list-search_zegd05g0',
                        },
                        {
                            type: 'list-actions',
                            children: [],
                            componentProps: {},
                            show: true,
                            id: 'list-actions_71danmyo',
                        },
                    ],
                    id: 'list_oggn1bv8',
                },
            ],
        },
    ],
    script: "const { defineExpose, find } = epic;\n  \n  function test (){\n      console.log('test')\n  }\n  \n  // 通过defineExpose暴露的函数或者属性\n  defineExpose({\n   test\n  })",
}

const onSave = (e) => {
    console.log(e)
}
</script>

<template>
    <EBuilder :page-schema="defaultSchema" />
</template>
