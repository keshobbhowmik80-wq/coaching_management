<script setup lang="ts">
import { Head,useForm } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';
defineProps<{ teachers: any }>();
const columns = [
    { key: 'employee_id', label: 'Employee ID' },
    { key: 'user.name', label: 'Name' },
    { key: 'user.email', label: 'Email' },
    { key: 'phone', label: 'Phone' },
    { key: 'qualification', label: 'Qualification' },
];

const deleteForm = useForm({});

function handleDelete(id: number) {
    if (confirm('Are you sure you want to delete this student?')) {
        deleteForm.delete(`/admin/teachers/${id}`);
    }
}
</script>
<template>

    <Head title="Teachers" />
    <ManagementList title="Teachers" description="Manage teacher profiles and credentials." :items="teachers"
        :columns="columns" :actions="['edit', 'delete']" edit-route="/admin/teachers/:id/edit" @delete="handleDelete" />
</template>
