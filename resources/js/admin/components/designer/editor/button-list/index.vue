<script setup lang="ts">
import {
    EpicNode,
    getUUID,
    getValueByPath,
    pluginManager,
    setValueByPath,
    type ComponentSchema,
    type Revoke,
} from 'lanyunit-epic-designer'
import { cloneDeep } from 'lodash-es'
import type { FormInst } from 'naive-ui'
import draggable from 'vuedraggable'

const schema = defineModel<ComponentSchema[]>({
    default: () => [],
})

const Button = pluginManager.getComponent('button')

const showModal = ref(false)

const columnForm = ref<ComponentSchema>({
    type: 'button',
    label: '按钮',
    componentProps: {
        type: 'primary',
        size: 'medium',
    },
})

function openModal() {
    showModal.value = true
    columnForm.value = {
        id: `list-actions-button_${getUUID()}`,
        type: 'button',
        label: '按钮',
        componentProps: {
            type: 'primary',
            size: 'medium',
        },
    }
}

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
            if (index !== null) {
                schema.value[index] = newColumn
                editColumnIndex.value = null
            } else {
                newColumn.id = `button_${getUUID()}`
                schema.value.push(newColumn)
            }
            schema.value = [...schema.value]
            showModal.value = false
        })
        .catch(() => {})
}

const handleDeleteColumn = (index: number) => {
    schema.value.splice(index, 1)
}

const attributes = [
    {
        componentProps: {
            disabled: true,
        },
        field: 'id',
        label: '组件ID',
        type: 'input',
    },
    ...pluginManager.getComponentConfingByType('button').config.attribute!,
]

const revoke = inject('revoke') as Revoke
/**
 * 设置属性值
 */
function handleSetValue(value: any, field: string, item: ComponentSchema, editData: null | object = columnForm.value) {
    if (typeof item.onChange === 'function') {
        item.onChange({ attributes, value, values: editData! })
    }
    // 判断是否同步修改属性值
    if (item.changeSync) {
        setValueByPath(editData!, field, value)
    } else {
        nextTick(() => {
            setValueByPath(editData!, field, value)
        })
    }
    // 将修改过的组件属性推入撤销操作的栈中
    revoke.push('编辑组件属性')
}
</script>

<template>
    <div class="w-full text-xs">
        <div class="flex items-center justify-end">
            <NButton size="small" text @click="openModal">
                <template #icon>
                    <Icon name="material-symbols:add-rounded" />
                </template>
                添加按钮
            </NButton>
        </div>
        <div class="mt-2 w-full rounded border border-(--border) px-2 py-2">
            <div v-if="schema.length === 0" class="flex h-full items-center justify-center text-gray-500">暂无按钮</div>
            <draggable v-else v-model="schema" :item-key="(item: ComponentSchema) => item.key" class="flex flex-col gap-2">
                <template #item="{ element, index }">
                    <div
                        :key="index"
                        class="flex cursor-move items-center justify-between px-2 py-2 transition-colors hover:bg-gray-100"
                    >
                        <div class="mr-2 flex flex-1 items-center">
                            <Button v-bind="element.componentProps">{{ element.label }}</Button>
                        </div>
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
            title="编辑按钮"
            size="small"
            preset="card"
            :mask-closable="false"
            style="width: 40%; max-width: 600px; min-width: 300px"
            content-style="max-height: 600px;overflow-y: auto;"
        >
            <NForm ref="columnFormRef" :model="columnForm" class="py-4">
                <NGrid :x-gap="12">
                    <NFormItemGi v-for="item in attributes" :key="item.field" :label="item.label" :span="12">
                        <EpicNode
                            :component-schema="{
                                ...item,
                                componentProps: {
                                    ...item.componentProps,
                                    ...(item.field === 'componentProps.defaultValue' ? columnForm.componentProps : {}),
                                    input: false,
                                    field: undefined,
                                    hidden: false,
                                },
                                show: true,
                                noFormItem: true,
                            }"
                            :model-value="getValueByPath(item.editData ?? columnForm, item.field!)"
                            @update:model-value="handleSetValue($event, item.field!, item, item.editData)"
                        >
                        </EpicNode>
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
