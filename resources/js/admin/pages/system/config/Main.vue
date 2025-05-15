<script setup lang="ts">
import type { PageSchema } from 'lanyunit-epic-designer'

const defaultSchema: PageSchema = {
    schemas: [
        {
            label: '列表',
            type: 'list',
            componentProps: {
                columns: [],
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
}

const onSave = (e: any) => {
    console.log(e)
}
</script>

<template>
    <Designer  @save="onSave" />
</template>
