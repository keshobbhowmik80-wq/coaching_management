<script setup lang="ts">
defineProps<{
    stats: Record<string, string | number>;
}>();

function labelFor(key: string): string {
    return key.replace(/([A-Z])/g, ' $1').replace(/^./, (value) => value.toUpperCase());
}
</script>

<template>
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <div
            v-for="(value, key) in stats"
            :key="key"
            class="rounded-lg border border-sidebar-border/70 bg-background p-4 dark:border-sidebar-border"
        >
            <p class="text-sm text-muted-foreground">{{ labelFor(String(key)) }}</p>
            <p class="mt-2 text-2xl font-semibold text-foreground">{{ value }}</p>
        </div>
    </div>
</template>
