<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { store } from '@/routes/register';

defineProps<{
    passwordRules: string;
}>();

defineOptions({
    layout: {
        title: 'Create your account',
        description: 'Set up access to the coaching management portal.',
    },
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

function submit(): void {
    form.post(store.url(), {
        onSuccess: () => form.reset('password', 'password_confirmation'),
    });
}
</script>

<template>
    <Head title="Register" />

    <section class="rounded-2xl border border-sidebar-border/70 bg-card p-6 shadow-xl shadow-black/5 sm:p-8 dark:border-sidebar-border">
        <form class="flex flex-col gap-5" @submit.prevent="submit">
            <div class="grid gap-2">
                <Label for="name" class="text-sm font-medium">Full name</Label>
                <Input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="name"
                    placeholder="Your full name"
                    class="h-11 rounded-lg border-sidebar-border/80 bg-background px-4 shadow-sm transition focus-visible:ring-2 focus-visible:ring-sidebar-primary/40"
                />
                <InputError :message="form.errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="email" class="text-sm font-medium">Email address</Label>
                <Input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    :tabindex="2"
                    autocomplete="email"
                    placeholder="name@example.com"
                    class="h-11 rounded-lg border-sidebar-border/80 bg-background px-4 shadow-sm transition focus-visible:ring-2 focus-visible:ring-sidebar-primary/40"
                />
                <InputError :message="form.errors.email" />
            </div>

            <div class="grid gap-2">
                <Label for="password" class="text-sm font-medium">Password</Label>
                <PasswordInput
                    id="password"
                    v-model="form.password"
                    required
                    :tabindex="3"
                    autocomplete="new-password"
                    placeholder="Create a password"
                    :passwordrules="passwordRules"
                    class="h-11 rounded-lg border-sidebar-border/80 bg-background px-4 shadow-sm transition focus-visible:ring-2 focus-visible:ring-sidebar-primary/40"
                />
                <InputError :message="form.errors.password" />
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation" class="text-sm font-medium">Confirm password</Label>
                <PasswordInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    required
                    :tabindex="4"
                    autocomplete="new-password"
                    placeholder="Confirm your password"
                    :passwordrules="passwordRules"
                    class="h-11 rounded-lg border-sidebar-border/80 bg-background px-4 shadow-sm transition focus-visible:ring-2 focus-visible:ring-sidebar-primary/40"
                />
                <InputError :message="form.errors.password_confirmation" />
            </div>

            <Button
                type="submit"
                class="mt-1 h-11 w-full rounded-lg text-base font-semibold shadow-sm transition disabled:cursor-not-allowed disabled:opacity-70"
                tabindex="5"
                :disabled="form.processing"
                data-test="register-user-button"
            >
                <Spinner v-if="form.processing" />
                <span>{{ form.processing ? 'Creating account...' : 'Create account' }}</span>
            </Button>
        </form>

        <p class="mt-6 text-center text-sm text-muted-foreground">
            Already have an account?
            <TextLink :href="login()" :tabindex="6" class="font-medium">Log in</TextLink>
        </p>
    </section>
</template>
