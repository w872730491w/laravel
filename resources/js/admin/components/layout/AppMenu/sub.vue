<script setup lang="ts">
import { cn } from '@admin/lib/utils'
import { useTimeoutFn } from '@vueuse/core'
import ScrollArea from '../ScrollArea/index.vue'
import Item from './item.vue'
import SubMenu from './sub.vue'

const props = withDefaults(
    defineProps<{
        uniqueKey: string[]
        menus: Record<string, any>
        level?: number
    }>(),
    {
        level: 0,
    },
)

const index = props.menus.path ?? props.menus.id
const itemRef = useTemplateRef('itemRef')
const subMenuRef = useTemplateRef('subMenuRef')
const { isCollapse, openedMenus, mouseInMenu, subMenus, items, handleSubMenuClick, handleMenuItemClick, openMenu, closeMenu } = inject<{
    isCollapse: Ref<boolean>
    openedMenus: Ref<string[]>
    mouseInMenu: Ref<string[]>
    subMenus: Record<string, any>
    items: Record<string, any>
    handleSubMenuClick: (index: string) => void
    handleMenuItemClick: (index: string) => void
    openMenu: (index: string) => void
    closeMenu: (index: string | string[]) => void
}>('app-sidebar')!

const opened = computed(() => {
    return openedMenus.value.includes(props.uniqueKey.at(-1)!)
})

const hasChildren = computed(() => {
    return props.menus.children?.length > 0
})

const onClick = () => {
    if (isCollapse.value && hasChildren.value) {
        return
    }
    if (hasChildren.value) {
        handleSubMenuClick(index)
    } else {
        handleMenuItemClick(index)
    }
}

const transitionEvent = computed(() => {
    return isCollapse.value
        ? {
              enter(el: HTMLElement) {
                  if (el.offsetHeight > window.innerHeight) {
                      el.style.height = `${window.innerHeight}px`
                  }
              },
              afterEnter: () => {},
              beforeLeave: (el: HTMLElement) => {
                  el.style.maxHeight = `${el.offsetHeight}px`
                  el.style.overflow = 'hidden'
              },
              leave: (el: HTMLElement) => {
                  el.style.maxHeight = '0'
              },
              afterLeave(el: HTMLElement) {
                  el.style.maxHeight = ''
                  el.style.overflow = ''
              },
          }
        : CSS.supports('height', 'calc-size(auto, size)')
          ? {}
          : {
                enter(el: HTMLElement) {
                    requestAnimationFrame(() => {
                        el.dataset.height = el.offsetHeight.toString()
                        el.style.maxHeight = '0'
                        void el.offsetHeight
                        el.style.maxHeight = `${el.dataset.height}px`
                        el.style.overflow = 'hidden'
                    })
                },
                afterEnter(el: HTMLElement) {
                    el.style.maxHeight = ''
                    el.style.overflow = ''
                },
                enterCancelled(el: HTMLElement) {
                    el.style.maxHeight = ''
                    el.style.overflow = ''
                },
                beforeLeave(el: HTMLElement) {
                    el.style.maxHeight = `${el.offsetHeight}px`
                    el.style.overflow = 'hidden'
                },
                leave(el: HTMLElement) {
                    el.style.maxHeight = '0'
                },
                afterLeave(el: HTMLElement) {
                    el.style.maxHeight = ''
                    el.style.overflow = ''
                },
                leaveCancelled(el: HTMLElement) {
                    el.style.maxHeight = ''
                    el.style.overflow = ''
                },
            }
})

const transitionClass = computed(() => {
    return isCollapse.value
        ? {
              enterActiveClass: 'ease-in-out duration-300',
              enterFromClass: 'opacity-0 translate-x-4',
              enterToClass: 'opacity-100',
              leaveActiveClass: 'ease-in-out duration-300',
              leaveFromClass: 'opacity-100',
              leaveToClass: 'opacity-0',
          }
        : {
              enterActiveClass: 'ease-in-out duration-300',
              enterFromClass: cn('translate-y-4 scale-95 opacity-0 blur-[4px]', CSS.supports('height', 'calc-size(auto, size)') && 'h-0'),
              enterToClass: 'opacity-100 translate-y-0 scale-100 blur-[0px]',
              leaveActiveClass: 'ease-in-out duration-300',
              leaveFromClass: 'opacity-100 translate-y-0 scale-100 blur-[0px]',
              leaveToClass: cn('translate-y-4 scale-95 opacity-0 blur-[4px]', CSS.supports('height', 'calc-size(auto, size)') && 'h-0'),
          }
})

let timeout: (() => void) | undefined

function handleMouseenter() {
    if (!isCollapse.value) {
        return
    }
    mouseInMenu.value = props.uniqueKey
    timeout?.()
    ;({ stop: timeout } = useTimeoutFn(() => {
        if (hasChildren.value) {
            openMenu(index)
            nextTick(() => {
                const el = itemRef.value?.ref
                const subMenuEl = subMenuRef.value?.$el
                if (!el || !subMenuEl) {
                    return
                }
                let top = 0
                let left = 0
                if (props.level !== 0) {
                    top = el.getBoundingClientRect().top + el.scrollTop
                    top -= 4 + 1 // 4px是菜单项的padding，1px是菜单项的border，目的是让菜单与上级菜单视觉上对齐
                    left = el.getBoundingClientRect().left + el.getBoundingClientRect().width
                    left += 4
                    if (top + subMenuEl.offsetHeight > window.innerHeight) {
                        top = Math.max(0, window.innerHeight - subMenuEl.offsetHeight)
                    }
                } else {
                    top = el.getBoundingClientRect().top
                    top -= 3
                    left = el.getBoundingClientRect().left + el.getBoundingClientRect().width
                    left += 5
                    if (top + subMenuEl.offsetHeight > window.innerHeight) {
                        subMenuEl.style.height = `${window.innerHeight - top}px`
                    }
                }
                if (left + subMenuEl.offsetWidth > document.documentElement.clientWidth) {
                    left = el.getBoundingClientRect().left - el.getBoundingClientRect().width
                }
                subMenuEl.style.top = `${top}px`
                subMenuEl.style.insetInlineStart = `${left}px`
            })
        } else {
            const path = props.menus.children ? subMenus.value[index].indexPath.at(-1)! : items.value[index].indexPath.at(-1)!
            openMenu(path)
        }
    }, 300))
}

function handleMouseleave() {
    if (!isCollapse.value) {
        return
    }
    mouseInMenu.value = []
    timeout?.()
    ;({ stop: timeout } = useTimeoutFn(() => {
        if (mouseInMenu.value.length === 0) {
            closeMenu(props.uniqueKey)
        } else {
            if (hasChildren.value) {
                !mouseInMenu.value.includes(props.uniqueKey.at(-1)!) && closeMenu(props.uniqueKey.at(-1)!)
            }
        }
    }, 300))
}
</script>

<template>
    <Item
        ref="itemRef"
        :unique-key="uniqueKey"
        :expand="opened"
        :item="menus"
        :level="level"
        :sub-menu="hasChildren"
        @click="onClick"
        @mouseenter="handleMouseenter"
        @mouseleave="handleMouseleave"
    />
    <Teleport v-if="hasChildren" to="body" :disabled="!isCollapse">
        <Transition v-bind="transitionClass" v-on="transitionEvent">
            <ScrollArea
                v-if="opened"
                ref="subMenuRef"
                :scrollbar="false"
                :mask="isCollapse"
                :class="
                    cn('sub-menu static h-[calc-size(auto,size)] rounded-lg', {
                        'bg-background': isCollapse,
                        'fixed z-3000 w-[200px] border shadow-xl': isCollapse,
                        'py-1': isCollapse,
                    })
                "
            >
                <template v-for="item in menus.children" :key="item.id">
                    <SubMenu :unique-key="[...uniqueKey, item.path ?? item.id]" :menus="item" :level="level + 1" />
                </template>
            </ScrollArea>
        </Transition>
    </Teleport>
</template>
