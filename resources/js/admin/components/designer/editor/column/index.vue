<script setup lang="ts">
import { cloneDeep } from 'lodash-es'
import type { FormInst } from 'naive-ui'
import type { SelectMixedOption } from 'naive-ui/es/select/src/interface'
import { toast } from 'vue-sonner'
import draggable from 'vuedraggable'

type Columns = Record<string, any>

const schema = defineModel<Columns[]>({
    required: true,
})

const showModal = ref(false)

const columnForm = ref<Columns>({
    key: null,
    title: null,
    ellipsis: 1,
    align: 'left',
    titleAlign: 'left',
    allowExport: true,
    fixed: '',
    width: 80,
    showSearch: false,
    searchComponent: null,
})

const fixedOptions = ref<SelectMixedOption[]>([
    {
        label: '不固定',
        value: '',
    },
    {
        label: '左测固定',
        value: 'left',
    },
    {
        label: '右侧固定',
        value: 'right',
    },
])

const alignOptions = ref<SelectMixedOption[]>([
    {
        label: '左对齐',
        value: 'left',
    },
    {
        label: '居中对齐',
        value: 'center',
    },
    {
        label: '右对齐',
        value: 'right',
    },
])

const ellipsisOptions = ref<SelectMixedOption[]>([
    {
        label: '不隐藏',
        value: '',
    },
    {
        label: '一行',
        value: 1,
    },
    {
        label: '两行',
        value: 2,
    },
])

const componentOptions = ref<SelectMixedOption[]>([
    {
        label: '输入框',
        value: 'input',
    },
    {
        label: '选择器',
        value: 'select',
    },
])

const columnFormRef = useTemplateRef<FormInst>('columnFormRef')

const editColumnIndex = ref<number | null>(null)
const handleEditColumn = (index: number) => {
    editColumnIndex.value = index
    columnForm.value = cloneDeep(schema.value?.[index])
    showModal.value = true
}

const handleAddColumn = () => {
    columnFormRef.value
        ?.validate()
        .then(() => {
            const newColumn = cloneDeep(columnForm.value)
            const index = editColumnIndex.value
            const uniqueIndex = schema.value
                .filter((v, columnIndex) => {
                    return index !== null ? columnIndex !== index : true
                })
                .findIndex((v) => v.key === newColumn.key)
            if (uniqueIndex !== -1) {
                toast.error('字段名已存在', {
                    position: 'top-center',
                })
                return
            }
            if (index !== null) {
                schema.value[index] = newColumn
                editColumnIndex.value = null
            } else {
                schema.value.push(newColumn)
            }
            schema.value = [...schema.value]
            showModal.value = false
            columnForm.value = {
                key: null,
                title: null,
                ellipsis: 1,
                align: 'left',
                titleAlign: 'left',
                allowExport: true,
                fixed: '',
                width: 80,
                showSearch: false,
                searchComponent: null,
            }
        })
        .catch(() => {})
}

const handleDeleteColumn = (index: number) => {
    schema.value.splice(index, 1)
}
</script>

<template>
    <div class="w-full text-xs">
        <div class="flex items-center justify-end">
            <NButton size="small" text @click="showModal = true">
                <template #icon>
                    <Icon name="material-symbols:add-rounded" />
                </template>
                添加列
            </NButton>
        </div>
        <div class="mt-2 w-full rounded border border-(--border) px-2 py-2">
            <div v-if="schema.length === 0" class="flex h-full items-center justify-center text-gray-500">暂无数据</div>
            <draggable v-else v-model="schema" :item-key="(item: Columns) => item.key" class="flex flex-col gap-2">
                <template #item="{ element, index }">
                    <div
                        :key="index"
                        class="flex cursor-move items-center justify-between px-2 py-2 transition-colors hover:bg-gray-100"
                    >
                        <div class="line-clamp-1 flex flex-1 items-center">{{ element.title }}-{{ element.key }}</div>
                        <div class="flex h-full items-center gap-2">
                            <div class="aspect-square h-full cursor-pointer overflow-hidden" @click="handleEditColumn(index)">
                                <Icon name="tabler:dots" />
                            </div>
                            <div class="aspect-square h-full cursor-pointer overflow-hidden" @click="handleDeleteColumn(index)">
                                <Icon name="material-symbols:close-rounded" />
                            </div>
                        </div>
                    </div>
                </template>
            </draggable>
        </div>
        <NModal
            v-model:show="showModal"
            title="添加列"
            size="small"
            preset="card"
            :mask-closable="false"
            style="width: 40%; max-width: 600px; min-width: 300px"
        >
            <NForm ref="columnFormRef" :model="columnForm" class="py-4">
                <NGrid :x-gap="12">
                    <NFormItemGi label="列名称" path="title" :span="12" :rule="{ required: true, message: '请输入列名称' }">
                        <NInput v-model:value="columnForm.title" />
                    </NFormItemGi>
                    <NFormItemGi label="字段名" path="key" :span="12" :rule="{ required: true, message: '请输入字段名' }">
                        <NInput v-model:value="columnForm.key" />
                    </NFormItemGi>
                </NGrid>
                <NGrid :x-gap="12">
                    <NFormItemGi label="是否搜索" path="showSearch" :span="12">
                        <NSwitch v-model:value="columnForm.showSearch" />
                    </NFormItemGi>
                    <NFormItemGi
                        v-if="columnForm.showSearch"
                        label="搜索组件"
                        path="searchComponent"
                        :span="12"
                        :rule="{ required: true, message: '请选择搜索组件' }"
                    >
                        <NSelect v-model:value="columnForm.searchComponent" :options="componentOptions" />
                    </NFormItemGi>
                </NGrid>
                <NGrid :x-gap="12">
                    <NFormItemGi label="是否固定" :span="12">
                        <NSelect v-model:value="columnForm.fixed" :options="fixedOptions" />
                    </NFormItemGi>
                    <NFormItemGi label="列宽" :span="12">
                        <NInputNumber v-model:value="columnForm.width" :min="0" :max="1000" :show-button="false" class="w-full">
                            <template #suffix> px </template>
                        </NInputNumber>
                    </NFormItemGi>
                    <NFormItemGi label="最小列宽" :span="12">
                        <NInputNumber
                            v-model:value="columnForm.minWidth"
                            :min="0"
                            :max="1000"
                            :show-button="false"
                            class="w-full"
                        >
                            <template #suffix> px </template>
                        </NInputNumber>
                    </NFormItemGi>
                    <NFormItemGi label="最大列宽" :span="12">
                        <NInputNumber
                            v-model:value="columnForm.maxWidth"
                            :min="0"
                            :max="1000"
                            :show-button="false"
                            class="w-full"
                        >
                            <template #suffix> px </template>
                        </NInputNumber>
                    </NFormItemGi>
                    <NFormItemGi label="列对齐" :span="12">
                        <NSelect v-model:value="columnForm.align" :options="alignOptions" />
                    </NFormItemGi>
                    <NFormItemGi label="表头对齐" :span="12">
                        <NSelect v-model:value="columnForm.titleAlign" :options="alignOptions" />
                    </NFormItemGi>
                    <NFormItemGi label="溢出隐藏" :span="12">
                        <NSelect v-model:value="columnForm.ellipsis" :options="ellipsisOptions" />
                    </NFormItemGi>
                    <NFormItemGi label="允许导出" :span="12">
                        <NSwitch v-model:value="columnForm.allowExport" />
                    </NFormItemGi>
                </NGrid>
            </NForm>
            <template #footer>
                <NSpace>
                    <NButton size="small" type="primary" @click="handleAddColumn"> 确定 </NButton>
                    <NButton size="small" @click="showModal = false"> 取消 </NButton>
                </NSpace>
            </template>
        </NModal>
    </div>
</template>
