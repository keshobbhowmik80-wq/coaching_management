<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';

defineProps<{ students: any }>();

const columns = [
    { key: 'admission_no', label: 'Admission No' },
    { key: 'user.name', label: 'Student' },
    { key: 'coaching_class.name', label: 'Class' },
    { key: 'section.name', label: 'Section' },
    { key: 'marks_count', label: 'Marks Count' },
    { key: 'marks_avg_marks_obtained', label: 'Average' },
];
</script>

<template>
    <Head title="Student Performance" />
    <ManagementList title="Student Performance" description="Read-only student performance summary." :items="students" :columns="columns" />
</template>
