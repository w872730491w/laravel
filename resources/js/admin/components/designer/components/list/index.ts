import type { ComponentConfigModel } from 'lanyunit-epic-designer'

export default <ComponentConfigModel>{
    component: async () => await import('./index.vue'),
    groupName: '列表',
    icon: 'icon--epic--code',
    defaultSchema: {
        label: '列表',
        type: 'list',
        componentProps: {
            api: null,
            columns: [],
            selection: false,
            selectionMultiple: false,
        },
        children: [
            {
                type: 'list-search',
                children: [],
                componentProps: {},
                show: true,
            },
            {
                type: 'list-actions',
                children: [],
                componentProps: {},
                show: true,
            },
        ],
    },
    editConstraints: {
        copyable: false,
        childImmovable: true,
    },
    config: {
        attribute: [
            {
                label: '接口地址',
                type: 'RouteSelect',
                field: 'componentProps.api',
            },
            {
                label: '显示搜索区',
                type: 'switch',
                field: 'children.0.show',
            },
            {
                label: '显示操作栏',
                type: 'switch',
                field: 'children.1.show',
            },
            {
                label: '列表选择',
                type: 'switch',
                field: 'componentProps.selection',
            },
            {
                label: '列表多选',
                type: 'switch',
                show: ({ values }: any) => {
                    return values.componentProps.selection
                },
                field: 'componentProps.selectionMultiple',
            },
            {
                label: '展示的列',
                type: 'TableColumnEditor',
                field: 'componentProps.columns',
                layout: 'vertical',
                onChange: ({ values, value }: any) => {
                    values.children[0].children = value
                        .filter((v: any) => {
                            return v.showSearch
                        })
                        .map((v: any) => {
                            return {
                                id: 'list-search-' + v.key,
                                type: v.searchComponent,
                                field: v.key,
                                componentProps: {
                                    placeholder: `搜索${v.title}`,
                                },
                                editConstraints: {
                                    copyable: false,
                                    deleteable: false,
                                },
                            }
                        })
                },
            },
        ],
    },
}
