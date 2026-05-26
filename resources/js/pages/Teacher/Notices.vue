<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';

defineProps<{ notices: any }>();

const columns = [
    { key: 'title', label: 'Title' },
    { key: 'audience', label: 'Audience' },
    { key: 'published_on', label: 'Published' },
    { key: 'body', label: 'Message' },
];
</script>

<template>
    <Head title="Notices" />
    <ManagementList title="Notices" description="Post notices and review announcements." :items="notices" :columns="columns" />
</template>
