<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';
defineProps<{ students: any }>();
const columns = [
    { key: 'admission_no', label: 'Admission No' },
    { key: 'user.name', label: 'Name' },
    { key: 'user.email', label: 'Email' },
    { key: 'coaching_class.name', label: 'Class' },
    { key: 'section.name', label: 'Section' },
    { key: 'guardian_phone', label: 'Guardian Phone' },
];
</script>
<template><Head title="Students" /><ManagementList title="Students" description="Manage student accounts and academic placement." :items="students" :columns="columns" /></template>
