<script setup>
/**
 * カードコンポーネント
 * 汎用的なコンテナ
 */
defineProps({
    padding: {
        type: String,
        default: 'md',
        validator: (value) => ['none', 'sm', 'md', 'lg'].includes(value),
    },
});

const paddingClasses = {
    none: '',
    sm: 'p-4',
    md: 'p-6',
    lg: 'p-8',
};
</script>

<template>
    <div :class="['bg-white rounded-lg shadow-md', paddingClasses[padding]]">
        <slot />
    </div>
</template>
