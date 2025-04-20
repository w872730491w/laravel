<script setup lang="ts">
import { Button } from '@admin/components/ui/button'
import { Checkbox } from '@admin/components/ui/checkbox'
import { FormControl, FormField, FormItem, FormLabel, FormMessage } from '@admin/components/ui/form'
import { Input } from '@admin/components/ui/input'
import { Label } from '@admin/components/ui/label'
import { ParticlesBg } from '@admin/components/ui/particles-bg'
import { router, usePage } from '@inertiajs/vue3'
import { toTypedSchema } from '@vee-validate/zod'
import { Motion } from 'motion-v'
import { useForm } from 'vee-validate'
import * as z from 'zod'

defineOptions({
    layout: false,
})

const { props } = usePage()

const { handleSubmit, controlledValues, setErrors } = useForm({
    validationSchema: toTypedSchema(
        z.object({
            username: z.string().min(1, '请输入用户名'),
            password: z.string().min(1, '请输入密码'),
            remember: z.boolean(),
        }),
    ),
    initialValues: {
        username: '',
        password: '',
        remember: false,
    },
})

const onSubmit = handleSubmit((values) => {
    router.post(route('admin.login'), values, {
        onError(errors) {
            setErrors(errors)
        },
    })
})
</script>

<template>
    <div
        class="bg-secondary relative flex h-svh w-full flex-col items-center justify-center overflow-hidden rounded-lg border md:shadow-xl">
        <Motion as="div" :initial="{ opacity: 0, y: 40, filter: 'blur(10px)' }" :in-view="{
            opacity: 1,
            y: 0,
            filter: 'blur(0px)',
        }" :transition="{
                duration: 0.4,
                ease: 'easeInOut',
            }" class="bg-background z-20 w-[450px] space-y-8 rounded-(--radius) p-8 shadow-lg">
            <div class="text-center text-xl font-medium">
                {{ props.name }}
            </div>
            <form @submit="onSubmit">
                <FormField v-slot="{ componentField, errors }" name="username">
                    <FormItem class="relative space-y-0 pb-6">
                        <FormLabel> 用户名 </FormLabel>
                        <FormControl>
                            <Input v-bind="componentField" autocomplete="username" placeholder="请输入用户名" />
                        </FormControl>
                        <Transition enter-active-class="transition-opacity" enter-from-class="opacity-0"
                            leave-active-class="transition-opacity" leave-to-class="opacity-0">
                            <FormMessage class="absolute bottom-1 text-xs" />
                        </Transition>
                    </FormItem>
                </FormField>
                <FormField v-slot="{ componentField, errors }" name="password">
                    <FormItem class="relative space-y-0 pb-6">
                        <FormLabel> 密码 </FormLabel>
                        <FormControl>
                            <Input v-bind="componentField" type="password" autocomplete="current-password"
                                placeholder="请输入密码" />
                        </FormControl>
                        <Transition enter-active-class="transition-opacity" enter-from-class="opacity-0"
                            leave-active-class="transition-opacity" leave-to-class="opacity-0">
                            <FormMessage class="absolute bottom-1 text-xs" />
                        </Transition>
                    </FormItem>
                </FormField>
                <FormItem class="relative space-y-0 pb-6">
                    <div class="flex items-center justify-between">
                        <Label for="remember" class="flex cursor-pointer items-center">
                            <Checkbox id="remember" v-model="controlledValues.remember" />
                            <span>记住我</span>
                        </Label>
                    </div>
                </FormItem>

                <Button type="submit" class="block w-full" size="lg"> 登录 </Button>
            </form>
        </Motion>
        <ParticlesBg class="absolute inset-0" :quantity="100" :ease="70" :color="'#000'" :staticity="10" refresh />
    </div>
</template>
