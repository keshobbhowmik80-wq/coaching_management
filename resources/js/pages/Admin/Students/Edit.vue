<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ArrowLeft, Save } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { computed } from 'vue';

const props = defineProps<{
    student: any;
    classes: { id: number; name: string }[];
    sections: { id: number; class_id: number; name: string }[];
}>();

const form = useForm({
    name: props.student.user.name,
    email: props.student.user.email,
    password: '',
    admission_no: props.student.admission_no,
    class_id: props.student.class_id,
    section_id: props.student.section_id,
    guardian_name: props.student.guardian_name || '',
    guardian_phone: props.student.guardian_phone || '',
    date_of_birth: props.student.date_of_birth || '',
    address: props.student.address || '',
    admitted_at: props.student.admitted_at || '',
});

const filteredSections = computed(() =>
    props.sections.filter((s) => s.class_id === form.class_id)
);

function submit() {
    form.put(`/admin/students/${props.student.id}`);
}
</script>

<template>
    <Head title="Edit Student" />

    <div class="mx-auto max-w-3xl space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <Link href="/admin/students" class="flex items-center gap-1 text-sm text-muted-foreground hover:text-foreground">
                    <ArrowLeft class="size-4" />
                    Back
                </Link>
                <h1 class="text-xl font-semibold">Edit Student</h1>
            </div>
            <Button @click="submit" :disabled="form.processing">
                <Save class="mr-2 size-4" />
                Update Student
            </Button>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <Card>
                <CardHeader>
                    <CardTitle class="text-base">Account Information</CardTitle>
                </CardHeader>
                <CardContent class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="name">Full Name *</Label>
                        <Input id="name" v-model="form.name" />
                        <p v-if="form.errors.name" class="text-xs text-destructive">{{ form.errors.name }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="email">Email *</Label>
                        <Input id="email" v-model="form.email" type="email" />
                        <p v-if="form.errors.email" class="text-xs text-destructive">{{ form.errors.email }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="password">Password (leave blank to keep current)</Label>
                        <Input id="password" v-model="form.password" type="password" />
                        <p v-if="form.errors.password" class="text-xs text-destructive">{{ form.errors.password }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="admission_no">Admission No *</Label>
                        <Input id="admission_no" v-model="form.admission_no" />
                        <p v-if="form.errors.admission_no" class="text-xs text-destructive">{{ form.errors.admission_no }}</p>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle class="text-base">Class Assignment</CardTitle>
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
                        <Label>Section</Label>
                        <Select v-model="form.section_id" :disabled="!form.class_id">
                            <SelectTrigger>
                                <SelectValue placeholder="Select section" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="s in filteredSections" :key="s.id" :value="s.id">{{ s.name }}</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle class="text-base">Guardian & Personal Details</CardTitle>
                </CardHeader>
                <CardContent class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="guardian_name">Guardian Name</Label>
                        <Input id="guardian_name" v-model="form.guardian_name" />
                    </div>
                    <div class="space-y-2">
                        <Label for="guardian_phone">Guardian Phone</Label>
                        <Input id="guardian_phone" v-model="form.guardian_phone" />
                    </div>
                    <div class="space-y-2">
                        <Label for="date_of_birth">Date of Birth</Label>
                        <Input id="date_of_birth" v-model="form.date_of_birth" type="date" />
                    </div>
                    <div class="space-y-2">
                        <Label for="admitted_at">Admission Date</Label>
                        <Input id="admitted_at" v-model="form.admitted_at" type="date" />
                    </div>
                    <div class="space-y-2 sm:col-span-2">
                        <Label for="address">Address</Label>
                        <Input id="address" v-model="form.address" />
                    </div>
                </CardContent>
            </Card>
        </form>
    </div>
</template>
