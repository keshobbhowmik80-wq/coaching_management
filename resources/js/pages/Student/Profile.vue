<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

defineProps<{ student: any }>();
</script>

<template>
    <Head title="Profile" />

    <section class="rounded-lg border border-sidebar-border/70 bg-background p-5 dark:border-sidebar-border">
        <h1 class="text-2xl font-semibold">Profile</h1>
        <dl class="mt-5 grid gap-4 text-sm sm:grid-cols-2">
            <div><dt class="text-muted-foreground">Name</dt><dd class="font-medium">{{ student?.user?.name ?? 'Not available' }}</dd></div>
            <div><dt class="text-muted-foreground">Email</dt><dd class="font-medium">{{ student?.user?.email ?? 'Not available' }}</dd></div>
            <div><dt class="text-muted-foreground">Admission No</dt><dd class="font-medium">{{ student?.admission_no ?? 'Not assigned' }}</dd></div>
            <div><dt class="text-muted-foreground">Class</dt><dd class="font-medium">{{ student?.coaching_class?.name ?? 'Not assigned' }}</dd></div>
            <div><dt class="text-muted-foreground">Section</dt><dd class="font-medium">{{ student?.section?.name ?? 'Not assigned' }}</dd></div>
            <div><dt class="text-muted-foreground">Guardian Phone</dt><dd class="font-medium">{{ student?.guardian_phone ?? 'Not provided' }}</dd></div>
        </dl>
    </section>
</template>
