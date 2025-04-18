<script setup lang="ts">
import AppTabbar from './Tabbar/index.vue'
import AppToolbar from './Toolbar/index.vue'
</script>

<template>
    <div class="topbar flex w-full flex-col shadow-[0_1px_var(--border)]">
        <AppTabbar />
        <AppToolbar />
    </div>
</template>

<style scoped>
.topbar {
    position: relative;
    &::before {
        position: absolute;
        bottom: 0;
        left: 0;
        z-index: 1;
        width: 100%;
        height: 50px;
        pointer-events: none;
        content: '';
        background-image: linear-gradient(0deg, transparent, var(--main-area-bg));
        box-shadow: 0 -1px hsl(var(--border));
        opacity: 0;
        transform: translateY(100%);
        transition: opacity 0.3s;
    }
}
</style>
