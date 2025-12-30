<script setup>
/**
 * テキストエリアコンポーネント
 */
defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    label: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: '',
    },
    rows: {
        type: [String, Number],
        default: 4,
    },
    error: {
        type: String,
        default: '',
    },
    required: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    id: {
        type: String,
        default: '',
    },
    maxlength: {
        type: [String, Number],
        default: null,
    },
});

defineEmits(['update:modelValue']);
</script>

<template>
    <div>
        <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700 mb-1">
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>
        <textarea
            :id="id"
            :value="modelValue"
            :placeholder="placeholder"
            :rows="rows"
            :required="required"
            :disabled="disabled"
            :maxlength="maxlength"
            @input="$emit('update:modelValue', $event.target.value)"
            :class="[
                'block w-full rounded-lg border px-3 py-2 text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-0 resize-y',
                error
                    ? 'border-red-300 focus:border-red-500 focus:ring-red-500'
                    : 'border-gray-300 focus:border-primary-500 focus:ring-primary-500',
                disabled ? 'bg-gray-100 cursor-not-allowed' : 'bg-white',
            ]"
        ></textarea>
        <div class="flex justify-between items-center mt-1">
            <p v-if="error" class="text-sm text-red-600">{{ error }}</p>
            <p v-if="maxlength" class="text-sm text-gray-400 ml-auto">
                {{ modelValue?.length || 0 }} / {{ maxlength }}
            </p>
        </div>
    </div>
</template>
