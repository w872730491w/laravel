<script setup lang="ts">
import { usePage } from '@inertiajs/vue3'
import type { GlobalThemeOverrides } from 'naive-ui'
import AppSidebar from './AppSidebar.vue'
import AppTopbar from './Topbar/index.vue'

const themeOverrides: GlobalThemeOverrides = {
    common: {
        primaryColor: '#000000',
    },
}

const page = usePage()

const isCollapse = ref(false)

const menus: Record<string, any>[] = [
    {
        id: 3,
        title: '监控',
        path: route('telescope'),
    },
    {
        id: 1,
        title: '配置相关',
        children: [
            {
                id: 2,
                title: '菜单管理',
                children: [
                    {
                        id: 4,
                        title: '菜单管理',
                        path: '/a',
                    },
                ],
            },
            {
                id: 5,
                title: '菜单管理',
                children: [
                    {
                        id: 6,
                        title: '菜单管理',
                        path: route('admin.home'),
                    },
                    {
                        id: 7,
                        title: '菜单管理',
                        path: '/c',
                    },
                ],
            },
        ],
    },
]

interface MenuItem {
    index: string
    indexPath: string[]
    active?: boolean
}

const activeIndex = ref<string>(page.props.ziggy.location)
const items = ref<Record<string, MenuItem>>({})
const subMenus = ref<Record<string, MenuItem>>({})
const openedMenus = ref<string[]>([])
const mouseInMenu = ref<string[]>([])

// 解析传入的 menu 数据，并保存到 items 和 subMenus 对象中
function initItems(menu: Record<string, any>[], parentPaths: string[] = []) {
    menu.forEach((item) => {
        const index = item.path ?? item.id
        if (item.children) {
            const indexPath = [...parentPaths, index]
            subMenus.value[index] = {
                index,
                indexPath,
                active: false,
            }
            initItems(item.children, indexPath)
        } else {
            items.value[index] = {
                index,
                indexPath: parentPaths,
            }
        }
    })
}

function initMenu() {
    const activeItem = activeIndex.value && items.value[activeIndex.value]
    setSubMenusActive(activeIndex.value)
    if (!activeItem || isCollapse.value) {
        return
    }
    // 展开该菜单项的路径上所有子菜单
    activeItem.indexPath.forEach((index) => {
        const subMenu = subMenus.value[index]
        subMenu && openMenu(index)
    })
}

onMounted(() => {
    initItems(menus)
    initMenu()
})

const closeMenu = (index: string | string[]) => {
    if (Array.isArray(index)) {
        nextTick(() => {
            closeMenu(index.at(-1)!)
            if (index.length > 1) {
                closeMenu(index.slice(0, -1))
            }
        })
        return
    }
    openedMenus.value = openedMenus.value.filter((item) => item !== index)
}

const openMenu = (index: string) => {
    if (openedMenus.value.includes(index)) {
        return
    }
    openedMenus.value.push(index)
}

const handleSubMenuClick = (index: string) => {
    if (openedMenus.value.includes(index)) {
        closeMenu(index)
    } else {
        openMenu(index)
    }
}

function setSubMenusActive(index: string) {
    for (const key in menus) {
        menus[key].active = false
    }
    subMenus.value[index]?.indexPath.forEach((idx) => {
        subMenus.value[idx].active = true
    })
    items.value[index]?.indexPath.forEach((idx) => {
        subMenus.value[idx].active = true
    })
}

const handleMenuItemClick = (index: string) => {
    if (isCollapse.value) {
        openedMenus.value = []
    }
    setSubMenusActive(index)
}

watch(
    () => page.url,
    (currentValue) => {
        if (!items.value[currentValue]) {
            activeIndex.value = ''
        }
        const item = items.value[currentValue] || (activeIndex.value && items.value[activeIndex.value]) || items.value[currentValue]
        if (item) {
            activeIndex.value = item.index
        } else {
            activeIndex.value = currentValue
        }
        initMenu()
    },
)

watch(isCollapse, () => {
    openedMenus.value = []
    initMenu()
})

provide('app-sidebar', {
    menus,
    isCollapse,
    openedMenus,
    subMenus,
    activeIndex,
    mouseInMenu,
    items,
    handleSubMenuClick,
    handleMenuItemClick,
    openMenu,
    closeMenu,
})
</script>

<template>
    <n-config-provider :theme-overrides="themeOverrides">
        <div class="flex h-svh w-svw">
            <AppSidebar />
            <main class="bg-secondary flex h-full flex-1 flex-col overflow-hidden">
                <AppTopbar />
                <div class="flex-1 overflow-x-hidden overflow-y-auto">
                    <slot />
                </div>
            </main>
        </div>
    </n-config-provider>
</template>
