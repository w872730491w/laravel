<script setup lang="ts">
import type { ComponentSchema } from 'lanyunit-epic-designer'

const props = withDefaults(
    defineProps<{
        componentSchema: ComponentSchema
    }>(),
    {},
)

const subcomponentSchema = {
    componentProps: {
        bordered: true,
        size: 'medium',
        type: 'default',
    },
    field: 'button_h5qt2n02',
    input: false,
    label: '按钮',
    type: 'button',
    id: 'button_h5qt2n02',
}
</script>

<template>
    <div class="my-4">
        <!-- <slot name="edit-node">
            <template v-for="subcomponentSchema in props.componentSchema.children" :key="subcomponentSchema.id">
                <slot name="node" :componentSchema="subcomponentSchema" />
            </template>
        </slot> -->
        <slot name="node" :componentSchema="subcomponentSchema" />
    </div>
</template>
