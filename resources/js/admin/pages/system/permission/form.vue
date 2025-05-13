<script setup lang="ts">
import { useApiGet } from '@admin/utils/useRequest'
import type { OnUpdateValueImpl } from 'naive-ui/es/cascader/src/interface'

type Data = Partial<Permission>

const { form } = useFormInject()

const defaultForm: Data = {
    display_name: null,
    type: 0,
    name: null,
    pid: null,
    status: true,
}

const options = shallowRef([])

const onOpen = async () => {
    const { permissions } = await useApiGet('admin.system.permission.lazyData')
    options.value = permissions
}

const onSelectParent: OnUpdateValueImpl = (v, e) => {
    if (e && !Array.isArray(e) && !form.value.name) {
        form.value.name = (e.name as string) + '.'
    }
}
</script>

<template>
    <FormModal
        :url="{
            add: 'admin.system.permission.create',
            edit: 'admin.system.permission.edit',
            detail: 'admin.system.permission.detail',
        }"
        :default-form="defaultForm"
        @open="onOpen"
    >
        <NFormItem path="pid" label="父级">
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
        <NFormItem :rule="{ required: true, message: '请选择权限类型' }" path="type" label="权限类型">
            <NRadioGroup v-model:value="form.type">
                <NRadio label="页面" :value="0" />
                <NRadio label="权限" :value="1" />
            </NRadioGroup>
        </NFormItem>
        <NFormItem :rule="{ required: true, message: '请输入显示名称' }" path="display_name" label="显示名称">
            <NInput v-model:value="form.display_name" :maxlength="20" show-count placeholder="请输入显示名称" />
        </NFormItem>
        <NFormItem :rule="{ required: true, message: '请输入标识' }" path="name" label="标识">
            <NInput v-model:value="form.name" placeholder="请输入标识" :maxlength="100" :input-props="{ spellcheck: false }" />
        </NFormItem>
        <NFormItem v-if="form.type === 0" path="icon" label="图标">
            <NInput v-model:value="form.icon" placeholder="请输入图标" :maxlength="60" :input-props="{ spellcheck: false }" />
        </NFormItem>
        <NFormItem path="status" label="状态">
            <NSwitch v-model:value="form.status" />
        </NFormItem>
    </FormModal>
</template>
