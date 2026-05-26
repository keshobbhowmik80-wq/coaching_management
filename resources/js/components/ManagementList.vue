<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';

type Column = {
    key: string;
    label: string;
};

const props = defineProps<{
    title: string;
    description?: string;
    items: {
        data: Record<string, unknown>[];
        links?: Array<{ url: string | null; label: string; active: boolean }>;
        meta?: Record<string, unknown>;
    };
    columns: Column[];
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
</script>

<template>
    <section class="flex flex-col gap-5">
        <header class="flex flex-col gap-1">
            <h1 class="text-2xl font-semibold tracking-normal text-foreground">{{ props.title }}</h1>
            <p v-if="props.description" class="max-w-3xl text-sm text-muted-foreground">{{ props.description }}</p>
        </header>

        <div class="overflow-hidden rounded-lg border border-sidebar-border/70 bg-background dark:border-sidebar-border">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-sidebar-border/70 text-sm dark:divide-sidebar-border">
                    <thead class="bg-sidebar-accent/50 text-left text-xs font-semibold uppercase text-muted-foreground">
                        <tr>
                            <th v-for="column in props.columns" :key="column.key" class="whitespace-nowrap px-4 py-3">
                                {{ column.label }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sidebar-border/70 dark:divide-sidebar-border">
                        <tr v-for="row in props.items.data" :key="String(row.id)" class="hover:bg-sidebar-accent/35">
                            <td v-for="column in props.columns" :key="column.key" class="whitespace-nowrap px-4 py-3">
                                {{ valueFor(row, column.key) }}
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
