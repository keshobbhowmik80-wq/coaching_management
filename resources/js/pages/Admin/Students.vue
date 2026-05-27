<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
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

const deleteForm = useForm({});

function handleDelete(id: number) {
    if (confirm('Are you sure you want to delete this student?')) {
        deleteForm.delete(`/admin/students/${id}`);
    }
}
</script>
<template>

    <Head title="Students" />
    <ManagementList title="Students" description="Manage student accounts and academic placement." :items="students"
        :columns="columns" :actions="['edit', 'delete']" edit-route="/admin/students/:id/edit"
        delete-route="/admin/students/:id" @delete="handleDelete" />
</template>
