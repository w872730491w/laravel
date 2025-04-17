<script setup lang="ts">
import Avatar from '@/components/avatar/index.vue'
import Dropdown from '@/components/dropdown/index.vue'
import Icon from '@/components/Icon/index.vue'
import Button from '@/components/ui/button/Button.vue'
import { router } from '@inertiajs/vue3'

defineOptions({
    name: 'Toolbar',
})
</script>

<template>
    <div class="toolbar-container flex items-center justify-between">
        <div class="left-side flex h-full items-center overflow-hidden ps-2 pe-16">
            <div class="flex items-center">
                <n-breadcrumb>
                    <n-breadcrumb-item>北京总行 </n-breadcrumb-item>
                    <n-breadcrumb-item>天津分行 </n-breadcrumb-item>
                    <n-breadcrumb-item>平山道支行 </n-breadcrumb-item>
                </n-breadcrumb>
            </div>
        </div>
        <div class="flex h-full flex-shrink-0 items-center justify-end px-2">
            <div class="flex items-center">
                <!-- <Button variant="outline" class="mx-2 h-9 px-3">
                    <Icon name="ri:search-line" class="size-4" />
                    <span class="text-muted-foreground/60 group-hover:text-muted-foreground text-sm transition">搜索</span>
                    <kbd
                        class="bg-muted text-muted-foreground inline-flex h-6 items-center gap-1 rounded-md border px-1.5 font-sans text-sm font-medium select-none"
                    >
                        Ctrl K
                    </kbd>
                </Button> -->
                <Dropdown
                    align="end"
                    :items="[
                        [
                            {
                                label: '主页',
                                icon: 'mdi:home',
                                // handle: () => router.push({ path: settingsStore.settings.home.fullPath }),
                            },
                            {
                                label: '个人设置',
                                icon: 'mdi:account',
                                // handle: () => (isProfileShow = true)
                            },
                        ],
                        [
                            {
                                label: '退出登录',
                                icon: 'mdi:logout',
                                handle: () => router.post(route('admin.logout')),
                            },
                        ],
                    ]"
                    class="flex items-center justify-center"
                >
                    <template #label>
                        <div class="space-y-2">
                            <div class="text-secondary-foreground/50 text-xs font-light">当前登录账号</div>
                            <div class="flex items-center justify-start gap-2">
                                <Avatar src="https://placehold.co/40x40" fallback="admin" shape="square" />
                                <div class="space-y-1">
                                    <div class="text-base">admin</div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <Button variant="ghost" class="flex h-9 items-center justify-center gap-1 px-2">
                        <Avatar src="https://placehold.co/40" class="size-6">
                            <Icon name="carbon:user-avatar-filled-alt" class="text-secondary-foreground/50 size-6" />
                        </Avatar>
                        admin
                        <Icon name="ep:caret-bottom" />
                    </Button>
                </Dropdown>
            </div>
        </div>
    </div>
</template>

<style scoped>
.toolbar-container {
    height: var(--toolbar-height);
    background-color: var(--background);
    transition: background-color 0.3s;

    .left-side {
        mask-image: linear-gradient(to right, #000 0%, #000 calc(100% - 50px), transparent);
    }
}
</style>
