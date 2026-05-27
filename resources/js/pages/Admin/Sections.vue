<script setup lang="ts">
import { Head,useForm } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';
defineProps<{ sections: any }>();
const columns = [
    { key: 'coaching_class.name', label: 'Class' },
    { key: 'name', label: 'Section' },
    { key: 'capacity', label: 'Capacity' },
];

const deleteForm = useForm({});

function handleDelete(id: number) {
    if (confirm('Are you sure you want to delete this student?')) {
        deleteForm.delete(`/admin/sections/${id}`);
    }
}
</script>
<template>

    <Head title="Sections" />
    <ManagementList title="Sections" description="Manage class sections." :items="sections" :columns="columns"
        :actions="['edit', 'delete']" edit-route="/admin/sections/:id/edit" delete-route="/admin/sections/:id"
        @delete="handleDelete" />
</template>
