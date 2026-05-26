<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    pagination: {
        links?: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
        meta?: {
            from?: number | null;
            to?: number | null;
            total?: number;
        };
    };
}>();
</script>

<template>
    <nav
        v-if="pagination.links?.length"
        class="flex flex-col gap-3 border-t border-sidebar-border/70 pt-4 text-sm md:flex-row md:items-center md:justify-between dark:border-sidebar-border"
        aria-label="Pagination"
    >
        <p class="text-muted-foreground">
            Showing
            <span class="font-medium text-foreground">{{ pagination.meta?.from ?? 0 }}</span>
            to
            <span class="font-medium text-foreground">{{ pagination.meta?.to ?? 0 }}</span>
            of
            <span class="font-medium text-foreground">{{ pagination.meta?.total ?? 0 }}</span>
            results
        </p>

        <div class="flex max-w-full gap-1 overflow-x-auto pb-1">
            <component
                :is="link.url ? Link : 'span'"
                v-for="link in pagination.links"
                :key="`${link.label}-${link.url ?? 'disabled'}`"
                :href="link.url || undefined"
                preserve-scroll
                preserve-state
                class="inline-flex min-h-10 min-w-10 shrink-0 items-center justify-center rounded-md border px-3 text-sm font-medium transition"
                :class="[
                    link.active
                        ? 'border-sidebar-primary bg-sidebar-primary text-sidebar-primary-foreground'
                        : 'border-sidebar-border bg-background text-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground dark:border-sidebar-border',
                    !link.url ? 'pointer-events-none opacity-45' : '',
                ]"
            >
                <span v-html="link.label" />
            </component>
        </div>
    </nav>
</template>
