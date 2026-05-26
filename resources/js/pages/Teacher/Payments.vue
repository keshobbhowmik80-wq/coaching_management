<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import DashboardStats from '@/components/DashboardStats.vue';
import ManagementList from '@/components/ManagementList.vue';

defineProps<{
    payments: any;
    summary: Record<string, string | number>;
}>();

const columns = [
    { key: 'student.user.name', label: 'Student' },
    { key: 'fee_type', label: 'Fee' },
    { key: 'amount_due', label: 'Due' },
    { key: 'amount_paid', label: 'Paid' },
    { key: 'status', label: 'Status' },
];
</script>

<template>
    <Head title="Payment Summary" />
    <section class="flex flex-col gap-5">
        <DashboardStats :stats="summary" />
        <ManagementList title="Payment Summary" description="Read-only overview of student payments and dues." :items="payments" :columns="columns" />
    </section>
</template>
