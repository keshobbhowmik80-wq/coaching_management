<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';

defineProps<{ notices: any }>();

const columns = [
    { key: 'title', label: 'Title' },
    { key: 'published_on', label: 'Published' },
    { key: 'body', label: 'Message' },
];
</script>

<template>
    <Head title="Notices" />
    <ManagementList title="Notices" description="Announcements for students." :items="notices" :columns="columns" />
</template>
