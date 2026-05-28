<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ArrowLeft, Save } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

const props = defineProps<{
    subject: any;
    classes: { id: number; name: string }[];
}>();

const form = useForm({
    class_id: props.subject.class_id,
    name: props.subject.name,
    code: props.subject.code,
    full_marks: props.subject.full_marks,
    pass_marks: props.subject.pass_marks,
});

function submit() {
    form.put(`/admin/subjects/${props.subject.id}`);
}
</script>

<template>
    <Head title="Edit Subject" />

    <div class="mx-auto max-w-2xl space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <Link href="/admin/subjects" class="flex items-center gap-1 text-sm text-muted-foreground hover:text-foreground">
                    <ArrowLeft class="size-4" />
                    Back
                </Link>
                <h1 class="text-xl font-semibold">Edit Subject</h1>
            </div>
            <Button @click="submit" :disabled="form.processing">
                <Save class="mr-2 size-4" />
                Update Subject
            </Button>
        </div>

        <form @submit.prevent="submit">
            <Card>
                <CardHeader>
                    <CardTitle class="text-base">Subject Details</CardTitle>
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
                        <Label for="code">Code *</Label>
                        <Input id="code" v-model="form.code" />
                        <p v-if="form.errors.code" class="text-xs text-destructive">{{ form.errors.code }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="full_marks">Full Marks *</Label>
                        <Input id="full_marks" v-model="form.full_marks" type="number" min="1" />
                        <p v-if="form.errors.full_marks" class="text-xs text-destructive">{{ form.errors.full_marks }}</p>
                    </div>
                    <div class="space-y-2 sm:col-span-2">
                        <Label for="pass_marks">Pass Marks *</Label>
                        <Input id="pass_marks" v-model="form.pass_marks" type="number" min="0" />
                        <p v-if="form.errors.pass_marks" class="text-xs text-destructive">{{ form.errors.pass_marks }}</p>
                    </div>
                </CardContent>
            </Card>
        </form>
    </div>
</template>
