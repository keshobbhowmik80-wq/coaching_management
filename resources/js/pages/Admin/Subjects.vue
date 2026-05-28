<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';

defineProps<{ subjects: any }>();

const columns = [
    { key: 'name', label: 'Subject' },
    { key: 'code', label: 'Code' },
    { key: 'coaching_class.name', label: 'Class' },
    { key: 'full_marks', label: 'Full Marks' },
    { key: 'pass_marks', label: 'Pass Marks' },
];

const deleteForm = useForm({});

function handleDelete(id: number) {
    if (confirm('Are you sure you want to delete this subject?')) {
        deleteForm.delete(`/admin/subjects/${id}`);
    }
}
</script>

<template>

    <Head title="Subjects" />
    <ManagementList title="Subjects" description="Manage subjects and grading rules." :items="subjects"
        :columns="columns" :actions="['edit', 'delete']" edit-route="/admin/subjects/:id/edit" @delete="handleDelete" />
</template>
