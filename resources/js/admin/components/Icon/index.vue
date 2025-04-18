<script setup lang="ts">
import { cn } from '@admin/lib/utils'
import { Icon } from '@iconify/vue'
import type { HTMLAttributes } from 'vue'

defineOptions({
    name: 'Icon',
})

const props = defineProps<{
    name: string
    class?: HTMLAttributes['class']
}>()
</script>

<template>
    <i :class="cn('flex-inline relative size-[1em] items-center justify-center fill-current leading-[1em]', props.class)">
        <Icon :icon="name" class="size-[inherit]! shrink-0" />
    </i>
</template>
