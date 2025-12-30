<script setup>
/**
 * 出欠ステータスアイコンコンポーネント
 * ○×△を色分けして表示
 */
import { CheckCircleIcon, XCircleIcon, MinusCircleIcon } from '@heroicons/vue/24/solid';

defineProps({
    status: {
        type: String,
        default: null,
        validator: (value) => [null, 'ok', 'ng', 'maybe'].includes(value),
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value),
    },
    showLabel: {
        type: Boolean,
        default: false,
    },
});

const statusConfig = {
    ok: {
        icon: CheckCircleIcon,
        label: '○',
        textClass: 'text-green-600',
        bgClass: 'bg-green-100',
    },
    ng: {
        icon: XCircleIcon,
        label: '×',
        textClass: 'text-red-600',
        bgClass: 'bg-red-100',
    },
    maybe: {
        icon: MinusCircleIcon,
        label: '△',
        textClass: 'text-yellow-600',
        bgClass: 'bg-yellow-100',
    },
};

const sizeClasses = {
    sm: 'h-5 w-5',
    md: 'h-6 w-6',
    lg: 'h-8 w-8',
};
</script>

<template>
    <span v-if="status && statusConfig[status]" class="inline-flex items-center gap-1">
        <component
            :is="statusConfig[status].icon"
            :class="[sizeClasses[size], statusConfig[status].textClass]"
        />
        <span v-if="showLabel" :class="['text-sm font-medium', statusConfig[status].textClass]">
            {{ statusConfig[status].label }}
        </span>
    </span>
    <span v-else class="text-gray-300">-</span>
</template>
