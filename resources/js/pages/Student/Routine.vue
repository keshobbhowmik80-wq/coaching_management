<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';

defineProps<{ routines: any }>();

const columns = [
    { key: 'day_of_week', label: 'Day' },
    { key: 'subject.name', label: 'Subject' },
    { key: 'teacher.user.name', label: 'Teacher' },
    { key: 'section.name', label: 'Section' },
    { key: 'starts_at', label: 'Starts' },
    { key: 'ends_at', label: 'Ends' },
    { key: 'room', label: 'Room' },
];
</script>

<template>
    <Head title="Class Routine" />
    <ManagementList title="Class Routine" description="Your class schedule and subject routine." :items="routines" :columns="columns" />
</template>
