<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';

defineProps<{ routines: any }>();

const columns = [
    { key: 'coaching_class.name', label: 'Class' },
    { key: 'section.name', label: 'Section' },
    { key: 'subject.name', label: 'Subject' },
    { key: 'day_of_week', label: 'Day' },
    { key: 'starts_at', label: 'Starts' },
    { key: 'ends_at', label: 'Ends' },
    { key: 'room', label: 'Room' },
];
</script>

<template>
    <Head title="Assigned Classes" />
    <ManagementList title="Assigned Classes" description="Your assigned class routine and teaching schedule." :items="routines" :columns="columns" />
</template>
