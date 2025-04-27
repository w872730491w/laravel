<script setup lang="ts">
import { useApiGet } from '@admin/utils/useRequest'

const form = ref({
    pid: null,
    status: true,
})

const options = [
    {
        label: '111',
        value: '1',
        children: [
            {
                label: '22',
                value: '33',
            },
        ],
    },
]

const onOpen = async () => {
    const { permissions } = await useApiGet('admin.system.permission.lazyData')
    console.log(permissions);
    
}
</script>

<template>
    <FormModal @open="onOpen">
        <NFormItem label="显示名称">
            <NInput placeholder="请输入显示名称" />
        </NFormItem>
        <NFormItem label="父级">
            <NCascader v-model:value="form.pid" placeholder="父级" :options="options" check-strategy="all" :filterable="true" />
        </NFormItem>
        <NFormItem label="标识">
            <NInput placeholder="请输入标识" />
        </NFormItem>
        <NFormItem label="状态">
            <NSwitch v-model:value="form.status" />
        </NFormItem>
    </FormModal>
</template>
