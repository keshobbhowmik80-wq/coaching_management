<script setup lang="ts">
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import Pagination from '@/components/Pagination.vue';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Pencil, Trash2, Calendar, BookOpen, GraduationCap, Clock, X } from 'lucide-vue-next';
import { ref, computed, watch } from 'vue';

const props = defineProps<{
    routines: any;
    classes: { id: number; name: string }[];
    filters: { type?: string; class_id?: string };
}>();

const deleteForm = useForm({});

const typeFilter = ref(props.filters?.type || 'all');
const classFilter = ref(props.filters?.class_id || 'all');

function applyFilters() {
    router.get('/admin/routines', {
        type: typeFilter.value !== 'all' ? typeFilter.value : undefined,
        class_id: classFilter.value !== 'all' ? classFilter.value : undefined,
    }, { preserveState: true, replace: true });
}

watch([typeFilter, classFilter], applyFilters);

function clearFilters() {
    typeFilter.value = 'all';
    classFilter.value = 'all';
}

function handleDelete(id: number, event: Event) {
    event.stopPropagation();
    if (confirm('Are you sure you want to delete this routine?')) {
        deleteForm.delete(`/admin/routines/${id}`);
    }
}

function formatDate(date: string) {
    return date ? new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '';
}

const hasFilters = computed(() => typeFilter.value !== 'all' || classFilter.value !== 'all');
</script>

<template>
    <Head title="Routines" />

    <div class="space-y-5">
        <div class="flex flex-col gap-1">
            <h1 class="text-2xl font-semibold">Routines</h1>
            <p class="text-sm text-muted-foreground">Manage class schedules and exam routines.</p>
        </div>

        <div class="flex flex-wrap items-end gap-3">
            <Select v-model="typeFilter">
                <SelectTrigger class="w-36">
                    <SelectValue placeholder="All Types" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">All Types</SelectItem>
                    <SelectItem value="class">Class Routine</SelectItem>
                    <SelectItem value="exam">Exam Routine</SelectItem>
                </SelectContent>
            </Select>
            <Select v-model="classFilter">
                <SelectTrigger class="w-40">
                    <SelectValue placeholder="All Classes" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">All Classes</SelectItem>
                    <SelectItem v-for="c in props.classes" :key="c.id" :value="String(c.id)">{{ c.name }}</SelectItem>
                </SelectContent>
            </Select>
            <Button v-if="hasFilters" variant="ghost" size="sm" @click="clearFilters">
                <X class="mr-1 size-4" />
                Clear
            </Button>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <Link v-for="routine in routines.data" :key="routine.id" :href="`/admin/routines/${routine.id}`">
                <Card class="relative overflow-hidden cursor-pointer hover:shadow-md transition-shadow h-full">
                    <div
                        class="absolute left-0 top-0 bottom-0 w-1"
                        :class="routine.type === 'exam' ? 'bg-orange-500' : 'bg-blue-500'"
                    />
                    <CardContent class="p-4 pl-5 space-y-3">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-semibold text-sm">{{ routine.name }}</p>
                                <p class="text-xs text-muted-foreground">{{ routine.coaching_class?.name }}</p>
                            </div>
                            <span
                                class="text-xs px-2 py-0.5 rounded-full font-medium"
                                :class="routine.type === 'exam' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'"
                            >
                                {{ routine.type === 'exam' ? 'Exam' : 'Class' }}
                            </span>
                        </div>

                        <div class="space-y-1.5 text-xs text-muted-foreground">
                            <div v-if="routine.type === 'class' && routine.section" class="flex items-center gap-2">
                                <BookOpen class="size-3.5" />
                                <span>Section {{ routine.section.name }}</span>
                            </div>
                            <div v-if="routine.type === 'exam'" class="flex items-center gap-2">
                                <Calendar class="size-3.5" />
                                <span>{{ formatDate(routine.starts_on) }} — {{ formatDate(routine.ends_on) }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <Clock class="size-3.5" />
                                <span>{{ routine.slots_count ?? routine.slots?.length }} slots</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-1 pt-1 border-t border-sidebar-border/50" @click.prevent>
                            <Link
                                :href="`/admin/routines/${routine.id}/edit`"
                                class="inline-flex items-center justify-center size-8 rounded-md hover:bg-sidebar-accent"
                            >
                                <Pencil class="size-3.5" />
                            </Link>
                            <button
                                class="inline-flex items-center justify-center size-8 rounded-md hover:bg-destructive/10 text-destructive"
                                @click="(e) => handleDelete(routine.id, e)"
                            >
                                <Trash2 class="size-3.5" />
                            </button>
                        </div>
                    </CardContent>
                </Card>
            </Link>
        </div>

        <p v-if="!routines.data.length" class="text-center text-muted-foreground py-12">No routines found.</p>

        <Pagination :pagination="routines" />
    </div>
</template>
