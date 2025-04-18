<script setup lang="ts">
import Icon from '@admin/components/Icon/index.vue'
import { cn } from '@admin/lib/utils'
import { Link } from '@inertiajs/vue3'

const props = withDefaults(
    defineProps<{
        uniqueKey: string[]
        item: Record<string, any>
        level?: number
        expand?: boolean
        subMenu?: boolean
    }>(),
    {
        level: 0,
        expand: false,
        subMenu: false,
    },
)

const { isCollapse, subMenus, activeIndex } = inject<{
    isCollapse: Ref<boolean>
    subMenus: Record<string, any>
    activeIndex: Ref<string>
}>('app-sidebar')!

const isActived = computed(() => {
    return props.subMenu ? subMenus[props.uniqueKey.at(-1)!]?.active : activeIndex.value === props.uniqueKey.at(-1)!
})

const isItemActive = computed(() => {
    return isActived.value && (!props.subMenu || isCollapse.value)
})

const itemRef = ref<HTMLElement>()
defineExpose({
    ref: itemRef,
})
</script>

<template>
    <div
        ref="itemRef"
        :class="
            cn('relative px-2 py-1 transition-all', {
                active: isItemActive,
            })
        "
    >
        <component
            :is="subMenu ? 'div' : Link"
            v-bind="
                subMenu
                    ? {}
                    : {
                          href: item.path,
                      }
            "
            :class="
                cn('group menu-item-container relative flex h-full w-full items-center justify-between gap-1 rounded-lg px-4 py-3', {
                    'hover:bg-muted hover:text-accent-foreground text-accent-foreground cursor-pointer transition-all': true,
                    'bg-primary! text-primary-foreground!': isItemActive,
                    'px-3': isCollapse && level === 0,
                    'py-3': isCollapse && level !== 0,
                })
            "
        >
            <div
                :class="
                    cn('inline-flex flex-1 items-center justify-center gap-[12px] ps-[calc(var(--indent-level)*20px)]', {
                        'flex-col': isCollapse && level === 0,
                    })
                "
                :style="{
                    '--indent-level': !isCollapse ? (level ?? 0) : 0,
                }"
            >
                <Icon
                    :name="item.icon ?? 'uim:align-left'"
                    :class="
                        cn('menu-item-container-icon size-5', {
                            'group-hover-scale-120 transition-transform': isCollapse,
                        })
                    "
                />
                <span
                    v-if="!(isCollapse && level === 0)"
                    :class="
                        cn('w-0 flex-1 truncate text-sm transition-all', {
                            'h-0 w-0 opacity-0': isCollapse && level === 0,
                        })
                    "
                >
                    {{ item.title }}
                </span>
            </div>
            <i
                v-if="subMenu && (!isCollapse || level !== 0)"
                :class="
                    cn(
                        'relative ms-1 w-[10px] before:absolute before:h-[1.5px] before:w-[6px] before:-translate-y-[1px] before:bg-current before:transition-transform before:duration-200 before:content-[\'\'] after:absolute after:h-[1.5px] after:w-[6px] after:-translate-y-[1px] after:bg-current after:transition-transform after:duration-200 after:content-[\'\']',
                        {
                            [expand
                                ? 'before:-translate-x-[2px] before:-rotate-45 after:translate-x-[2px] after:rotate-45'
                                : 'before:-translate-x-[2px] before:rotate-45 after:translate-x-[2px] after:-rotate-45']: true,
                            'opacity-0': isCollapse && level === 0,
                            '-rotate-90 ltr:-top-[1.5px] rtl:top-[1.5px]': isCollapse && level !== 0,
                        },
                    )
                "
            />
        </component>
    </div>
</template>
