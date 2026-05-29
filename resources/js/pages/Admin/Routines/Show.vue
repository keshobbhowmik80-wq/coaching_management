<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Clock, MapPin } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    routine: any;
}>();

const days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

const slotsByDay = computed(() => {
    const grouped: Record<string, any[]> = {};
    props.routine.slots.forEach((slot: any) => {
        if (!grouped[slot.day_of_week]) grouped[slot.day_of_week] = [];
        grouped[slot.day_of_week].push(slot);
    });
    return grouped;
});

function formatTime(time: string) {
    return time?.substring(0, 5);
}
</script>

<template>
    <Head :title="routine.name" />

    <div class="space-y-6">
        <div class="flex items-center gap-4">
            <Link href="/admin/routines" class="flex items-center gap-1 text-sm text-muted-foreground hover:text-foreground">
                <ArrowLeft class="size-4" />
                Back
            </Link>
        </div>

        <div class="space-y-2">
            <h1 class="text-2xl font-semibold">{{ routine.name }}</h1>
            <div class="flex items-center gap-3 text-sm text-muted-foreground">
                <span>{{ routine.coaching_class?.name }}</span>
                <span v-if="routine.type === 'class' && routine.section">• Section {{ routine.section.name }}</span>
                <span v-if="routine.type === 'exam'">• {{ routine.starts_on }} to {{ routine.ends_on }}</span>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg border">
            <table class="min-w-full divide-y text-sm">
                <thead class="bg-muted/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-muted-foreground w-28">Day</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-muted-foreground">Schedule</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <tr v-for="day in days" :key="day">
                        <td class="px-4 py-3 font-medium whitespace-nowrap align-top">{{ day }}</td>
                        <td class="px-4 py-3">
                            <div v-if="slotsByDay[day]" class="flex flex-wrap gap-3">
                                <div
                                    v-for="slot in slotsByDay[day]"
                                    :key="slot.id"
                                    class="rounded-md border bg-background p-3 min-w-[200px]"
                                >
                                    <p class="font-medium text-sm">{{ slot.subject?.name || '—' }}</p>
                                    <div class="mt-1 space-y-1 text-xs text-muted-foreground">
                                        <div class="flex items-center gap-1">
                                            <Clock class="size-3" />
                                            {{ formatTime(slot.starts_at) }} — {{ formatTime(slot.ends_at) }}
                                        </div>
                                        <p v-if="slot.teacher?.user?.name">{{ slot.teacher.user.name }}</p>
                                        <div v-if="slot.room" class="flex items-center gap-1">
                                            <MapPin class="size-3" />
                                            {{ slot.room }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span v-else class="text-muted-foreground text-xs">—</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
