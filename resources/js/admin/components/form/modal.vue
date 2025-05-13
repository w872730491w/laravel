<script setup lang="ts" generic="T extends FormProps['model']">
import type { FormType } from '@admin/utils/useForm'
import { usePage } from '@inertiajs/vue3';
import { useEventBus } from '@vueuse/core';
import { cloneDeep } from 'lodash-es'
import type { FormProps, ModalProps } from 'naive-ui'
import type { ValidRouteName } from 'ziggy-js'

type FormURL = {
    [x in FormType]?: ValidRouteName
}

const page = usePage()

const props = defineProps<{
    url: FormURL
    rules?: FormProps['rules']
    defaultForm?: T
    beforeSubmit?: (data: Record<string, any>) => Record<string, any> | Promise<Record<string, any>>
}>()

const emits = defineEmits<{
    open: []
    close: []
}>()

const bus = useEventBus<string>(page.url)

const { show, type, URLParams, form } = useFormInject()

const formEl = useTemplateRef('formEl')

const onSubmit: FormProps['onSubmit'] = () => {
    formEl.value
        ?.validate()
        .then(async () => {
            if (!props.url[type.value]) {
                return console.error('请配置props的url.' + type.value)
            }
            let data = cloneDeep(form.value)
            if (props.beforeSubmit) {
                data = await props.beforeSubmit(data)
            }
            useApiPost(props.url[type.value]!, data, {
                showMessage: true,
            })
                .then((res) => {
                    bus.emit('reload')
                    show.value = false
                })
                .catch((err) => {
                    console.log(err)
                })
        })
        .catch((err) => {
            console.log(err)
        })
}

const detailLoading = ref(false)
function getDetail() {
    const url = props.url['detail']
    if (!url) {
        console.error('请配置props的url.detail')
        return
    }
    detailLoading.value = true
    return useApiGet(url, URLParams.value).finally(() => {
        detailLoading.value = false
    })
}

const onLeave = () => {
    form.value = props.defaultForm ? cloneDeep(props.defaultForm) : {}
}

const onUpdateShow: ModalProps['onUpdate:show'] = async (v) => {
    if (v === false) {
        emits('close')
        show.value = false
        return
    }
    if (!['edit', 'detail'].includes(type.value)) {
        emits('open')
        props.defaultForm && (form.value = cloneDeep(props.defaultForm))
        show.value = true
        return
    }
    const request = getDetail()
    if (!request) {
        return
    }
    const data = await request.catch(() => {})
    form.value = data
    emits('open')
    show.value = true
}

watch(show, (v) => {
    onUpdateShow(v)
})

if (import.meta.hot) {
    if (show.value) {
        emits('open')
    }
}
</script>

<template>
    <NModal
        :show="show"
        style="width: 600px"
        preset="card"
        title="添加权限"
        :bordered="false"
        :mask-closable="false"
        @update:show="onUpdateShow"
        @after-leave="onLeave"
    >
        <NSpin size="large" :show="detailLoading">
            <NForm :model="form" ref="formEl" @submit.prevent="onSubmit">
                <slot></slot>
            </NForm>
        </NSpin>
        <template #footer>
            <NSpace>
                <NButton :disabled="detailLoading" type="primary" @click="onSubmit">提交</NButton>
                <NButton @click="onUpdateShow(false)">关闭</NButton>
            </NSpace>
        </template>
    </NModal>
</template>
