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

// 定义权限项接口
interface PermissionItem {
    id: number
    pid: number
    type: number
    route: string
    name: string
    display_name: string
    icon: string
    guard_name: string
    roles: any[]
}

// 构建菜单树的函数
function buildMenuTree(permissions: PermissionItem[]) {
    // 创建一个映射表，用于快速查找每个节点
    const map: Record<number, any> = {}
    const result: any[] = []

    // 先将所有节点放入映射表
    permissions.forEach((permission) => {
        map[permission.id] = {
            id: permission.id,
            title: permission.display_name,
            icon: permission.icon,
            path: permission.route ? route(permission.route) : undefined,
            name: permission.name,
            children: [],
        }
    })

    // 构建树结构
    permissions.forEach((permission) => {
        const node = map[permission.id]
        if (permission.pid === 0) {
            // 根节点直接放入结果数组
            result.push(node)
        } else {
            // 子节点放入父节点的children数组
            if (map[permission.pid]) {
                map[permission.pid].children.push(node)
            }
        }
    })

    // 清理空的children数组
    const cleanEmptyChildren = (nodes: any[]) => {
        nodes.forEach((node) => {
            if (node.children.length === 0) {
                delete node.children
            } else {
                cleanEmptyChildren(node.children)
            }
        })
    }

    cleanEmptyChildren(result)
    return result
}

const activeSidebarIndex = ref(0)

// 获取用户权限并构建菜单
const allMenus = computed(() => {
    if (!page.props.user || !page.props.user.permissions) {
        return []
    }
    return buildMenuTree(page.props.user.permissions as PermissionItem[])
})

const menus = computed(() => {
    return allMenus.value[activeSidebarIndex.value].children
})

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
    initItems(menus.value)
    initMenu()
})

watch(
    menus,
    () => {
        items.value = {}
        subMenus.value = {}
        initItems(menus.value)
        initMenu()
    },
    { deep: true },
)

const closeMenu = (index: string | string[]) => {
    if (Array.isArray(index)) {
        nextTick(() => {
            const lastItem = index[index.length - 1]
            closeMenu(lastItem)
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
    const menusValue = menus.value
    for (const key in menusValue) {
        if (menusValue[key]) {
            menusValue[key].active = false
        }
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
    () => page.props.ziggy.location,
    (currentValue) => {
        if (!items.value[currentValue]) {
            activeIndex.value = ''
        }
        const item =
            items.value[currentValue] || (activeIndex.value && items.value[activeIndex.value]) || items.value[currentValue]
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
    allMenus,
    activeSidebarIndex,
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
