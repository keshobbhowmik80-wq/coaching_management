<script setup lang="ts">
import { Head,useForm } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';
defineProps<{ classes: any }>();
const columns = [
    { key: 'name', label: 'Class' },
    { key: 'code', label: 'Code' },
    { key: 'sections_count', label: 'Sections' },
    { key: 'students_count', label: 'Students' },
    { key: 'subjects_count', label: 'Subjects' },
];

const deleteForm = useForm({});

function handleDelete(id: number) {
    if (confirm('Are you sure you want to delete this student?')) {
        deleteForm.delete(`/admin/classes/${id}`);
    }
}
</script>
<template>

    <Head title="Classes" />
    <ManagementList title="Classes" description="Manage academic classes." :items="classes" :columns="columns"
        :actions="['edit', 'delete']" edit-route="/admin/classes/:id/edit" delete-route="/admin/classes/:id"
        @delete="handleDelete" />
</template>
