<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2, Save, X } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { ref } from 'vue';

const props = defineProps<{
    grades: { id: number; min_percent: number; max_percent: number; grade: string; gpa: number }[];
}>();

const editingId = ref<number | null>(null);
const showForm = ref(false);

const form = useForm({
    min_percent: 0,
    max_percent: 100,
    grade: '',
    gpa: 0,
});

const editForm = useForm({
    min_percent: 0,
    max_percent: 100,
    grade: '',
    gpa: 0,
});

const deleteForm = useForm({});

function startEdit(grade: typeof props.grades[0]) {
    editingId.value = grade.id;
    editForm.min_percent = grade.min_percent;
    editForm.max_percent = grade.max_percent;
    editForm.grade = grade.grade;
    editForm.gpa = grade.gpa;
}

function cancelEdit() {
    editingId.value = null;
}

function saveEdit(id: number) {
    editForm.put(`/admin/grades/${id}`, {
        onSuccess: () => { editingId.value = null; },
    });
}

function addGrade() {
    form.post('/admin/grades', {
        onSuccess: () => {
            showForm.value = false;
            form.reset();
        },
    });
}

function deleteGrade(id: number) {
    if (confirm('Delete this grade?')) {
        deleteForm.delete(`/admin/grades/${id}`);
    }
}
</script>

<template>

    <Head title="Grades" />

    <div class="space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">Grade Settings</h1>
                <p class="text-sm text-muted-foreground">Configure grading system for marksheets.</p>
            </div>
            <Button v-if="!showForm" @click="showForm = true">
                <Plus class="mr-2 size-4" />
                Add Grade
            </Button>
        </div>

        <!-- Add Form -->
        <Card v-if="showForm">
            <CardHeader class="flex flex-row items-center justify-between">
                <CardTitle class="text-base">New Grade</CardTitle>
                <Button variant="ghost" size="icon" @click="showForm = false">
                    <X class="size-4" />
                </Button>
            </CardHeader>
            <CardContent>
                <form @submit.prevent="addGrade" class="grid grid-cols-4 gap-3 items-end">
                    <div class="space-y-1">
                        <Label>Min %</Label>
                        <Input v-model="form.min_percent" type="number" min="0" max="100" />
                    </div>
                    <div class="space-y-1">
                        <Label>Max %</Label>
                        <Input v-model="form.max_percent" type="number" min="0" max="100" />
                    </div>
                    <div class="space-y-1">
                        <Label>Grade</Label>
                        <Input v-model="form.grade" placeholder="A+" />
                    </div>
                    <div class="space-y-1">
                        <Label>GPA</Label>
                        <Input v-model="form.gpa" type="number" step="0.01" min="0" max="5" />
                    </div>
                    <Button type="submit" :disabled="form.processing" class="col-span-4">
                        <Save class="mr-2 size-4" /> Save
                    </Button>
                </form>
            </CardContent>
        </Card>

        <!-- Grades List -->
        <div class="overflow-hidden rounded-lg border">
            <table class="min-w-full divide-y text-sm">
                <thead class="bg-muted/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Min %</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Max %</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Grade</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">GPA</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <tr v-for="grade in grades" :key="grade.id" class="hover:bg-muted/30">
                        <template v-if="editingId === grade.id">
                            <td class="px-4 py-2"><Input v-model="editForm.min_percent" type="number" class="w-20" />
                            </td>
                            <td class="px-4 py-2"><Input v-model="editForm.max_percent" type="number" class="w-20" />
                            </td>
                            <td class="px-4 py-2"><Input v-model="editForm.grade" class="w-16" /></td>
                            <td class="px-4 py-2"><Input v-model="editForm.gpa" type="number" step="0.01"
                                    class="w-20" /></td>
                            <td class="px-4 py-2 text-right">
                                <div class="flex justify-end gap-1">
                                    <Button size="icon" variant="ghost" @click="saveEdit(grade.id)">
                                        <Save class="size-4" />
                                    </Button>
                                    <Button size="icon" variant="ghost" @click="cancelEdit">
                                        <X class="size-4" />
                                    </Button>
                                </div>
                            </td>
                        </template>
                        <template v-else>
                            <td class="px-4 py-2">{{ grade.min_percent }}%</td>
                            <td class="px-4 py-2">{{ grade.max_percent }}%</td>
                            <td class="px-4 py-2 font-medium">{{ grade.grade }}</td>
                            <td class="px-4 py-2">{{ grade.gpa }}</td>
                            <td class="px-4 py-2 text-right">
                                <div class="flex justify-end gap-1">
                                    <Button size="icon" variant="ghost" @click="startEdit(grade)">
                                        <Pencil class="size-3.5" />
                                    </Button>
                                    <Button size="icon" variant="ghost" @click="deleteGrade(grade.id)">
                                        <Trash2 class="size-3.5 text-destructive" />
                                    </Button>
                                </div>
                            </td>
                        </template>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
