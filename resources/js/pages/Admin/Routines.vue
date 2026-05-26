<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';
defineProps<{ routines: any }>();
const columns = [
    { key: 'coaching_class.name', label: 'Class' },
    { key: 'section.name', label: 'Section' },
    { key: 'subject.name', label: 'Subject' },
    { key: 'teacher.user.name', label: 'Teacher' },
    { key: 'day_of_week', label: 'Day' },
    { key: 'starts_at', label: 'Starts' },
    { key: 'ends_at', label: 'Ends' },
];
</script>
<template><Head title="Routines" /><ManagementList title="Routines" description="Manage class schedules and rooms." :items="routines" :columns="columns" /></template>
