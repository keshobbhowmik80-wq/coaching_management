<script setup lang="ts">
import RoleShell from '@/components/RoleShell.vue';

const groups = [
    {
        title: 'Overview',
        items: [{ title: 'Dashboard', href: '/teacher/dashboard' }],
    },
    {
        title: 'Teaching',
        items: [
            { title: 'Assigned Classes', href: '/teacher/classes' },
            { title: 'Marks', href: '/teacher/marks' },
            { title: 'Exams', href: '/teacher/exams' },
            { title: 'Performance', href: '/teacher/performance' },
        ],
    },
    {
        title: 'Operations',
        items: [
            { title: 'Notices', href: '/teacher/notices' },
            { title: 'Payments', href: '/teacher/payments' },
        ],
    },
];
</script>

<template>
    <RoleShell
        role-name="Teacher"
        title="Teacher Dashboard"
        subtitle="Class and exam workspace"
        dashboard-href="/teacher/dashboard"
        accent-class="bg-emerald-700"
        :groups="groups"
    >
        <slot />
    </RoleShell>
</template>
