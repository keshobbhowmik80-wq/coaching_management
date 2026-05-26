import { router, usePage } from '@inertiajs/vue3';
import type { ComputedRef, Ref } from 'vue';
import { computed, ref, watch } from 'vue';
import { update } from '@/routes/user/settings/theme';
import type { Appearance, ResolvedAppearance } from '@/types';

export type { Appearance, ResolvedAppearance };

export type UseAppearanceReturn = {
    appearance: Ref<Appearance>;
    resolvedAppearance: ComputedRef<ResolvedAppearance>;
    updateAppearance: (value: Appearance) => void;
    processing: Ref<boolean>;
};

const appearance = ref<Appearance>('light');
const processing = ref(false);

export function updateTheme(value: Appearance): void {
    if (typeof document === 'undefined') {
        return;
    }

    document.documentElement.classList.toggle('dark', value === 'dark');
    document.documentElement.dataset.theme = value;
}

export function initializeTheme(): void {
    if (typeof document === 'undefined') {
        return;
    }

    const theme = document.documentElement.dataset.theme === 'dark' ? 'dark' : 'light';

    appearance.value = theme;
    updateTheme(theme);
}

export function useAppearance(): UseAppearanceReturn {
    const page = usePage();

    watch(
        () => page.props.settings?.theme,
        (theme) => {
            const resolvedTheme = theme === 'dark' ? 'dark' : 'light';

            appearance.value = resolvedTheme;
            updateTheme(resolvedTheme);
        },
        { immediate: true },
    );

    const resolvedAppearance = computed<ResolvedAppearance>(() => appearance.value);

    function updateAppearance(value: Appearance): void {
        appearance.value = value;
        updateTheme(value);
        processing.value = true;

        router.put(
            update.url(),
            { theme: value },
            {
                preserveScroll: true,
                preserveState: true,
                onFinish: () => {
                    processing.value = false;
                },
                onError: () => {
                    const fallbackTheme = page.props.settings?.theme === 'dark' ? 'dark' : 'light';

                    appearance.value = fallbackTheme;
                    updateTheme(fallbackTheme);
                },
            },
        );
    }

    return {
        appearance,
        resolvedAppearance,
        updateAppearance,
        processing,
    };
}
