<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';

defineProps<{ exams: any }>();

const columns = [
    { key: 'name', label: 'Exam' },
    { key: 'coaching_class.name', label: 'Class' },
    { key: 'starts_on', label: 'Starts' },
    { key: 'ends_on', label: 'Ends' },
    { key: 'status', label: 'Status' },
];

const deleteForm = useForm({});

function handleDelete(id: number) {
    if (confirm('Are you sure you want to delete this exam?')) {
        deleteForm.delete(`/admin/exams/${id}`);
    }
}
</script>

<template>

    <Head title="Exams" />
    <ManagementList title="Exams" description="Create and manage exams." :items="exams" :columns="columns"
        :actions="['edit', 'delete']" edit-route="/admin/exams/:id/edit" @delete="handleDelete" />
</template>
