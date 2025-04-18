<script setup lang="ts">
import ScrollArea from '@admin/components/layout/ScrollArea/index.vue'
import Icon from '@admin/components/Icon/index.vue'
import { usePage } from '@inertiajs/vue3'
import Sortable from 'sortablejs'

const page = usePage()

const { menus } = inject<{
    menus: Record<string, any>[]
}>('app-sidebar')!

const list = ref<any[]>([])
const leaveIndex = ref(-1)
const activedTabId = computed(() => page.props.ziggy.location)

const tabsRef = useTemplateRef('tabsRef')
const tabRef = useTemplateRef<HTMLElement[]>('tabRef')

watch(
    () => page.props.ziggy.location,
    (v) => {
        const tabId = v
        // 记录查找到的标签页
        const findTab = list.value.find((item) => {
            return item.tabId === tabId
        })
        if (!findTab) {
            const findRoute = (path: string, menus: any[]): any => {
                for (const menu of menus) {
                    if (menu.path === path) {
                        return menu
                    }
                    if (menu.children?.length) {
                        const find = findRoute(path, menu.children)
                        if (find) {
                            return find
                        }
                    }
                }
                return null
            }
            const route = findRoute(v, menus)
            if (!route) {
                return
            }
            const listItem = {
                tabId,
                fullPath: v,
                title: route.title,
                iframe: route.iframe,
                icon: route.icon,
                activeIcon: route.activeIcon,
            }
            if (leaveIndex.value >= 0) {
                list.value.splice(leaveIndex.value + 1, 0, listItem)
                leaveIndex.value = -1
            } else {
                list.value.push(listItem)
            }
        }
        const index = list.value.findIndex((item) => item.tabId === activedTabId.value)
        if (index !== -1) {
            tabRef.value && tabsRef.value?.scrollTo({ left: tabRef.value[index].offsetLeft - tabsRef.value.ref?.$el.clientWidth * 0.4 })
        }
    },
    {
        immediate: true,
    },
)

const tabContainerRef = useTemplateRef('tabContainerRef')

const isDragging = ref(false)
let tabSortable: Sortable

onMounted(() => {
    tabSortable = new Sortable(tabContainerRef.value?.$el, {
        animation: 200,
        ghostClass: 'opacity-0',
        draggable: '.tab',
        handle: '.drag-handle',
        onStart: () => {
            isDragging.value = true
        },
        onEnd: () => {
            isDragging.value = false
        },
        onUpdate: (e) => {
            if (e.newIndex !== undefined && e.oldIndex !== undefined) {
                list.value.splice(e.newIndex, 0, list.value.splice(e.oldIndex, 1)[0])
            }
        },
    })
})
</script>

<template>
    <div class="tabbar relative flex h-(--tabbar-height) items-center bg-(--main-area-bg) transition-colors">
        <div class="relative h-full flex-1">
            <ScrollArea ref="tabsRef" horizontal :scrollbar="false" mask class="absolute inset-x-0 bottom-0 whitespace-nowrap">
                <TransitionGroup
                    ref="tabContainerRef"
                    :name="!isDragging ? 'tabbar' : undefined"
                    tag="div"
                    class="relative inline-block"
                    :class="{ dragging: isDragging }"
                >
                    <div v-for="item in list" :key="item.tabId" ref="tabRef" class="tab group" :class="{ active: activedTabId === item.tabId }">
                        <div class="pointer-events-auto size-full select-none">
                            <div class="tab-divider"></div>
                            <div class="tab-background"></div>
                            <div class="pointer-events-[all] flex size-full">
                                <div class="title">
                                    <Icon v-if="item.icon" name="uim:box" class="flex-shrink-0" />
                                    <span>{{ item.title }}</span>
                                </div>
                                <div class="action-icon">
                                    <Icon name="ri:close-fill" />
                                </div>
                                <div class="absolute inset-0 z-10"></div>
                            </div>
                        </div>
                    </div>
                </TransitionGroup>
            </ScrollArea>
        </div>
    </div>
</template>

<style scoped>
.tabbar {
    .tab {
        &:last-child {
            margin-inline-end: 0;
        }
        &:first-child {
            .tab-divider {
                opacity: 0;
            }
        }
        &.active {
            z-index: 5;
            .tab-background {
                background-color: var(--background);
                &::before,
                &::after {
                    background-color: var(--background);
                }
            }
            .title {
                color: var(--foreground);
            }
        }
        &:not(.active):hover {
            z-index: 3;
            .tab-background {
                background-color: var(--border);
                &::before,
                &::after {
                    background-color: var(--border);
                }
            }
            .title {
                color: hsl(var(--accent-foreground) / 50%);
            }
        }
        .title {
            display: flex;
            flex: 1;
            gap: 5px;
            align-items: center;
            height: 100%;
            padding: 0 10px;
            margin-inline-end: 10px;
            overflow: hidden;
            color: hsl(var(--accent-foreground) / 50%);
            white-space: nowrap;
            mask-image: linear-gradient(to right, #000 calc(100% - 20px), transparent);
            transition: margin-inline-end 0.3s;
            &:has(+ .action-icon) {
                margin-inline-end: 28px;
            }
        }
        .action-icon {
            position: absolute;
            inset-inline-end: 0.5rem;
            top: 50%;
            z-index: 10;
            width: 1.25rem;
            height: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: translateY(-50%);
            border-radius: 9999px;
            font-size: 0.75rem;
            line-height: 1rem;
            color: hsl(var(--accent-foreground) / 50%);
            transition-property:
                color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 0.15s;
        }
        position: relative;
        display: inline-block;
        min-width: var(--tabbar-tab-min-width);
        max-width: var(--tabbar-tab-max-width);
        font-size: 14px;
        vertical-align: bottom;
        pointer-events: none;
        cursor: pointer;
        height: calc(var(--tabbar-height) - 6px);
        margin-inline: 6px -6px;
        line-height: calc(var(--tabbar-height) - 8px);
    }
    .tab-divider {
        position: absolute;
        inset-inline: -1px;
        top: 50%;
        z-index: 0;
        height: 14px;
        transform: translateY(-50%);
        &::before {
            position: absolute;
            inset-inline-start: 1px;
            top: 0;
            bottom: 0;
            display: block;
            width: 1px;
            content: '';
            background-color: var(--tabbar-dividers-bg);
            opacity: 1;
            transition:
                opacity 0.3s,
                background-color 0.3s;
        }
    }
    .tab-background {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        transition:
            opacity 0.3s,
            background-color 0.3s;
        border-radius: 10px 10px 0 0;
        &::before,
        &::after {
            position: absolute;
            bottom: 0;
            width: 10px;
            height: 10px;
            content: '';
            background-color: transparent;
            mask-size: 10px;
            transition: background-color 0.3s;
        }
        &::before {
            left: -10px;
            mask-image: url('/static/admin/images/tab-left.svg');
        }
        &::after {
            right: -10px;
            mask-image: url('/static/admin/images/tab-right.svg');
        }
    }
}
/* 标签栏动画 */
.tabs {
    .tabbar-move,
    .tabbar-enter-active,
    .tabbar-leave-active {
        transition: all 0.3s;
    }

    .tabbar-enter-from,
    .tabbar-leave-to {
        opacity: 0;
        transform: translateY(30px);
    }

    &.tabs-ontop {
        .tabbar-enter-from,
        .tabbar-leave-to {
            opacity: 0;
            transform: translateY(-30px);
        }
    }

    .tabbar-leave-active {
        position: absolute !important;
    }
}
</style>
