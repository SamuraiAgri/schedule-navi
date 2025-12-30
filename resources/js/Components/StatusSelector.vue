<script setup>
/**
 * 出欠選択ボタングループ
 * ○×△を選択できるボタン群
 */
const props = defineProps({
    modelValue: {
        type: String,
        default: null,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['update:modelValue']);

const options = [
    { value: 'ok', label: '○', color: 'green' },
    { value: 'maybe', label: '△', color: 'yellow' },
    { value: 'ng', label: '×', color: 'red' },
];

const getButtonClasses = (option, isSelected) => {
    const base = 'w-10 h-10 rounded-full font-bold text-lg transition-all focus:outline-none focus:ring-2 focus:ring-offset-1';
    
    if (isSelected) {
        switch (option.color) {
            case 'green':
                return `${base} bg-green-500 text-white focus:ring-green-500`;
            case 'yellow':
                return `${base} bg-yellow-500 text-white focus:ring-yellow-500`;
            case 'red':
                return `${base} bg-red-500 text-white focus:ring-red-500`;
        }
    }
    
    return `${base} bg-gray-100 text-gray-400 hover:bg-gray-200`;
};

const selectOption = (value) => {
    // トグル機能: 同じボタンを押すと選択解除
    emit('update:modelValue', value === props.modelValue ? null : value);
};
</script>

<template>
    <div class="inline-flex items-center gap-2">
        <button
            v-for="option in options"
            :key="option.value"
            type="button"
            :disabled="disabled"
            @click="selectOption(option.value)"
            :class="getButtonClasses(option, modelValue === option.value)"
            :title="option.label"
        >
            {{ option.label }}
        </button>
    </div>
</template>
