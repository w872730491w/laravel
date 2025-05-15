import type { ComponentConfigModel } from 'lanyunit-epic-designer'

export default <ComponentConfigModel>{
    component: () => import('./index.vue'),
    defaultSchema: {
        label: '操作区',
        type: 'list-actions',
        componentProps: {
            add: true,
        },
        children: [],
    },
    editConstraints: {
        copyable: false,
        deleteable: false,
    },
    config: {
        attribute: [
            {
                type: 'switch',
                label: '是否显示',
                field: 'componentProps.add',
            },
        ],
    },
}
