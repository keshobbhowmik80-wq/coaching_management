<script setup lang="ts">
import RoleShell from '@/components/RoleShell.vue';

const groups = [
    {
        title: 'Overview',
        items: [{ title: 'Dashboard', href: '/student/dashboard' }],
    },
    {
        title: 'Academic',
        items: [
            { title: 'Profile', href: '/student/profile' },
            { title: 'Results', href: '/student/results' },
            { title: 'Performance', href: '/student/performance' },
            { title: 'Routine', href: '/student/routine' },
        ],
    },
    {
        title: 'Account',
        items: [
            { title: 'Payments', href: '/student/payments' },
            { title: 'Notices', href: '/student/notices' },
        ],
    },
];
</script>

<template>
    <RoleShell
        role-name="Student"
        title="Student Dashboard"
        subtitle="Academic self-service"
        dashboard-href="/student/dashboard"
        accent-class="bg-blue-700"
        :groups="groups"
    >
        <slot />
    </RoleShell>
</template>
