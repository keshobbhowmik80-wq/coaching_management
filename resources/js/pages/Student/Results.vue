<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';

defineProps<{ marks: any }>();

const columns = [
    { key: 'exam.name', label: 'Exam' },
    { key: 'subject.name', label: 'Subject' },
    { key: 'marks_obtained', label: 'Marks' },
    { key: 'remarks', label: 'Remarks' },
];
</script>

<template>
    <Head title="Exam Results" />
    <ManagementList title="Exam Results" description="Subject-wise marks per exam." :items="marks" :columns="columns" />
</template>
