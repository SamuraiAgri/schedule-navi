<script setup>
/**
 * カレンダー日付選択コンポーネント
 * シンプルなカレンダーUIで日付を選択
 */
import { ref, computed } from 'vue';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    selectedDates: {
        type: Set,
        default: () => new Set(),
    },
});

const emit = defineEmits(['select']);

// 現在表示中の年月
const currentDate = ref(new Date());

const currentYear = computed(() => currentDate.value.getFullYear());
const currentMonth = computed(() => currentDate.value.getMonth());

// 曜日ラベル
const weekdays = ['日', '月', '火', '水', '木', '金', '土'];

// 月の日数を取得
const getDaysInMonth = (year, month) => {
    return new Date(year, month + 1, 0).getDate();
};

// 月の最初の日の曜日を取得
const getFirstDayOfMonth = (year, month) => {
    return new Date(year, month, 1).getDay();
};

// カレンダーの日付配列を生成
const calendarDays = computed(() => {
    const year = currentYear.value;
    const month = currentMonth.value;
    const daysInMonth = getDaysInMonth(year, month);
    const firstDay = getFirstDayOfMonth(year, month);
    
    const days = [];
    
    // 前月の空白
    for (let i = 0; i < firstDay; i++) {
        days.push(null);
    }
    
    // 当月の日付
    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = formatDateStr(year, month, day);
        days.push({
            day,
            date: dateStr,
            isToday: isToday(year, month, day),
            isPast: isPast(year, month, day),
            isSelected: props.selectedDates.has(dateStr),
        });
    }
    
    return days;
});

// 日付文字列をフォーマット
const formatDateStr = (year, month, day) => {
    return `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
};

// 今日かどうか
const isToday = (year, month, day) => {
    const today = new Date();
    return (
        today.getFullYear() === year &&
        today.getMonth() === month &&
        today.getDate() === day
    );
};

// 過去の日付かどうか
const isPast = (year, month, day) => {
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const date = new Date(year, month, day);
    return date < today;
};

// 前月へ
const prevMonth = () => {
    currentDate.value = new Date(currentYear.value, currentMonth.value - 1, 1);
};

// 次月へ
const nextMonth = () => {
    currentDate.value = new Date(currentYear.value, currentMonth.value + 1, 1);
};

// 日付を選択
const selectDate = (day) => {
    if (!day || day.isPast) return;
    emit('select', new Date(day.date));
};

// 月表示のフォーマット
const monthLabel = computed(() => {
    return `${currentYear.value}年${currentMonth.value + 1}月`;
});
</script>

<template>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <!-- ヘッダー: 月切り替え -->
        <div class="flex items-center justify-between mb-4">
            <button
                type="button"
                @click="prevMonth"
                class="p-2 hover:bg-gray-100 rounded-full transition-colors"
            >
                <ChevronLeftIcon class="h-5 w-5 text-gray-600" />
            </button>
            <span class="text-lg font-semibold text-gray-900">{{ monthLabel }}</span>
            <button
                type="button"
                @click="nextMonth"
                class="p-2 hover:bg-gray-100 rounded-full transition-colors"
            >
                <ChevronRightIcon class="h-5 w-5 text-gray-600" />
            </button>
        </div>

        <!-- 曜日ヘッダー -->
        <div class="grid grid-cols-7 gap-1 mb-2">
            <div
                v-for="(weekday, index) in weekdays"
                :key="weekday"
                :class="[
                    'text-center text-xs font-medium py-1',
                    index === 0 ? 'text-red-500' : index === 6 ? 'text-blue-500' : 'text-gray-500'
                ]"
            >
                {{ weekday }}
            </div>
        </div>

        <!-- 日付グリッド -->
        <div class="grid grid-cols-7 gap-1">
            <template v-for="(day, index) in calendarDays" :key="index">
                <div v-if="!day" class="aspect-square"></div>
                <button
                    v-else
                    type="button"
                    @click="selectDate(day)"
                    :disabled="day.isPast"
                    :class="[
                        'aspect-square flex items-center justify-center text-sm rounded-full transition-colors',
                        day.isSelected
                            ? 'bg-primary-600 text-white font-semibold'
                            : day.isToday
                            ? 'bg-primary-100 text-primary-700 font-semibold'
                            : day.isPast
                            ? 'text-gray-300 cursor-not-allowed'
                            : 'hover:bg-gray-100 text-gray-700',
                        index % 7 === 0 && !day.isSelected && !day.isPast ? 'text-red-500' : '',
                        index % 7 === 6 && !day.isSelected && !day.isPast ? 'text-blue-500' : '',
                    ]"
                >
                    {{ day.day }}
                </button>
            </template>
        </div>
    </div>
</template>
