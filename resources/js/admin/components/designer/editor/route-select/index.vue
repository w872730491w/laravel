<script setup lang="ts">
import { Ziggy } from '@admin/../ziggy.js'

const schema = defineModel<string | null>({
    required: true,
    default: () => null,
})

const options = Object.keys(Ziggy.routes).map((route) => ({
    label: route,
    value: route,
}))
</script>

<template>
    <NSelect v-model:value="schema" :options="options" :consistent-menu-width="false" />
</template>
