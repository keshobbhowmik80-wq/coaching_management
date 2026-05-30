<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Printer, Download } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { ref, watch } from 'vue';

const props = defineProps<{
    exams: { id: number; name: string; class_id: number }[];
    subjects: { id: number; name: string; full_marks: number }[];
    students: {
        student_id: number;
        name: string;
        admission_no: string;
        subjects: Record<string, { marks: number | null; full_marks: number; status: string; grade: string }>;
        total_obtained: number;
        total_full: number;
        total_grade: string;
    }[];
    filters: { exam_id: number | null };
}>();

const examId = ref(props.filters?.exam_id?.toString() || '');

watch(examId, () => {
    if (examId.value) {
        router.get('/admin/marks/marksheet', { exam_id: examId.value }, { preserveState: true, replace: true });
    }
});

function downloadPdf() {
    window.open(`/admin/marks/marksheet/pdf?exam_id=${examId.value}`, '_blank');
}

function printMarksheet() {
    window.print();
}
</script>

<template>

    <Head title="Marksheet" />

    <div class="space-y-5">
        <div class="print-hidden flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">Marksheet</h1>
                <p class="text-sm text-muted-foreground">View and export exam marksheets.</p>
            </div>
            <div class="flex items-center gap-2" v-if="examId">
                <Button variant="outline" @click="printMarksheet">
                    <Printer class="mr-2 size-4" /> Print
                </Button>
                <Button @click="downloadPdf">
                    <Download class="mr-2 size-4" /> PDF
                </Button>
            </div>
        </div>

        <div class="print-hidden">
            <Select v-model="examId">
                <SelectTrigger class="w-56">
                    <SelectValue placeholder="Select exam" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem v-for="e in exams" :key="e.id" :value="String(e.id)">{{ e.name }}</SelectItem>
                </SelectContent>
            </Select>
        </div>

        <div v-if="students.length" class="overflow-hidden rounded-lg border">
            <!-- Print Header -->
            <div class="hidden print:block text-center mb-4">
                <h1 class="text-xl font-bold">{{ $page.props.name || 'Coaching Center' }}</h1>
                <h2 class="text-lg font-semibold mt-1">Marksheet</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y text-sm">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">ID</th>
                            <th v-for="s in subjects" :key="s.id"
                                class="px-4 py-3 text-center text-xs font-semibold uppercase">
                                {{ s.name }} ({{ s.full_marks }})
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr v-for="student in students" :key="student.student_id" class="hover:bg-muted/30">
                            <td class="px-4 py-3 font-medium">{{ student.name }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ student.admission_no }}</td>
                            <td v-for="s in subjects" :key="s.id" class="px-4 py-3 text-center">
                                <template v-if="student.subjects[s.id]">
                                    <span v-if="student.subjects[s.id].status === 'absent'"
                                        class="text-red-600 font-medium">Absent</span>
                                    <span v-else-if="student.subjects[s.id].marks !== null">
                                        {{ student.subjects[s.id].marks }}
                                        <span class="text-xs text-muted-foreground ml-1">({{
                                            student.subjects[s.id].grade }})</span>
                                    </span>
                                    <span v-else class="text-muted-foreground">—</span>
                                </template>
                                <span v-else class="text-muted-foreground">—</span>
                            </td>
                            <td class="px-4 py-3 text-center font-medium">
                                {{ student.total_full > 0 ? student.total_obtained : '—' }}
                                <span v-if="student.total_full > 0" class="text-xs text-muted-foreground ml-1">({{
                                    student.total_grade }})</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <p v-else-if="examId" class="text-center text-muted-foreground py-12">No students found.</p>
    </div>
</template>

<style>
@media print {
    .print-hidden {
        display: none !important;
    }

    body {
        background: white !important;
    }

    @page {
        margin: 1cm;
    }
}
</style>
