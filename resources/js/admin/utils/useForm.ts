export type FormType = 'add' | 'edit' | 'detail'

export interface FormOptions {
    type: Ref<FormType>
    show: Ref<boolean>
    form: Ref<Record<string, any>>
    URLParams: Ref<Record<string, any>>
}

const key = Symbol('form-modal') as InjectionKey<FormOptions>

export const useForm = () => {
    const type = ref<FormType>('add')
    const show = ref(false)
    const URLParams = ref({})
    const form = ref({})
    const open = (showType: FormType, params: Record<string, any> = {}) => {
        type.value = showType
        URLParams.value = params
        show.value = true
    }
    provide(key, {
        show,
        form,
        type,
        URLParams,
    })
    return {
        open,
    }
}

export const useFormInject = () => {
    return inject(key)!
}
