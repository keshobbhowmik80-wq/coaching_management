<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import Pagination from '@/components/Pagination.vue';

type Option = {
    label: string;
    value: string | number | null;
};

type Field = {
    key: string;
    label: string;
    type?: 'text' | 'email' | 'password' | 'number' | 'date' | 'time' | 'textarea' | 'select';
    options?: Option[];
    required?: boolean;
};

type Column = {
    key: string;
    label: string;
};

const props = defineProps<{
    title: string;
    description?: string;
    items: {
        data: Record<string, any>[];
        links?: Array<{ url: string | null; label: string; active: boolean }>;
        meta?: Record<string, unknown>;
    };
    columns: Column[];
    fields: Field[];
    endpoint: string;
}>();

const editing = ref<Record<string, any> | null>(null);

const defaults = computed(() =>
    props.fields.reduce<Record<string, any>>((values, field) => {
        values[field.key] = '';

        return values;
    }, {}),
);

const form = useForm<Record<string, any>>({ ...defaults.value });

function valueFor(row: Record<string, any>, key: string): string {
    const value = key.split('.').reduce<any>((current, segment) => current?.[segment] ?? null, row);

    return value === null || value === undefined || value === '' ? '—' : String(value);
}

function directValue(row: Record<string, any>, key: string): any {
    return key.split('.').reduce<any>((current, segment) => current?.[segment] ?? '', row);
}

function resetForm(): void {
    editing.value = null;
    form.clearErrors();

    for (const field of props.fields) {
        form[field.key] = '';
    }
}

function edit(row: Record<string, any>): void {
    editing.value = row;
    form.clearErrors();

    for (const field of props.fields) {
        form[field.key] = directValue(row, field.key);
    }
}

function submit(): void {
    if (editing.value) {
        form.patch(`${props.endpoint}/${editing.value.id}`, {
            preserveScroll: true,
            onSuccess: resetForm,
        });

        return;
    }

    form.post(props.endpoint, {
        preserveScroll: true,
        onSuccess: resetForm,
    });
}

function destroy(row: Record<string, any>): void {
    router.delete(`${props.endpoint}/${row.id}`, {
        preserveScroll: true,
    });
}
</script>

<template>
    <section class="grid gap-5 xl:grid-cols-[minmax(0,1fr)_24rem]">
        <div class="flex min-w-0 flex-col gap-5">
            <header class="flex flex-col gap-1">
                <h1 class="text-2xl font-semibold tracking-normal text-foreground">{{ title }}</h1>
                <p v-if="description" class="max-w-3xl text-sm text-muted-foreground">{{ description }}</p>
            </header>

            <div class="overflow-hidden rounded-lg border border-sidebar-border/70 bg-background dark:border-sidebar-border">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-sidebar-border/70 text-sm dark:divide-sidebar-border">
                        <thead class="bg-sidebar-accent/50 text-left text-xs font-semibold uppercase text-muted-foreground">
                            <tr>
                                <th v-for="column in columns" :key="column.key" class="whitespace-nowrap px-4 py-3">{{ column.label }}</th>
                                <th class="whitespace-nowrap px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-sidebar-border/70 dark:divide-sidebar-border">
                            <tr v-for="row in items.data" :key="row.id" class="hover:bg-sidebar-accent/35">
                                <td v-for="column in columns" :key="column.key" class="whitespace-nowrap px-4 py-3">{{ valueFor(row, column.key) }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button class="rounded-md border px-3 py-2 text-xs font-medium hover:bg-sidebar-accent" type="button" @click="edit(row)">Edit</button>
                                        <button class="rounded-md border border-red-200 px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-50 dark:border-red-900/50 dark:hover:bg-red-950/40" type="button" @click="destroy(row)">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!items.data.length">
                                <td :colspan="columns.length + 1" class="px-4 py-8 text-center text-muted-foreground">No records found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <Pagination :pagination="items" />
        </div>

        <form class="h-fit rounded-lg border border-sidebar-border/70 bg-background p-4 shadow-sm dark:border-sidebar-border" @submit.prevent="submit">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-semibold">{{ editing ? 'Edit record' : 'Create record' }}</h2>
                    <p class="text-sm text-muted-foreground">{{ editing ? 'Update the selected item.' : 'Add a new item.' }}</p>
                </div>
                <button v-if="editing" type="button" class="rounded-md border px-3 py-2 text-xs" @click="resetForm">Cancel</button>
            </div>

            <div class="mt-4 grid gap-4">
                <label v-for="field in fields" :key="field.key" class="grid gap-2 text-sm">
                    <span class="font-medium">{{ field.label }}</span>
                    <select
                        v-if="field.type === 'select'"
                        v-model="form[field.key]"
                        :required="field.required"
                        class="min-h-10 rounded-md border border-sidebar-border bg-background px-3 py-2 outline-none focus:ring-2 focus:ring-sidebar-primary/30"
                    >
                        <option value="">Select {{ field.label }}</option>
                        <option v-for="option in field.options" :key="`${field.key}-${option.value}`" :value="option.value ?? ''">
                            {{ option.label }}
                        </option>
                    </select>
                    <textarea
                        v-else-if="field.type === 'textarea'"
                        v-model="form[field.key]"
                        :required="field.required"
                        rows="4"
                        class="rounded-md border border-sidebar-border bg-background px-3 py-2 outline-none focus:ring-2 focus:ring-sidebar-primary/30"
                    />
                    <input
                        v-else
                        v-model="form[field.key]"
                        :type="field.type ?? 'text'"
                        :required="field.required"
                        class="min-h-10 rounded-md border border-sidebar-border bg-background px-3 py-2 outline-none focus:ring-2 focus:ring-sidebar-primary/30"
                    />
                    <span v-if="form.errors[field.key]" class="text-xs font-medium text-red-600">{{ form.errors[field.key] }}</span>
                </label>
            </div>

            <button
                type="submit"
                class="mt-5 min-h-10 w-full rounded-md bg-sidebar-primary px-4 py-2 text-sm font-semibold text-sidebar-primary-foreground disabled:cursor-not-allowed disabled:opacity-60"
                :disabled="form.processing"
            >
                {{ form.processing ? 'Saving...' : editing ? 'Update' : 'Create' }}
            </button>
        </form>
    </section>
</template>
