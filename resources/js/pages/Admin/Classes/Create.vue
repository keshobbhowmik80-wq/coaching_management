<script setup lang="ts">
import { useForm, Link, Head } from '@inertiajs/vue3';
import { ArrowLeft, Save } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

const form = useForm({
    name: '',
    code: '',
    description: '',
});

function submit() {
    form.post('/admin/classes', {
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <Head title="Add-class" />
    <div class="mx-auto max-w-2xl space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <Link href="/admin/classes"
                    class="flex items-center gap-1 text-sm text-muted-foreground hover:text-foreground">
                    <ArrowLeft class="size-4" />
                    Back
                </Link>
                <h1 class="text-xl font-semibold">Add New Class</h1>
            </div>
            <Button @click="submit" :disabled="form.processing">
                <Save class="mr-2 size-4" />
                Save Class
            </Button>
        </div>

        <form @submit.prevent="submit">
            <Card>
                <CardHeader>
                    <CardTitle class="text-base">Class Details</CardTitle>
                </CardHeader>
                <CardContent class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="name">Name *</Label>
                        <Input id="name" v-model="form.name" placeholder="e.g., Class X" />
                        <p v-if="form.errors.name" class="text-xs text-destructive">{{ form.errors.name }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="code">Code *</Label>
                        <Input id="code" v-model="form.code" placeholder="e.g., X-2025" />
                        <p v-if="form.errors.code" class="text-xs text-destructive">{{ form.errors.code }}</p>
                    </div>
                    <div class="space-y-2 sm:col-span-2">
                        <Label for="description">Description</Label>
                        <Input id="description" v-model="form.description" placeholder="Optional description" />
                    </div>
                </CardContent>
            </Card>
        </form>
    </div>
</template>
