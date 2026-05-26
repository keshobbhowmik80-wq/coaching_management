<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import DashboardStats from '@/components/DashboardStats.vue';

defineProps<{ stats: Record<string, string | number> }>();
</script>

<template>
    <Head title="Admin Dashboard" />
    <section class="flex flex-col gap-5">
        <h1 class="text-2xl font-semibold">Admin Dashboard</h1>
        <DashboardStats :stats="stats" />
    </section>
</template>
