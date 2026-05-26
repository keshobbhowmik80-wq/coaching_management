<script setup lang="ts">
import { Moon, Sun } from 'lucide-vue-next';
import { useAppearance } from '@/composables/useAppearance';

const { appearance, updateAppearance, processing } = useAppearance();

function toggleTheme(): void {
    updateAppearance(appearance.value === 'dark' ? 'light' : 'dark');
}
</script>

<template>
    <button
        type="button"
        class="inline-flex min-h-10 items-center gap-2 rounded-md border border-sidebar-border bg-background px-3 text-sm font-medium text-foreground transition hover:bg-sidebar-accent disabled:cursor-not-allowed disabled:opacity-60"
        :disabled="processing"
        :aria-label="appearance === 'dark' ? 'Switch to light theme' : 'Switch to dark theme'"
        @click="toggleTheme"
    >
        <Sun v-if="appearance === 'dark'" class="size-4" />
        <Moon v-else class="size-4" />
        <span class="hidden sm:inline">{{ appearance === 'dark' ? 'Light' : 'Dark' }}</span>
    </button>
</template>
