<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';
defineProps<{ notices: any }>();
const columns = [
    { key: 'title', label: 'Title' },
    { key: 'audience', label: 'Audience' },
    { key: 'published_on', label: 'Published' },
    { key: 'creator.name', label: 'Created By' },
];
</script>
<template><Head title="Notices" /><ManagementList title="Notices" description="Manage announcements by audience." :items="notices" :columns="columns" /></template>
