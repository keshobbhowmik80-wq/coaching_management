<script setup lang="ts">
import RoleShell from '@/components/RoleShell.vue';

const groups = [
    {
        title: 'Overview',
        items: [
            { title: 'Dashboard', href: '/teacher/dashboard', icon: 'LayoutDashboard' },
        ],
    },
    {
        title: 'Teaching',
        items: [
            { title: 'Assigned Classes', href: '/teacher/classes', icon: 'BookOpen' },
            { title: 'Marks', href: '/teacher/marks', icon: 'PenLine' },
            { title: 'Exams', href: '/teacher/exams', icon: 'ClipboardList' },
            { title: 'Performance', href: '/teacher/performance', icon: 'TrendingUp' },
        ],
    },
    {
        title: 'Operations',
        items: [
            { title: 'Notices', href: '/teacher/notices', icon: 'Megaphone' },
            { title: 'Payments', href: '/teacher/payments', icon: 'Banknote' },
        ],
    },
];
</script>

<template>
    <RoleShell role-name="Teacher" title="Teacher Dashboard" subtitle="Class and exam workspace"
        dashboard-href="/teacher/dashboard" accent-class="bg-emerald-700" :groups="groups">
        <slot />
    </RoleShell>
</template>
