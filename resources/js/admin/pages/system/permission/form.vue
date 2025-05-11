<script setup lang="ts">
import { useApiGet } from '@admin/utils/useRequest'
import type { OnUpdateValueImpl } from 'naive-ui/es/cascader/src/interface'

const form = ref({
    display_name: null,
    type: 0,
    name: null,
    pid: null,
    status: true,
})

const options = shallowRef([])

const onOpen = async () => {
    const { permissions } = await useApiGet('admin.system.permission.lazyData')
    options.value = permissions
}

const onSelectParent: OnUpdateValueImpl = (v, e) => {
    if (e && !Array.isArray(e) && !form.value.display_name) {
        form.value.name = (e.name as string) + '.'
    }
}
</script>

<template>
    <FormModal @open="onOpen">
        <NFormItem label="显示名称">
            <NInput v-model:value="form.display_name" placeholder="请输入显示名称" />
        </NFormItem>
        <NFormItem label="父级">
            <NCascader
                v-model:value="form.pid"
                placeholder="父级"
                label-field="display_name"
                value-field="id"
                :options="options"
                check-strategy="all"
                clearable
                :filterable="true"
                @update:value="onSelectParent"
            />
        </NFormItem>
        <NFormItem label="权限类型">
            <NRadioGroup v-model:value="form.type">
                <NRadio label="页面" :value="0" />
                <NRadio label="权限" :value="1" />
            </NRadioGroup>
        </NFormItem>
        <NFormItem label="标识">
            <NInput v-model:value="form.name" placeholder="请输入标识" :input-props="{ spellcheck: false }" />
        </NFormItem>
        <NFormItem label="状态">
            <NSwitch v-model:value="form.status" />
        </NFormItem>
    </FormModal>
</template>
