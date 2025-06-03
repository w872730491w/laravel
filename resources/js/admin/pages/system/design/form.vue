<script setup lang="ts">
import { useApiGet } from '@admin/utils/useRequest'

type Data = Partial<Design>

const { form } = useFormInject()

const defaultForm: Data = {
    name: '',
    type: 'list',
}

const options = shallowRef<{ label: string; value: string }[]>([])

const onOpen = async () => {
    const { types } = await useApiGet('admin.system.design.lazyData')
    options.value = types
}
</script>

<template>
    <FormModal
        :url="{
            add: 'admin.system.design.create',
            edit: 'admin.system.design.edit',
            detail: 'admin.system.design.detail',
        }"
        :default-form="defaultForm"
        @open="onOpen"
    >
        <NFormItem :rule="{ required: true, message: '请输入名称' }" path="name" label="名称">
            <NInput v-model:value="form.name" :maxlength="20" show-count placeholder="请输入名称" />
        </NFormItem>
        <NFormItem :rule="{ required: true, message: '请选择类型' }" path="type" label="类型">
            <NRadioGroup v-model:value="form.type">
                <NRadio v-for="item of options" :label="item.label" :value="item.value" />
            </NRadioGroup>
        </NFormItem>
    </FormModal>
</template>
