<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import DashboardStats from '@/components/DashboardStats.vue';

defineProps<{ summary: any }>();
</script>

<template>
    <Head title="Performance" />

    <section class="flex flex-col gap-5">
        <DashboardStats :stats="{ total: summary.total, average: summary.average }" />
        <div class="rounded-lg border border-sidebar-border/70 bg-background p-4 dark:border-sidebar-border">
            <h1 class="text-xl font-semibold">Subject Performance</h1>
            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                <div v-for="subject in summary.subjects" :key="subject.subject" class="rounded-md border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                    <p class="font-medium">{{ subject.subject }}</p>
                    <p class="mt-1 text-sm text-muted-foreground">Average: {{ subject.average }}</p>
                </div>
            </div>
        </div>
    </section>
</template>
