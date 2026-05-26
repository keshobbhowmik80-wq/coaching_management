<script setup lang="ts">
import RoleShell from '@/components/RoleShell.vue';

const groups = [
    {
        title: 'Overview',
        items: [{ title: 'Dashboard', href: '/admin/dashboard' }],
    },
    {
        title: 'People',
        items: [
            { title: 'Students', href: '/admin/students' },
            { title: 'Teachers', href: '/admin/teachers' },
        ],
    },
    {
        title: 'Academic',
        items: [
            { title: 'Classes', href: '/admin/classes' },
            { title: 'Sections', href: '/admin/sections' },
            { title: 'Subjects', href: '/admin/subjects' },
            { title: 'Exams', href: '/admin/exams' },
            { title: 'Marks', href: '/admin/marks' },
            { title: 'Routines', href: '/admin/routines' },
        ],
    },
    {
        title: 'Finance',
        items: [
            { title: 'Payments', href: '/admin/payments' },
            { title: 'Expenses', href: '/admin/expenses' },
        ],
    },
    {
        title: 'Communication',
        items: [{ title: 'Notices', href: '/admin/notices' }],
    },
];
</script>

<template>
    <RoleShell
        role-name="Admin"
        title="Admin Dashboard"
        subtitle="Full system control"
        dashboard-href="/admin/dashboard"
        accent-class="bg-slate-900 dark:bg-slate-100 dark:text-slate-900"
        :groups="groups"
    >
        <slot />
    </RoleShell>
</template>
