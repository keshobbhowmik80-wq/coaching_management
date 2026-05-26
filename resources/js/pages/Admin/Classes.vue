<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';
defineProps<{ classes: any }>();
const columns = [
    { key: 'name', label: 'Class' },
    { key: 'code', label: 'Code' },
    { key: 'sections_count', label: 'Sections' },
    { key: 'students_count', label: 'Students' },
    { key: 'subjects_count', label: 'Subjects' },
];
</script>
<template><Head title="Classes" /><ManagementList title="Classes" description="Manage academic classes." :items="classes" :columns="columns" /></template>
