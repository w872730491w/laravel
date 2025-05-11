<script setup lang="ts">
const emits = defineEmits<{
    open: []
    close: []
}>()

const { show } = useFormInject()

if (import.meta.hot) {
    if (show.value) {
        emits('open')
    }
}
</script>

<template>
    <NModal
        v-model:show="show"
        style="width: 600px"
        preset="card"
        title="添加权限"
        :bordered="false"
        @after-enter="emits('open')"
        @after-leave="emits('close')"
    >
        <NForm>
            <slot></slot>
        </NForm>
        <template #footer>
            <NSpace>
                <NButton type="primary">提交</NButton>
                <NButton>关闭</NButton>
            </NSpace>
        </template>
    </NModal>
</template>
