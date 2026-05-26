<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';

defineProps<{ marks: any }>();

const columns = [
    { key: 'student.user.name', label: 'Student' },
    { key: 'exam.name', label: 'Exam' },
    { key: 'subject.name', label: 'Subject' },
    { key: 'marks_obtained', label: 'Marks' },
    { key: 'remarks', label: 'Remarks' },
];
</script>

<template>
    <Head title="Manage Marks" />
    <ManagementList title="Manage Marks" description="Add and update marks for your assigned students." :items="marks" :columns="columns" />
</template>
