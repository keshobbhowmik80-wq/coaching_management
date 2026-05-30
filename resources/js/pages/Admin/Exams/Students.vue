<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';

const props = defineProps<{
    exam: any;
    subjects: { id: number; name: string }[];
    students: {
        student_id: number;
        name: string;
        admission_no: string;
        subjects: Record<string, string>;
    }[];
}>();

const toggleForm = useForm({
    student_id: 0,
    subject_id: 0,
    status: '',
});

function toggle(studentId: number, subjectId: number, currentStatus: string) {
    const newStatus = currentStatus === 'present' ? 'absent' : 'present';
    toggleForm.student_id = studentId;
    toggleForm.subject_id = subjectId;
    toggleForm.status = newStatus;
    toggleForm.patch(`/admin/exams/${props.exam.id}/students/toggle`, {
        preserveScroll: true,
        preserveState: true,
    });
}
</script>

<template>

    <Head :title="`${exam.name} — Students`" />

    <div class="space-y-5">
        <div class="flex items-center gap-4">
            <Link href="/admin/exams"
                class="flex items-center gap-1 text-sm text-muted-foreground hover:text-foreground">
                <ArrowLeft class="size-4" />
                Back to Exams
            </Link>
        </div>

        <div class="space-y-1">
            <h1 class="text-2xl font-semibold">{{ exam.name }}</h1>
            <p class="text-sm text-muted-foreground">
                {{ exam.coaching_class?.name }} — {{ students.length }} students
            </p>
        </div>

        <div class="overflow-hidden rounded-lg border">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y text-sm">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Student</th>
                            <th v-for="subject in subjects" :key="subject.id"
                                class="px-4 py-3 text-center text-xs font-semibold uppercase w-24">
                                {{ subject.name }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr v-for="student in students" :key="student.student_id" class="hover:bg-muted/30">
                            <td class="px-4 py-3">
                                <p class="font-medium">{{ student.name }}</p>
                                <p class="text-xs text-muted-foreground">{{ student.admission_no }}</p>
                            </td>
                            <td v-for="subject in subjects" :key="subject.id"
                                class="px-4 py-3 text-center cursor-pointer select-none"
                                @click="toggle(student.student_id, subject.id, student.subjects[subject.id] || 'present')">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium" :class="(student.subjects[subject.id] || 'present') === 'present'
                                    ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                                    : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'">
                                    {{ (student.subjects[subject.id] || 'present') === 'present' ? 'Present' : 'Absent'
                                    }}
                                </span>
                            </td>
                        </tr>
                        <tr v-if="!students.length">
                            <td :colspan="subjects.length + 1" class="px-4 py-8 text-center text-muted-foreground">
                                No students assigned yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
