<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import { Link } from '@inertiajs/vue3';
import { Pencil, Trash2, Eye } from 'lucide-vue-next';

type Column = {
    key: string;
    label: string;
};
type Action = 'edit' | 'view' | 'delete';

const props = defineProps<{
    title: string;
    description?: string;
    items: {
        data: Record<string, unknown>[];
        links?: Array<{ url: string | null; label: string; active: boolean }>;
        meta?: Record<string, unknown>;
    };
    columns: Column[];
    actions?: Action[];
    editRoute?: string;
    viewRoute?: string;
    deleteRoute?: string;
}>();

const emit = defineEmits<{
    delete: [id: number];
}>();

function valueFor(row: Record<string, unknown>, key: string): string {
    const value = key.split('.').reduce<unknown>((current, segment) => {
        if (current && typeof current === 'object' && segment in current) {
            return (current as Record<string, unknown>)[segment];
        }

        return null;
    }, row);

    return value === null || value === undefined || value === '' ? '—' : String(value);
}
function routeWithId(route: string, id: number): string {
    return route.replace(':id', String(id));
}
</script>

<template>
    <section class="flex flex-col gap-5">
        <header class="flex flex-col gap-1">
            <h1 class="text-2xl font-semibold tracking-normal text-foreground">{{ props.title }}</h1>
            <p v-if="props.description" class="max-w-3xl text-sm text-muted-foreground">{{ props.description }}</p>
        </header>

        <div
            class="overflow-hidden rounded-lg border border-sidebar-border/70 bg-background dark:border-sidebar-border">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-sidebar-border/70 text-sm dark:divide-sidebar-border">
                    <thead class="bg-sidebar-accent/50 text-left text-xs font-semibold uppercase text-muted-foreground">
                        <tr>
                            <th v-for="column in props.columns" :key="column.key" class="whitespace-nowrap px-4 py-3">
                                {{ column.label }}
                            </th>
                            <th v-if="props.actions?.length" class="whitespace-nowrap px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sidebar-border/70 dark:divide-sidebar-border">
                        <tr v-for="row in props.items.data" :key="String(row.id)" class="hover:bg-sidebar-accent/35">
                            <td v-for="column in props.columns" :key="column.key" class="whitespace-nowrap px-4 py-3">
                                {{ valueFor(row, column.key) }}
                            </td>
                            <td v-if="props.actions?.length" class="whitespace-nowrap px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <Link v-if="props.actions.includes('view') && props.viewRoute"
                                        :href="routeWithId(props.viewRoute, Number(row.id))"
                                        class="inline-flex items-center justify-center size-8 rounded-md hover:bg-sidebar-accent">
                                        <Eye class="size-4" />
                                    </Link>
                                    <Link v-if="props.actions.includes('edit') && props.editRoute"
                                        :href="routeWithId(props.editRoute, Number(row.id))"
                                        class="inline-flex items-center justify-center size-8 rounded-md hover:bg-sidebar-accent">
                                        <Pencil class="size-4" />
                                    </Link>
                                    <button v-if="props.actions.includes('delete')"
                                        class="inline-flex items-center justify-center size-8 rounded-md hover:bg-destructive/10 text-destructive"
                                        @click="emit('delete', Number(row.id))">
                                        <Trash2 class="size-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!props.items.data.length">
                            <td :colspan="props.columns.length" class="px-4 py-8 text-center text-muted-foreground">
                                No records found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <Pagination :pagination="props.items" />
    </section>
</template>
