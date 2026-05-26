<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';
defineProps<{ teachers: any }>();
const columns = [
    { key: 'employee_id', label: 'Employee ID' },
    { key: 'user.name', label: 'Name' },
    { key: 'user.email', label: 'Email' },
    { key: 'phone', label: 'Phone' },
    { key: 'qualification', label: 'Qualification' },
];
</script>
<template><Head title="Teachers" /><ManagementList title="Teachers" description="Manage teacher profiles and credentials." :items="teachers" :columns="columns" /></template>
