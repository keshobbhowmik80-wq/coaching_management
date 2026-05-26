<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasskeyVerify from '@/components/PasskeyVerify.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Welcome back',
        description: 'Sign in to continue to your coaching dashboard.',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

function submit(): void {
    form.post(store.url(), {
        onSuccess: () => form.reset('password'),
    });
}
</script>

<template>
    <Head title="Log in" />

    <section class="rounded-2xl border border-sidebar-border/70 bg-card p-6 shadow-xl shadow-black/5 sm:p-8 dark:border-sidebar-border">
        <div v-if="status" class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-300">
            {{ status }}
        </div>

        <PasskeyVerify />

        <form class="mt-6 flex flex-col gap-5" @submit.prevent="submit">
            <div class="grid gap-2">
                <Label for="email" class="text-sm font-medium">Email address</Label>
                <Input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="email"
                    placeholder="name@example.com"
                    class="h-11 rounded-lg border-sidebar-border/80 bg-background px-4 shadow-sm transition focus-visible:ring-2 focus-visible:ring-sidebar-primary/40"
                />
                <InputError :message="form.errors.email" />
            </div>

            <div class="grid gap-2">
                <div class="flex items-center justify-between gap-3">
                    <Label for="password" class="text-sm font-medium">Password</Label>
                    <TextLink v-if="canResetPassword" :href="request()" class="text-sm font-medium" :tabindex="5">
                        Forgot password?
                    </TextLink>
                </div>
                <PasswordInput
                    id="password"
                    v-model="form.password"
                    required
                    :tabindex="2"
                    autocomplete="current-password"
                    placeholder="Enter your password"
                    class="h-11 rounded-lg border-sidebar-border/80 bg-background px-4 shadow-sm transition focus-visible:ring-2 focus-visible:ring-sidebar-primary/40"
                />
                <InputError :message="form.errors.password" />
            </div>

            <label class="flex items-center gap-3 text-sm text-muted-foreground">
                <input
                    v-model="form.remember"
                    type="checkbox"
                    class="size-4 rounded border-sidebar-border text-sidebar-primary focus:ring-2 focus:ring-sidebar-primary/40"
                    :tabindex="3"
                />
                <span>Remember me on this device</span>
            </label>

            <Button
                type="submit"
                class="mt-1 h-11 w-full rounded-lg text-base font-semibold shadow-sm transition disabled:cursor-not-allowed disabled:opacity-70"
                :tabindex="4"
                :disabled="form.processing"
                data-test="login-button"
            >
                <Spinner v-if="form.processing" />
                <span>{{ form.processing ? 'Signing in...' : 'Log in' }}</span>
            </Button>
        </form>

        <p class="mt-6 text-center text-sm text-muted-foreground">
            Don't have an account?
            <TextLink :href="register()" :tabindex="6" class="font-medium">Create one</TextLink>
        </p>
    </section>
</template>
