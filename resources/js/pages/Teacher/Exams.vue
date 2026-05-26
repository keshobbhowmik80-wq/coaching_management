<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';

defineProps<{ exams: any }>();

const columns = [
    { key: 'name', label: 'Exam' },
    { key: 'coaching_class.name', label: 'Class' },
    { key: 'starts_on', label: 'Starts' },
    { key: 'ends_on', label: 'Ends' },
    { key: 'status', label: 'Status' },
];
</script>

<template>
    <Head title="Exams" />
    <ManagementList title="Exams" description="Create and maintain exam schedules." :items="exams" :columns="columns" />
</template>
