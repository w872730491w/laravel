import type { ComponentSchema } from 'lanyunit-epic-designer'

import type { PropType } from 'vue'

import { defineComponent, h, renderSlot } from 'vue'

import { NButton } from 'naive-ui'

import Icon from '@admin/components/Icon/index.vue'

// 二次封装组件
export default defineComponent({
    props: {
        componentSchema: {
            default: () => ({}),
            type: Object as PropType<ComponentSchema>,
        },
    },
    setup(props, { slots }) {
        const renderIcon = () => {
            if (props.componentSchema?.icon) {
                return h(Icon, { name: props.componentSchema?.icon })
            }
            return null
        }

        return () => {
            const componentProps: Record<string, any> = {
                ...props.componentSchema?.componentProps,
            }

            return h(NButton, componentProps, {
                default: () => renderSlot(slots, 'default', {}, () => [props.componentSchema?.label]),
                icon: () => renderIcon(),
            })
        }
    },
})
