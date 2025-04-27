export type FormType = 'add' | 'edit'

export interface FormOptions {
    type: Ref<FormType>
    show: Ref<boolean>
    form: Ref<Record<string, any>>
}

const key = Symbol('form-modal') as InjectionKey<FormOptions>

export const useForm = () => {
    const type = ref<FormType>('add')
    const show = ref(false)
    const form = ref()
    const open = (showType: FormType) => {
        type.value = showType
        show.value = true
    }
    provide(key, {
        show,
        form,
        type,
    })
    return {
        open,
    }
}

export const useFormInject = () => {
    return inject(key)!
}
