<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ArrowLeft, Save } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

const props = defineProps<{
    exam: any;
    classes: { id: number; name: string }[];
}>();

const form = useForm({
    class_id: props.exam.class_id,
    name: props.exam.name,
    starts_on: props.exam.starts_on || '',
    ends_on: props.exam.ends_on || '',
    status: props.exam.status,
});

function submit() {
    form.put(`/admin/exams/${props.exam.id}`);
}
</script>

<template>
    <Head title="Edit Exam" />

    <div class="mx-auto max-w-2xl space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <Link href="/admin/exams" class="flex items-center gap-1 text-sm text-muted-foreground hover:text-foreground">
                    <ArrowLeft class="size-4" />
                    Back
                </Link>
                <h1 class="text-xl font-semibold">Edit Exam</h1>
            </div>
            <Button @click="submit" :disabled="form.processing">
                <Save class="mr-2 size-4" />
                Update Exam
            </Button>
        </div>

        <form @submit.prevent="submit">
            <Card>
                <CardHeader>
                    <CardTitle class="text-base">Exam Details</CardTitle>
                </CardHeader>
                <CardContent class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-2">
                        <Label>Class</Label>
                        <Select v-model="form.class_id">
                            <SelectTrigger>
                                <SelectValue placeholder="Select class" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="c in classes" :key="c.id" :value="c.id">{{ c.name }}</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <div class="space-y-2">
                        <Label for="name">Name *</Label>
                        <Input id="name" v-model="form.name" />
                        <p v-if="form.errors.name" class="text-xs text-destructive">{{ form.errors.name }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="starts_on">Start Date</Label>
                        <Input id="starts_on" v-model="form.starts_on" type="date" />
                    </div>
                    <div class="space-y-2">
                        <Label for="ends_on">End Date</Label>
                        <Input id="ends_on" v-model="form.ends_on" type="date" />
                    </div>
                    <div class="space-y-2 sm:col-span-2">
                        <Label>Status *</Label>
                        <Select v-model="form.status">
                            <SelectTrigger>
                                <SelectValue placeholder="Select status" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="draft">Draft</SelectItem>
                                <SelectItem value="published">Published</SelectItem>
                                <SelectItem value="completed">Completed</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                </CardContent>
            </Card>
        </form>
    </div>
</template>
