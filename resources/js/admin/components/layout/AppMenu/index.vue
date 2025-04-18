<script setup lang="ts">
import Item from './item.vue'
import SubMenu from './sub.vue'

defineProps<{
    menus: Record<string, any>[]
}>()
</script>

<template>
    <div class="flex h-full w-full flex-col overflow-hidden py-1 transition-all duration-300">
        <template v-for="item in menus" :key="item.id">
            <SubMenu v-if="item.children?.length" :menus="item" :unique-key="[item.path ?? item.id]" />
            <Item v-else :item="item" :unique-key="[item.path ?? item.id]" />
        </template>
    </div>
</template>
