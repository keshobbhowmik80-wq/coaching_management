<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Clock, MapPin, Printer } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { computed, ref } from 'vue';

const props = defineProps<{
    routine: any;
}>();

const days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

const slotsByDay = computed(() => {
    if (props.routine.type === 'class') {
        const grouped: Record<string, any[]> = {};
        props.routine.slots.forEach((slot: any) => {
            if (!grouped[slot.day_of_week]) grouped[slot.day_of_week] = [];
            grouped[slot.day_of_week].push(slot);
        });
        return { type: 'day', grouped };
    }

    const grouped: Record<string, any[]> = {};
    props.routine.slots.forEach((slot: any) => {
        const key = slot.date || slot.day_of_week;
        if (!grouped[key]) grouped[key] = [];
        grouped[key].push(slot);
    });
    return { type: 'date', grouped };
});

const sortedDays = computed(() => {
    const dayOrder = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    const days = Object.keys(slotsByDay.value.grouped);
    return days.sort((a, b) => dayOrder.indexOf(a) - dayOrder.indexOf(b));
});

function formatTime(time: string) {
    return time?.substring(0, 5);
}

function formatDate(date: any) {
    if (!date) return '';
    const raw = typeof date === 'object' ? date.toString() : String(date);
    const cleaned = raw.split('T')[0];
    const [year, month, day] = cleaned.split('-');
    if (!year || !month || !day) return raw;
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const d = new Date(Number(year), Number(month) - 1, Number(day));
    const weekday = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][d.getDay()];
    return `${weekday}, ${months[Number(month) - 1]} ${Number(day)}, ${year}`;
}

function printRoutine() {
    window.print();
}
</script>

<template>

    <Head :title="routine.name" />

    <div class="space-y-6 print-hidden">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <Link href="/admin/routines"
                    class="flex items-center gap-1 text-sm text-muted-foreground hover:text-foreground">
                    <ArrowLeft class="size-4" />
                    Back
                </Link>
            </div>
            <Button variant="outline" @click="printRoutine">
                <Printer class="mr-2 size-4" />
                Print
            </Button>
        </div>

        <div class="space-y-2">
            <h1 class="text-2xl font-semibold">{{ routine.name }}</h1>
            <div class="flex items-center gap-3 text-sm text-muted-foreground">
                <p>Class: <span>{{ routine.coaching_class?.name }}</span></p>
                <span v-if="routine.type === 'class' && routine.section">• Section {{ routine.section.name }}</span>
                <span v-if="routine.type === 'exam'">• {{ formatDate(routine.starts_on) }} to {{
                    formatDate(routine.ends_on) }}</span>
            </div>
        </div>
    </div>

    <!-- Print-only content -->
    <div class="print-only text-black">
        <div class="print-header">
            <img src="/logo.png" alt="Logo" class="print-logo" />
            <h1>{{ $page.props.name + " " + 'Coaching Center' }}</h1>
            <h2>{{ routine.name }}</h2>
            <p>
                Class: {{ routine.coaching_class?.name }}
                <span v-if="routine.type === 'class' && routine.section"> • Section: {{ routine.section.name }}</span>
                <span v-if="routine.type === 'exam'"> • {{ formatDate(routine.starts_on) }} to {{
                    formatDate(routine.ends_on) }}</span>
            </p>
        </div>
    </div>

    <!-- Class Routine -->
    <!-- Class Routine -->
    <!-- Class Routine -->
    <!-- Class Routine -->
    <!-- Class Routine -->
    <div v-if="routine.type === 'class'" class="mt-6 overflow-hidden rounded-lg border">
        <table class="min-w-full divide-y text-sm">
            <thead class="bg-muted/50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-muted-foreground">Day</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-muted-foreground">
                        Subject
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <tr v-for="day in sortedDays" :key="day" class="bg-background">
                    <td class="px-4 py-3 font-medium text-foreground whitespace-nowrap align-middle">
                        {{ day }}
                    </td>
                    <td v-for="slot in slotsByDay.grouped[day]" :key="slot.id" class="px-4 py-2 align-top">
                        <div class="space-y-1">
                            <p class="font-medium text-sm">{{ slot.subject?.name || '—' }}</p>
                            <p class="text-xs text-muted-foreground">{{ formatTime(slot.starts_at) }} — {{
                                formatTime(slot.ends_at) }}</p>
                            <p class="text-xs text-muted-foreground">{{ slot.teacher?.user?.name || '—' }}</p>
                            <p v-if="slot.room" class="text-xs text-muted-foreground">{{ slot.room }}</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Exam Routine -->
    <div v-else class="mt-6 overflow-hidden rounded-lg border">
        <table class="min-w-full divide-y text-sm">
            <thead class="bg-muted/50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-muted-foreground">Date / Time
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-muted-foreground">Subject</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-muted-foreground">Room</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <template v-for="(slots, dateKey) in slotsByDay.grouped" :key="dateKey">
                    <tr v-for="(slot, i) in slots" :key="slot.id" class="bg-background">
                        <td class="px-4 py-2 text-muted-foreground">
                            <template v-if="i === 0">
                                <p class="font-medium text-foreground">{{ formatDate(dateKey) }}</p>
                            </template>
                            {{ formatTime(slot.starts_at) }} — {{ formatTime(slot.ends_at) }}
                        </td>
                        <td class="px-4 py-2">
                            {{ slot.subject?.name || '—' }}
                            <span v-if="slot.teacher?.user?.name" class="text-xs text-muted-foreground block">
                                {{ slot.teacher.user.name }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-muted-foreground">{{ slot.room || '—' }}</td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>

<style>
@media screen {
    .print-only {
        display: none;
    }
}

@media print {

    /* Hide everything first */
    body {
        visibility: hidden;
        background: white !important;
        margin: 0;
        padding: 0;
    }

    body * {
        visibility: hidden;
    }

    /* Show the print-only container - centered */
    .print-only,
    .print-only * {
        visibility: visible !important;
    }

    .print-only {
        display: block !important;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
        padding: 20px 0;
        margin: 0 auto;
        text-align: center;
    }

    .print-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .print-header h1 {
        font-size: 22px;
        font-weight: 700;
        margin: 0 0 8px;
    }

    .print-header h2 {
        font-size: 17px;
        font-weight: 600;
        margin: 0 0 4px;
    }

    .print-header p {
        font-size: 13px;
        color: #555;
        margin: 0;
    }

    /* Show the tables - centered */
    table {
        visibility: visible !important;
        display: table;
        margin: 30px auto 0 auto;
        width: auto;
        min-width: 80%;
        max-width: 95%;
        border-collapse: collapse;
    }

    table * {
        visibility: visible !important;
    }

    th {
        background: #f0f0f0 !important;
        font-size: 12px;
        padding: 8px 12px;
        text-align: left;
    }

    td {
        font-size: 12px;
        padding: 8px 12px;
        background: white !important;
        text-align: left;
    }

    .print-hidden {
        display: none !important;
    }

    @page {
        margin: 1cm;
    }
}
</style>
