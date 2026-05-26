<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';
defineProps<{ subjects: any }>();
const columns = [
    { key: 'name', label: 'Subject' },
    { key: 'code', label: 'Code' },
    { key: 'coaching_class.name', label: 'Class' },
    { key: 'full_marks', label: 'Full Marks' },
    { key: 'pass_marks', label: 'Pass Marks' },
];
</script>
<template><Head title="Subjects" /><ManagementList title="Subjects" description="Manage subjects and grading rules." :items="subjects" :columns="columns" /></template>
