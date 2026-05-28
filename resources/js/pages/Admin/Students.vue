<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import ManagementList from '@/components/ManagementList.vue';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Search, X } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { ref, computed, watch } from 'vue';

const props = defineProps<{
    students: any;
    classes: { id: number; name: string }[];
    sections: { id: number; class_id: number; name: string }[];
    filters: { search?: string; class_id?: string; section_id?: string };
    allStudents: { id: number; admission_no: string; name: string }[];
}>();

const columns = [
    { key: 'admission_no', label: 'Admission No' },
    { key: 'user.name', label: 'Name' },
    { key: 'user.email', label: 'Email' },
    { key: 'coaching_class.name', label: 'Class' },
    { key: 'section.name', label: 'Section' },
    { key: 'guardian_phone', label: 'Guardian Phone' },
];

const deleteForm = useForm({});

const search = ref(props.filters?.search || '');
const classId = ref(props.filters?.class_id || 'all');
const sectionId = ref(props.filters?.section_id || 'all');

// Searchable selects
const admissionNoSearch = ref('');
const nameSearch = ref('');

const filteredAdmissionNos = computed(() => {
    if (!admissionNoSearch.value) return [];

    const q = admissionNoSearch.value.toLowerCase();

    return props.allStudents
        .filter(s => s.admission_no.toLowerCase().includes(q))
        .slice(0, 10);
});

const filteredNames = computed(() => {
    if (!nameSearch.value) return [];

    const q = nameSearch.value.toLowerCase();

    return props.allStudents
        .filter(s => s.name.toLowerCase().includes(q))
        .slice(0, 10);
});

function selectByAdmissionNo(admissionNo: string) {
    search.value = admissionNo;
    admissionNoSearch.value = '';
    applyFilters();
}

function selectByName(name: string) {
    search.value = name;
    nameSearch.value = '';
    applyFilters();
}

function applyFilters() {
    router.get('/admin/students', {
        search: search.value || undefined,
        class_id: classId.value !== 'all' ? classId.value : undefined,
        section_id: sectionId.value !== 'all' ? sectionId.value : undefined,
    }, { preserveState: true, replace: true });
}

watch([classId, sectionId], () => {
    applyFilters();
});

watch(classId, () => {
    sectionId.value = '';
});

const filteredSections = computed(() =>
    classId.value === 'all'
        ? props.sections
        : props.sections.filter((s) => String(s.class_id) === classId.value)
);

function clearFilters() {
    search.value = '';
    classId.value = 'all';
    sectionId.value = 'all';
    admissionNoSearch.value = '';
    nameSearch.value = '';
    router.get('/admin/students', {}, { replace: true });
}

function handleDelete(id: number) {
    if (confirm('Are you sure you want to delete this student?')) {
        deleteForm.delete(`/admin/students/${id}`);
    }
}

const hasFilters = computed(() =>
    search.value ||
    classId.value !== 'all' ||
    sectionId.value !== 'all'
);
</script>

<template>

    <Head title="Students" />

    <div class="space-y-5">
        <div class="flex flex-wrap items-end gap-3">
            <!-- Admission No Autocomplete -->
            <div class="relative w-48">
                <Search class="absolute left-3 top-3 size-4 text-muted-foreground" />
                <Input v-model="admissionNoSearch" placeholder="Admission No..." class="pl-9" />
                <div v-if="filteredAdmissionNos.length"
                    class="absolute top-full left-0 right-0 z-50 mt-1 rounded-md border bg-background shadow-lg max-h-48 overflow-y-auto">
                    <div v-for="s in filteredAdmissionNos" :key="s.id"
                        class="cursor-pointer px-4 py-2 text-sm hover:bg-sidebar-accent"
                        @click="selectByAdmissionNo(s.admission_no)">
                        {{ s.admission_no }}
                    </div>
                </div>
            </div>

            <!-- Name Autocomplete -->
            <div class="relative w-56">
                <Search class="absolute left-3 top-3 size-4 text-muted-foreground" />
                <Input v-model="nameSearch" placeholder="Student Name..." class="pl-9" />
                <div v-if="filteredNames.length"
                    class="absolute top-full left-0 right-0 z-50 mt-1 rounded-md border bg-background shadow-lg max-h-48 overflow-y-auto">
                    <div v-for="s in filteredNames" :key="s.id"
                        class="cursor-pointer px-4 py-2 text-sm hover:bg-sidebar-accent" @click="selectByName(s.name)">
                        {{ s.name }}
                    </div>
                </div>
            </div>

            <Select v-model="classId">
                <SelectTrigger class="w-40">
                    <SelectValue placeholder="All Classes" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">All Classes</SelectItem>
                    <SelectItem v-for="c in classes" :key="c.id" :value="String(c.id)">{{ c.name }}</SelectItem>
                </SelectContent>
            </Select>

            <Select v-model="sectionId" :disabled="!classId">
                <SelectTrigger class="w-40">
                    <SelectValue placeholder="All Sections" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">All Sections</SelectItem>
                    <SelectItem v-for="s in filteredSections" :key="s.id" :value="String(s.id)">{{ s.name }}
                    </SelectItem>
                </SelectContent>
            </Select>

            <Button v-if="hasFilters" variant="ghost" size="sm" @click="clearFilters">
                <X class="mr-1 size-4" />
                Clear
            </Button>
        </div>

        <ManagementList title="Students" description="Manage student accounts and academic placement." :items="students"
            :columns="columns" :actions="['edit', 'delete']" edit-route="/admin/students/:id/edit"
            @delete="handleDelete" />
    </div>
</template>
