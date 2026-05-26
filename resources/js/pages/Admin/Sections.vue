<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';
defineProps<{ sections: any }>();
const columns = [
    { key: 'coaching_class.name', label: 'Class' },
    { key: 'name', label: 'Section' },
    { key: 'capacity', label: 'Capacity' },
];
</script>
<template><Head title="Sections" /><ManagementList title="Sections" description="Manage class sections." :items="sections" :columns="columns" /></template>
