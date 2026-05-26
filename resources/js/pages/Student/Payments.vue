<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import DashboardStats from '@/components/DashboardStats.vue';
import ManagementList from '@/components/ManagementList.vue';

defineProps<{ payments: any; summary: Record<string, string | number> }>();

const columns = [
    { key: 'fee_type', label: 'Fee' },
    { key: 'amount_due', label: 'Due' },
    { key: 'amount_paid', label: 'Paid' },
    { key: 'due_date', label: 'Due Date' },
    { key: 'paid_at', label: 'Paid At' },
    { key: 'status', label: 'Status' },
];
</script>

<template>
    <Head title="Payments" />
    <section class="flex flex-col gap-5">
        <DashboardStats :stats="summary" />
        <ManagementList title="Payments" description="Your due payments and payment history." :items="payments" :columns="columns" />
    </section>
</template>
