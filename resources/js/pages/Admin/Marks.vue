<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';
defineProps<{ marks: any }>();
const columns = [
    { key: 'student.user.name', label: 'Student' },
    { key: 'exam.name', label: 'Exam' },
    { key: 'subject.name', label: 'Subject' },
    { key: 'marks_obtained', label: 'Marks' },
    { key: 'teacher.user.name', label: 'Teacher' },
];
</script>
<template><Head title="Marks" /><ManagementList title="Marks" description="Review marks across exams and subjects." :items="marks" :columns="columns" /></template>
