import type { ComponentConfigModel } from 'lanyunit-epic-designer'

export default <ComponentConfigModel>{
    component: async () => await import('./index.vue'),
    defaultSchema: {
        label: '搜索区',
        type: 'list-search',
        componentProps: {},
    },
    editConstraints: {
        copyable: false,
        deleteable: false,
        childImmovable: true,
        childDeleteable: false,
        childCopyable: false,
    },
    config: {
        attribute: [],
    },
}
