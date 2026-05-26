<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import DashboardStats from '@/components/DashboardStats.vue';

defineProps<{
    student: any;
    stats: Record<string, string | number>;
}>();
</script>

<template>
    <Head title="Student Dashboard" />

    <section class="flex flex-col gap-5">
        <div>
            <p class="text-sm font-medium text-muted-foreground">Student Portal</p>
            <h1 class="text-2xl font-semibold tracking-normal">Dashboard</h1>
        </div>

        <DashboardStats :stats="stats" />

        <div class="rounded-lg border border-sidebar-border/70 bg-background p-4 dark:border-sidebar-border">
            <h2 class="text-base font-semibold">Academic Profile</h2>
            <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
                <div><dt class="text-muted-foreground">Admission No</dt><dd class="font-medium">{{ student?.admission_no ?? 'Not assigned' }}</dd></div>
                <div><dt class="text-muted-foreground">Class</dt><dd class="font-medium">{{ student?.coaching_class?.name ?? 'Not assigned' }}</dd></div>
                <div><dt class="text-muted-foreground">Section</dt><dd class="font-medium">{{ student?.section?.name ?? 'Not assigned' }}</dd></div>
                <div><dt class="text-muted-foreground">Guardian</dt><dd class="font-medium">{{ student?.guardian_name ?? 'Not provided' }}</dd></div>
            </dl>
        </div>
    </section>
</template>
