<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ArrowLeft, Save } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

const form = useForm({
    name: '',
    email: '',
    password: '',
    employee_id: '',
    phone: '',
    qualification: '',
    joined_at: new Date().toISOString().split('T')[0],
    address: '',
});

function submit() {
    form.post('/admin/teachers', {
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <Head title="Add Teacher" />

    <div class="mx-auto max-w-3xl space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <Link href="/admin/teachers" class="flex items-center gap-1 text-sm text-muted-foreground hover:text-foreground">
                    <ArrowLeft class="size-4" />
                    Back
                </Link>
                <h1 class="text-xl font-semibold">Add New Teacher</h1>
            </div>
            <Button @click="submit" :disabled="form.processing">
                <Save class="mr-2 size-4" />
                Save Teacher
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
                        <Input id="name" v-model="form.name" placeholder="John Doe" />
                        <p v-if="form.errors.name" class="text-xs text-destructive">{{ form.errors.name }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="email">Email *</Label>
                        <Input id="email" v-model="form.email" type="email" placeholder="teacher@example.com" />
                        <p v-if="form.errors.email" class="text-xs text-destructive">{{ form.errors.email }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="password">Password *</Label>
                        <Input id="password" v-model="form.password" type="password" placeholder="Minimum 8 characters" />
                        <p v-if="form.errors.password" class="text-xs text-destructive">{{ form.errors.password }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="employee_id">Employee ID *</Label>
                        <Input id="employee_id" v-model="form.employee_id" placeholder="e.g., EMP-2025-001" />
                        <p v-if="form.errors.employee_id" class="text-xs text-destructive">{{ form.errors.employee_id }}</p>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle class="text-base">Professional Details</CardTitle>
                </CardHeader>
                <CardContent class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="phone">Phone</Label>
                        <Input id="phone" v-model="form.phone" placeholder="Phone number" />
                    </div>
                    <div class="space-y-2">
                        <Label for="qualification">Qualification</Label>
                        <Input id="qualification" v-model="form.qualification" placeholder="e.g., M.Sc. Mathematics" />
                    </div>
                    <div class="space-y-2">
                        <Label for="joined_at">Joining Date</Label>
                        <Input id="joined_at" v-model="form.joined_at" type="date" />
                    </div>
                    <div class="space-y-2 sm:col-span-2">
                        <Label for="address">Address</Label>
                        <Input id="address" v-model="form.address" placeholder="Full address" />
                    </div>
                </CardContent>
            </Card>
        </form>
    </div>
</template>
