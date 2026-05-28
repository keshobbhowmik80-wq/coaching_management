<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';
import { Input } from '@/components/ui/input';
import { Search, X } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { ref, computed, watch } from 'vue';

const props = defineProps<{
    teachers: any;
    filters: { search?: string };
    allTeachers: { id: number; employee_id: string; name: string }[];
}>();

const columns = [
    { key: 'employee_id', label: 'Employee ID' },
    { key: 'user.name', label: 'Name' },
    { key: 'user.email', label: 'Email' },
    { key: 'phone', label: 'Phone' },
    { key: 'qualification', label: 'Qualification' },
];

const deleteForm = useForm({});

const search = ref(props.filters?.search || '');

const employeeIdSearch = ref('');
const nameSearch = ref('');

const filteredEmployeeIds = computed(() => {
    if (!employeeIdSearch.value) return [];
    const q = employeeIdSearch.value.toLowerCase();
    return props.allTeachers
        .filter(t => t.employee_id.toLowerCase().includes(q))
        .slice(0, 10);
});

const filteredNames = computed(() => {
    if (!nameSearch.value) return [];
    const q = nameSearch.value.toLowerCase();
    return props.allTeachers
        .filter(t => t.name.toLowerCase().includes(q))
        .slice(0, 10);
});

function selectByEmployeeId(employeeId: string) {
    search.value = employeeId;
    employeeIdSearch.value = '';
    applyFilters();
}

function selectByName(name: string) {
    search.value = name;
    nameSearch.value = '';
    applyFilters();
}

function applyFilters() {
    router.get('/admin/teachers', {
        search: search.value || undefined,
    }, { preserveState: true, replace: true });
}

function clearFilters() {
    search.value = '';
    employeeIdSearch.value = '';
    nameSearch.value = '';
    router.get('/admin/teachers', {}, { replace: true });
}

function handleDelete(id: number) {
    if (confirm('Are you sure you want to delete this teacher?')) {
        deleteForm.delete(`/admin/teachers/${id}`);
    }
}

const hasFilters = computed(() => !!search.value);
</script>

<template>

    <Head title="Teachers" />

    <div class="space-y-5">
        <div class="flex flex-wrap items-end gap-3">
            <div class="relative w-48">
                <Search class="absolute left-3 top-3 size-4 text-muted-foreground" />
                <Input v-model="employeeIdSearch" placeholder="Employee ID..." class="pl-9" />
                <div v-if="filteredEmployeeIds.length"
                    class="absolute top-full left-0 right-0 z-50 mt-1 rounded-md border bg-background shadow-lg max-h-48 overflow-y-auto">
                    <div v-for="t in filteredEmployeeIds" :key="t.id"
                        class="cursor-pointer px-4 py-2 text-sm hover:bg-sidebar-accent"
                        @click="selectByEmployeeId(t.employee_id)">
                        {{ t.employee_id }}
                    </div>
                </div>
            </div>

            <div class="relative w-56">
                <Search class="absolute left-3 top-3 size-4 text-muted-foreground" />
                <Input v-model="nameSearch" placeholder="Teacher Name..." class="pl-9" />
                <div v-if="filteredNames.length"
                    class="absolute top-full left-0 right-0 z-50 mt-1 rounded-md border bg-background shadow-lg max-h-48 overflow-y-auto">
                    <div v-for="t in filteredNames" :key="t.id"
                        class="cursor-pointer px-4 py-2 text-sm hover:bg-sidebar-accent" @click="selectByName(t.name)">
                        {{ t.name }}
                    </div>
                </div>
            </div>

            <Button v-if="hasFilters" variant="ghost" size="sm" @click="clearFilters">
                <X class="mr-1 size-4" />
                Clear
            </Button>
        </div>

        <ManagementList title="Teachers" description="Manage teacher profiles and credentials." :items="teachers"
            :columns="columns" :actions="['edit', 'delete']" edit-route="/admin/teachers/:id/edit"
            @delete="handleDelete" />
    </div>
</template>
