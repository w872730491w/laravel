import { type ComponentConfigModel } from 'lanyunit-epic-designer'

export default <ComponentConfigModel>{
    component: () => import('./index.vue'),
    defaultSchema: {
        label: '操作区',
        type: 'list-actions',
        componentProps: {},
        children: [],
    },
    editConstraints: {
        copyable: false,
        deleteable: false,
        childCopyable: false,
        childDeleteable: false,
        childImmovable: true,
    },
    config: {
        attribute: [
            {
                type: 'ButtonList',
                label: '自定义按钮',
                field: 'children',
            },
        ],
    },
}
