<script setup lang="ts">
import RoleShell from '@/components/RoleShell.vue';

// const groups = [
//     {
//         title: 'Overview',
//         items: [{ title: 'Dashboard', href: '/admin/dashboard' }],
//     },
//     {
//         title: 'People',
//         items: [
//             { title: 'Students', href: '/admin/students' },
//             { title: 'Teachers', href: '/admin/teachers' },
//         ],
//     },
//     {
//         title: 'Academic',
//         items: [
//             { title: 'Classes', href: '/admin/classes' },
//             { title: 'Sections', href: '/admin/sections' },
//             { title: 'Subjects', href: '/admin/subjects' },
//             { title: 'Exams', href: '/admin/exams' },
//             { title: 'Marks', href: '/admin/marks' },
//             { title: 'Routines', href: '/admin/routines' },
//         ],
//     },
//     {
//         title: 'Finance',
//         items: [
//             { title: 'Payments', href: '/admin/payments' },
//             { title: 'Expenses', href: '/admin/expenses' },
//         ],
//     },
//     {
//         title: 'Communication',
//         items: [{ title: 'Notices', href: '/admin/notices' }],
//     },
// ];

const groups = [
    {
        title: 'Overview',
        icon: 'ScanEye',
        items: [{ title: 'Dashboard', href: '/admin/dashboard', icon: 'LayoutDashboard' }],
    },
    {
        title: 'People',
        icon: 'Users',
        items: [
            { title: 'Students', href: '/admin/students', icon: 'GraduationCap' },
            { title: 'Teachers', href: '/admin/teachers', icon: 'Presentation' },
            { title: 'Add Student', href: '/admin/students/create', icon: 'UserPlus' },
            { title: 'Add Teacher', href: '/admin/teachers/create', icon: 'UserPlus' },
        ],
    },
    {
        title: 'Academic',
        icon: 'BookOpen',
        items: [
            { title: 'Classes', href: '/admin/classes', icon: 'Building2' },
            { title: 'Add Class', href: '/admin/classes/create', icon: 'Plus' },
            { title: 'Sections', href: '/admin/sections', icon: 'Grid3x3' },
            { title: 'Add Section', href: '/admin/sections/create', icon: 'Plus' },
            { title: 'Subjects', href: '/admin/subjects', icon: 'BookMarked' },
            { title: 'Add Subject', href: '/admin/subjects/create', icon: 'Plus' },
            { title: 'Exams', href: '/admin/exams', icon: 'ClipboardList' },
            { title: 'Add Exam', href: '/admin/exams/create', icon: 'Plus' },
            // { title: 'Exam Students', href: '/admin/exams/{id}/students', icon: 'Users' },
            { title: 'Marks', href: '/admin/marks', icon: 'PenLine' },
            { title: 'Marks Entry', href: '/admin/marks/entry', icon: 'PenLine' },
            { title: 'Routines', href: '/admin/routines', icon: 'CalendarDays' },
            { title: 'Add Routine', href: '/admin/routines/create', icon: 'Plus' },
        ],
    },
    {
        title: 'Finance',
        icon: 'Banknote',
        items: [
            { title: 'Payments', href: '/admin/payments', icon: 'Receipt' },
            { title: 'Expenses', href: '/admin/expenses', icon: 'ArrowDownCircle' },
        ],
    },
    {
        title: 'Communication',
        icon: 'Megaphone',
        items: [{ title: 'Notices', href: '/admin/notices', icon: 'Bell' }],
    },
];
</script>

<template>
    <RoleShell role-name="Admin" title="Admin Dashboard" subtitle="Full system control"
        dashboard-href="/admin/dashboard" accent-class="bg-slate-900 dark:bg-slate-100 dark:text-slate-900"
        :groups="groups">
        <slot />
    </RoleShell>
</template>
