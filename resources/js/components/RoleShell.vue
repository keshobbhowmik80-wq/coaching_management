<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronDown, LogOut, Menu, PanelLeftClose, PanelLeftOpen, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import ThemeToggle from '@/components/ThemeToggle.vue';

type NavItem = {
    title: string;
    href: string;
};

type NavGroup = {
    title: string;
    items: NavItem[];
};

const props = defineProps<{
    roleName: string;
    title: string;
    subtitle: string;
    dashboardHref: string;
    accentClass: string;
    groups: NavGroup[];
}>();

const page = usePage();
const sidebarOpen = ref(false);
const sidebarCollapsed = ref(false);
const expandedGroups = ref<Record<string, boolean>>(
    props.groups.reduce<Record<string, boolean>>((groups, group) => {
        groups[group.title] = true;

        return groups;
    }, {}),
);

const currentUrl = computed(() => page.url.split('?')[0]);

function isActive(href: string): boolean {
    return currentUrl.value === href || currentUrl.value.startsWith(`${href}/`);
}

function toggleGroup(title: string): void {
    expandedGroups.value[title] = !expandedGroups.value[title];
}
</script>

<template>
    <div class="min-h-screen bg-muted/30 text-foreground">
        <div v-if="sidebarOpen" class="fixed inset-0 z-40 bg-black/40 lg:hidden" @click="sidebarOpen = false" />

        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-sidebar-border bg-background transition-transform duration-200 lg:translate-x-0"
            :class="[
                sidebarOpen ? 'translate-x-0' : '-translate-x-full',
                sidebarCollapsed ? 'lg:w-20' : 'lg:w-72',
            ]"
        >
            <div class="flex min-h-16 items-center justify-between gap-3 border-b border-sidebar-border px-4">
                <Link :href="dashboardHref" class="flex min-w-0 items-center gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-lg text-sm font-bold text-white" :class="accentClass">
                        {{ roleName.charAt(0) }}
                    </span>
                    <span v-if="!sidebarCollapsed" class="min-w-0">
                        <span class="block truncate text-sm font-semibold">{{ roleName }}</span>
                        <span class="block truncate text-xs text-muted-foreground">Management Portal</span>
                    </span>
                </Link>

                <button type="button" class="rounded-md p-2 hover:bg-sidebar-accent lg:hidden" @click="sidebarOpen = false">
                    <X class="size-5" />
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-4">
                <div v-for="group in groups" :key="group.title" class="mb-3">
                    <button
                        type="button"
                        class="flex min-h-10 w-full items-center justify-between rounded-md px-3 text-left text-xs font-semibold uppercase text-muted-foreground hover:bg-sidebar-accent"
                        @click="toggleGroup(group.title)"
                    >
                        <span v-if="!sidebarCollapsed">{{ group.title }}</span>
                        <span v-else>{{ group.title.charAt(0) }}</span>
                        <ChevronDown v-if="!sidebarCollapsed" class="size-4 transition-transform" :class="{ '-rotate-90': !expandedGroups[group.title] }" />
                    </button>

                    <div v-show="expandedGroups[group.title] || sidebarCollapsed" class="mt-1 grid gap-1">
                        <Link
                            v-for="item in group.items"
                            :key="item.href"
                            :href="item.href"
                            class="flex min-h-10 items-center rounded-md px-3 text-sm font-medium transition"
                            :class="isActive(item.href) ? `${accentClass} text-white shadow-sm` : 'text-muted-foreground hover:bg-sidebar-accent hover:text-foreground'"
                            :title="sidebarCollapsed ? item.title : undefined"
                            @click="sidebarOpen = false"
                        >
                            <span v-if="sidebarCollapsed" class="mx-auto">{{ item.title.charAt(0) }}</span>
                            <span v-else>{{ item.title }}</span>
                        </Link>
                    </div>
                </div>
            </nav>

            <div class="border-t border-sidebar-border p-3">
                <Link
                    href="/logout"
                    method="post"
                    as="button"
                    class="flex min-h-10 w-full items-center justify-center gap-2 rounded-md border border-sidebar-border px-3 text-sm font-medium hover:bg-sidebar-accent"
                >
                    <LogOut class="size-4" />
                    <span v-if="!sidebarCollapsed">Logout</span>
                </Link>
            </div>
        </aside>

        <div class="transition-[padding] duration-200" :class="sidebarCollapsed ? 'lg:pl-20' : 'lg:pl-72'">
            <header class="sticky top-0 z-30 border-b border-sidebar-border bg-background/95 backdrop-blur">
                <div class="flex min-h-16 items-center justify-between gap-3 px-4 sm:px-6">
                    <div class="flex min-w-0 items-center gap-3">
                        <button type="button" class="rounded-md p-2 hover:bg-sidebar-accent lg:hidden" @click="sidebarOpen = true">
                            <Menu class="size-5" />
                        </button>
                        <button type="button" class="hidden rounded-md p-2 hover:bg-sidebar-accent lg:inline-flex" @click="sidebarCollapsed = !sidebarCollapsed">
                            <PanelLeftOpen v-if="sidebarCollapsed" class="size-5" />
                            <PanelLeftClose v-else class="size-5" />
                        </button>
                        <div class="min-w-0">
                            <p class="truncate text-xs font-semibold uppercase text-muted-foreground">{{ subtitle }}</p>
                            <h1 class="truncate text-lg font-semibold">{{ title }}</h1>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <ThemeToggle />
                        <Link :href="dashboardHref" class="hidden rounded-md border border-sidebar-border px-3 py-2 text-sm font-medium hover:bg-sidebar-accent sm:inline-flex">
                            Dashboard
                        </Link>
                    </div>
                </div>
            </header>

            <main class="px-4 py-5 sm:px-6 lg:py-6">
                <slot />
            </main>
        </div>
    </div>
</template>
