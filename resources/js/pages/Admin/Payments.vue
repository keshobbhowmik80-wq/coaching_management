<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';
defineProps<{ payments: any }>();
const columns = [
    { key: 'student.user.name', label: 'Student' },
    { key: 'fee_type', label: 'Fee' },
    { key: 'amount_due', label: 'Due' },
    { key: 'amount_paid', label: 'Paid' },
    { key: 'status', label: 'Status' },
];
</script>
<template><Head title="Payments" /><ManagementList title="Payments" description="Track fees, dues, and payment history." :items="payments" :columns="columns" /></template>
