<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';
defineProps<{ expenses: any }>();
const columns = [
    { key: 'category', label: 'Category' },
    { key: 'description', label: 'Description' },
    { key: 'amount', label: 'Amount' },
    { key: 'spent_on', label: 'Spent On' },
    { key: 'creator.name', label: 'Created By' },
];
</script>
<template><Head title="Expenses" /><ManagementList title="Expenses" description="Manage operational expenses." :items="expenses" :columns="columns" /></template>
