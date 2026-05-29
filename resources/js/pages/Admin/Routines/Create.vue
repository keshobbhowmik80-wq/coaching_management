<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ArrowLeft, Save, Plus, Trash2 } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { computed, ref } from 'vue';

const props = defineProps<{
    classes: { id: number; name: string }[];
    sections: { id: number; class_id: number; name: string }[];
    subjects: { id: number; class_id: number; name: string }[];
    teachers: { id: number; user_id: number; employee_id: string; user: { id: number; name: string } }[];
    exams: { id: number; name: string; class_id: number; coaching_class: { id: number; name: string } }[];
}>();

const days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

const form = useForm({
    name: '',
    type: 'class',
    class_id: null as number | null,
    section_id: null as number | null,
    exam_id: null as number | null,
    starts_on: '',
    ends_on: '',
    slots: [
        { day_of_week: '', subject_id: null, teacher_id: null, starts_at: '', ends_at: '', room: '' }
    ],
});

const filteredSections = computed(() =>
    props.sections.filter(s => s.class_id === form.class_id)
);

const filteredSubjects = computed(() =>
    props.subjects.filter(s => s.class_id === form.class_id)
);

function onTypeChange() {
    form.class_id = null;
    form.section_id = null;
    form.exam_id = null;
    form.starts_on = '';
    form.ends_on = '';
}

function onExamSelect(examId: number) {
    const exam = props.exams.find(e => e.id === examId);
    if (exam) {
        form.class_id = exam.class_id;
    }
}

function addSlot() {
    form.slots.push({ day_of_week: '', subject_id: null, teacher_id: null, starts_at: '', ends_at: '', room: '' });
}

function removeSlot(index: number) {
    form.slots.splice(index, 1);
}

function submit() {
    form.post('/admin/routines', {
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <Head title="Add Routine" />

    <div class="mx-auto max-w-3xl space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <Link href="/admin/routines" class="flex items-center gap-1 text-sm text-muted-foreground hover:text-foreground">
                    <ArrowLeft class="size-4" />
                    Back
                </Link>
                <h1 class="text-xl font-semibold">Add New Routine</h1>
            </div>
            <Button @click="submit" :disabled="form.processing">
                <Save class="mr-2 size-4" />
                Save Routine
            </Button>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <Card>
                <CardHeader>
                    <CardTitle class="text-base">Routine Details</CardTitle>
                </CardHeader>
                <CardContent class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-2 sm:col-span-2">
                        <Label for="name">Routine Name *</Label>
                        <Input id="name" v-model="form.name" placeholder="e.g., Class X Section A Weekly Routine" />
                        <p v-if="form.errors.name" class="text-xs text-destructive">{{ form.errors.name }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label>Type *</Label>
                        <Select v-model="form.type" @update:model-value="onTypeChange">
                            <SelectTrigger><SelectValue placeholder="Select type" /></SelectTrigger>
                            <SelectContent>
                                <SelectItem value="class">Class Routine</SelectItem>
                                <SelectItem value="exam">Exam Routine</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <template v-if="form.type === 'class'">
                        <div class="space-y-2">
                            <Label>Class *</Label>
                            <Select v-model="form.class_id">
                                <SelectTrigger><SelectValue placeholder="Select class" /></SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="c in classes" :key="c.id" :value="c.id">{{ c.name }}</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="space-y-2">
                            <Label>Section</Label>
                            <Select v-model="form.section_id" :disabled="!form.class_id">
                                <SelectTrigger><SelectValue placeholder="Select section" /></SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="s in filteredSections" :key="s.id" :value="s.id">{{ s.name }}</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </template>

                    <template v-if="form.type === 'exam'">
                        <div class="space-y-2 sm:col-span-2">
                            <Label>Exam *</Label>
                            <Select v-model="form.exam_id" @update:model-value="(val) => onExamSelect(Number(val))">
                                <SelectTrigger><SelectValue placeholder="Select exam" /></SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="e in exams" :key="e.id" :value="e.id">{{ e.name }} ({{ e.coaching_class?.name }})</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="space-y-2">
                            <Label for="starts_on">Start Date</Label>
                            <Input id="starts_on" v-model="form.starts_on" type="date" />
                        </div>
                        <div class="space-y-2">
                            <Label for="ends_on">End Date</Label>
                            <Input id="ends_on" v-model="form.ends_on" type="date" />
                        </div>
                    </template>
                </CardContent>
            </Card>

            <!-- Slots -->
            <Card v-for="(slot, index) in form.slots" :key="index">
                <CardHeader class="flex flex-row items-center justify-between">
                    <CardTitle class="text-base">Slot {{ index + 1 }}</CardTitle>
                    <Button v-if="form.slots.length > 1" variant="ghost" size="icon" @click="removeSlot(index)">
                        <Trash2 class="size-4 text-destructive" />
                    </Button>
                </CardHeader>
                <CardContent class="grid gap-4 sm:grid-cols-3">
                    <div class="space-y-2">
                        <Label>Day *</Label>
                        <Select v-model="slot.day_of_week">
                            <SelectTrigger><SelectValue placeholder="Select day" /></SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="d in days" :key="d" :value="d">{{ d }}</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <div class="space-y-2">
                        <Label>Subject</Label>
                        <Select v-model="slot.subject_id" :disabled="!form.class_id">
                            <SelectTrigger><SelectValue placeholder="Select subject" /></SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="s in filteredSubjects" :key="s.id" :value="s.id">{{ s.name }}</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <div class="space-y-2">
                        <Label>Teacher</Label>
                        <Select v-model="slot.teacher_id">
                            <SelectTrigger><SelectValue placeholder="Select teacher" /></SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="t in teachers" :key="t.id" :value="t.id">{{ t.user?.name }}</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <div class="space-y-2">
                        <Label>Start Time *</Label>
                        <Input v-model="slot.starts_at" type="time" />
                    </div>
                    <div class="space-y-2">
                        <Label>End Time *</Label>
                        <Input v-model="slot.ends_at" type="time" />
                    </div>
                    <div class="space-y-2">
                        <Label>Room</Label>
                        <Input v-model="slot.room" placeholder="e.g., Room 101" />
                    </div>
                </CardContent>
            </Card>

            <Button type="button" variant="outline" class="w-full" @click="addSlot">
                <Plus class="mr-2 size-4" />
                Add Slot
            </Button>
        </form>
    </div>
</template>
