<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { Save } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Label } from '@/components/ui/label';
import { ref, computed, watch } from 'vue';

const props = defineProps<{
    exams: { id: number; name: string; class_id: number }[];
    subjects: { id: number; name: string; full_marks: number; pass_marks: number }[];
    students: {
        mark_id: number;
        student_id: number;
        name: string;
        admission_no: string;
        marks_obtained: number | null;
        status: string;
    }[];
    filters: { exam_id: number | null; subject_id: number | null };
}>();

const examId = ref(props.filters?.exam_id?.toString() || '');
const subjectId = ref(props.filters?.subject_id?.toString() || '');

const selectedSubject = computed(() =>
    props.subjects.find(s => s.id === Number(subjectId.value))
);

function applyFilter() {
    if (examId.value) {
        router.get('/admin/marks/entry', {
            exam_id: examId.value,
            subject_id: subjectId.value || undefined,
        }, { preserveState: true, replace: true });
    }
}

watch(examId, () => {
    subjectId.value = '';
    applyFilter();
});

watch(subjectId, applyFilter);

const marksForm = useForm({
    exam_id: props.filters.exam_id,
    subject_id: props.filters.subject_id,
    marks: props.students.map(s => ({
        mark_id: s.mark_id,
        marks_obtained: s.marks_obtained,
    })),
});

watch(() => props.students, (newStudents) => {
    marksForm.marks = newStudents.map(s => ({
        mark_id: s.mark_id,
        marks_obtained: s.marks_obtained,
    }));
}, { deep: true });

function updateMark(index: number, value: string) {
    marksForm.marks[index].marks_obtained = value ? Number(value) : null;
}

function submit() {
    marksForm.exam_id = props.filters.exam_id;
    marksForm.subject_id = props.filters.subject_id;
    marksForm.post('/admin/marks/bulk');
}
</script>

<template>

    <Head title="Marks Entry" />

    <div class="space-y-5">
        <div class="flex flex-col gap-1">
            <h1 class="text-2xl font-semibold">Marks Entry</h1>
            <p class="text-sm text-muted-foreground">Enter marks for students by exam and subject.</p>
        </div>

        <div class="flex flex-wrap items-end gap-3">
            <div class="space-y-1">
                <Label>Exam</Label>
                <Select v-model="examId">
                    <SelectTrigger class="w-56">
                        <SelectValue placeholder="Select exam" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="e in exams" :key="e.id" :value="String(e.id)">{{ e.name }}</SelectItem>
                    </SelectContent>
                </Select>
            </div>
            <div class="space-y-1">
                <Label>Subject</Label>
                <Select v-model="subjectId" :disabled="!examId">
                    <SelectTrigger class="w-48">
                        <SelectValue placeholder="Select subject" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="s in subjects" :key="s.id" :value="String(s.id)">
                            {{ s.name }} ({{ s.full_marks }})
                        </SelectItem>
                    </SelectContent>
                </Select>
            </div>
        </div>

        <div v-if="selectedSubject" class="text-sm text-muted-foreground">
            Full Marks: {{ selectedSubject.full_marks }} | Pass Marks: {{ selectedSubject.pass_marks }}
        </div>

        <div v-if="students.length" class="space-y-4">
            <div class="flex justify-end">
                <Button @click="submit" :disabled="marksForm.processing">
                    <Save class="mr-2 size-4" />
                    Save All Marks
                </Button>
            </div>

            <div class="overflow-hidden rounded-lg border">
                <table class="min-w-full divide-y text-sm">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Admission No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase w-40">Marks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr v-for="(student, index) in students" :key="student.mark_id" class="hover:bg-muted/30">
                            <td class="px-4 py-2 text-muted-foreground">{{ student.admission_no }}</td>
                            <td class="px-4 py-2 font-medium">{{ student.name }}</td>
                            <td class="px-4 py-2">
                                <span
                                    class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    Present
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <Input type="number" :min="0" :max="selectedSubject?.full_marks"
                                    :model-value="marksForm.marks[index]?.marks_obtained ?? ''"
                                    @input="(e) => updateMark(index, (e.target as HTMLInputElement).value)"
                                    class="w-24" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <p v-else-if="examId && subjectId" class="text-center text-muted-foreground py-12">
            No present students found for this exam and subject.
        </p>
    </div>
</template>
