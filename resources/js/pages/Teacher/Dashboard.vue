<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import DashboardStats from '@/components/DashboardStats.vue';

defineProps<{
    stats: Record<string, string | number>;
}>();
</script>

<template>
    <Head title="Teacher Dashboard" />

    <section class="flex flex-col gap-5">
        <div>
            <p class="text-sm font-medium text-muted-foreground">Teacher Workspace</p>
            <h1 class="text-2xl font-semibold tracking-normal">Dashboard</h1>
        </div>

        <DashboardStats :stats="stats" />
    </section>
</template>
