<script setup lang="ts">
import Icon from '@/components/Icon/index.vue'
import { Button } from '@/components/ui/button'
import { cn } from '@/lib/utils'
import { Link, usePage } from '@inertiajs/vue3'
import AppMenu from './AppMenu/index.vue'
import ScrollArea from './ScrollArea/index.vue'

const page = usePage()

const { isCollapse, menus } = inject<{
    isCollapse: Ref<boolean>
    menus: Record<string, any>[]
}>('app-sidebar')!

const toogleCollapse = () => {
    isCollapse.value = !isCollapse.value
}
</script>

<template>
    <div class="flex transition-all">
        <Transition name="main-sidebar">
            <div class="bg-background flex h-full w-(--sidebar-width) flex-col transition-all">
                <Link
                    :href="route('admin.home')"
                    class="w-inherit flex-center sidebar-logo h-(--sidebar-logo-height) cursor-pointer gap-2 px-3 text-inherit no-underline"
                >
                    <img src="/static/admin/images/logo.png" class="h-[30px] w-[30px] object-contain" />
                </Link>
                <div class="flex-1 overflow-x-hidden overflow-y-auto overscroll-contain">
                    <div class="-mt-2 flex w-full flex-col overflow-hidden py-1 transition-all">
                        <div class="menu-item active relative px-2 py-1 transition-all">
                            <div
                                class="group menu-item-container relative flex size-full h-full w-full cursor-pointer items-center justify-between gap-1 rounded-lg px-2! py-4 text-(--muted-foreground) transition-all hover:bg-(--accent) hover:text-(--accent-foreground)"
                            >
                                <div class="inline-flex w-full flex-1 flex-col items-center justify-center gap-1">
                                    <Icon name="uim:box" class="text-xl transition-transform group-hover:scale-120" />
                                    <span class="transition-height transition-width w-full flex-1 truncate text-center text-sm transition-opacity">
                                        演示
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="menu-item relative px-2 py-1 transition-all">
                            <div
                                class="group menu-item-container relative flex size-full h-full w-full cursor-pointer items-center justify-between gap-1 rounded-lg px-2! py-4 text-(--muted-foreground) transition-all hover:bg-(--accent) hover:text-(--accent-foreground)"
                            >
                                <div class="inline-flex w-full flex-1 flex-col items-center justify-center gap-1">
                                    <Icon name="uim:box" class="text-xl transition-transform group-hover:scale-120" />
                                    <span class="transition-height transition-width w-full flex-1 truncate text-center text-sm transition-opacity">
                                        演示
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
        <Transition name="sub-sidebar">
            <div
                :class="
                    cn('bg-background flex h-full flex-col shadow-[-1px_0_0_var(--border),1px_0_0_var(--border)] transition-[width] duration-300', {
                        'w-(--sidebar-collapse-width)': isCollapse,
                        'w-(--sidebar-menu-width)': !isCollapse,
                    })
                "
            >
                <Link
                    v-if="!isCollapse"
                    :href="route('admin.home')"
                    class="w-inherit flex-center sidebar-logo h-[50px] cursor-pointer gap-2 px-3 text-inherit no-underline"
                >
                    <span class="block truncate font-bold">Fantastic-admin 专业版</span>
                </Link>
                <ScrollArea :scrollbar="false" mask class="flex-1">
                    <AppMenu :menus />
                </ScrollArea>
                <div
                    class="relative flex items-center px-4 py-3"
                    :class="{
                        'justify-center': isCollapse,
                        'justify-end': !isCollapse,
                    }"
                >
                    <Button
                        variant="secondary"
                        size="icon"
                        class="h-8 w-8 cursor-pointer transition"
                        :class="{ '-rotate-z-180': isCollapse }"
                        @click="toogleCollapse"
                    >
                        <Icon class="size-6" name="uim:angle-left" />
                    </Button>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
.menu-item .menu-item-container {
    padding-block: 8px;
    color: var(--accent-foreground);
}
.menu-item.active .menu-item-container {
    background-color: var(--primary);
    color: var(--primary-foreground);
}
.main-sidebar-enter-active,
.main-sidebar-leave-active {
    transition: 0.3s;
}

.main-sidebar-enter-from,
.main-sidebar-leave-to {
    transform: translateX(calc(var(--g-main-sidebar-width) * -1));

    [dir='rtl'] & {
        transform: translateX(var(--g-main-sidebar-width));
    }
}

.sub-sidebar-x-start-enter-active,
.sub-sidebar-x-end-enter-active,
.sub-sidebar-y-start-enter-active,
.sub-sidebar-y-end-enter-active {
    transition: 0.2s;
}

.sub-sidebar-x-start-enter-from,
.sub-sidebar-x-start-leave-active {
    opacity: 0;
    transform: translateX(30px);
}

.sub-sidebar-x-end-enter-from,
.sub-sidebar-x-end-leave-active {
    opacity: 0;
    transform: translateX(-30px);
}

.sub-sidebar-y-start-enter-from,
.sub-sidebar-y-start-leave-active {
    opacity: 0;
    transform: translateY(30px);
}

.sub-sidebar-y-end-enter-from,
.sub-sidebar-y-end-leave-active {
    opacity: 0;
    transform: translateY(-30px);
}

.sub-sidebar-x-start-leave-active,
.sub-sidebar-x-end-leave-active,
.sub-sidebar-y-start-leave-active,
.sub-sidebar-y-end-leave-active {
    position: absolute;
}

/* 次侧边栏动画 */
.sub-sidebar-enter-active,
.sub-sidebar-leave-active {
    transition: 0.3s;
}

.sub-sidebar-enter-from,
.sub-sidebar-leave-to {
    transform: translateX(calc(var(--g-sub-sidebar-width) * -1));

    [dir='rtl'] & {
        transform: translateX(var(--g-sub-sidebar-width));
    }
}
</style>
