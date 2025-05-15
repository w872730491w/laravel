<script setup lang="ts">
import { EDesigner, pluginManager, type ComponentConfigModel, type ComponentType } from 'lanyunit-epic-designer'
import 'lanyunit-epic-designer/dist/lanyunit-epic-designer.css'
import { setupNaiveUi } from 'lanyunit-epic-designer/dist/ui/naiveUi'

setupNaiveUi()

const components = import.meta.glob<true, string, ComponentConfigModel>('./components/**/index.ts', {
    eager: true,
    import: 'default',
})

Object.entries(components).forEach(([_key, value]) => {
    pluginManager.registerComponent(value)
})

const editorComponents = import.meta.glob<true, string, { name: string; component: ComponentType }>('./editor/**/index.ts', {
    eager: true,
    import: 'default',
})

Object.entries(editorComponents).forEach(([_key, value]) => {
    pluginManager.component(value.name, value.component)
})
</script>

<template>
    <EDesigner>
        <template #header-prefix> &nbsp; </template>
        <template #header-title> &nbsp; </template>
    </EDesigner>
</template>
