<script setup>
/**
 * イベント編集ページ
 * イベント情報と候補日程の編集
 */
import { ref, computed } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from '@/Components/Card.vue';
import Button from '@/Components/Button.vue';
import Input from '@/Components/Input.vue';
import Textarea from '@/Components/Textarea.vue';
import DatePicker from '@/Components/DatePicker.vue';
import {
    CalendarDaysIcon,
    TrashIcon,
    ArrowDownTrayIcon,
    ExclamationTriangleIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
});

// フォームデータ
const form = ref({
    title: props.event.title,
    description: props.event.description || '',
    notify_email: props.event.notifyEmail || '',
    is_time_required: props.event.isTimeRequired,
    is_anonymous_allowed: props.event.isAnonymousAllowed,
    deadline_at: props.event.deadlineAt || '',
    dates: props.event.dates.map(d => ({
        date: d.date,
        time_from: d.time_from || '',
        time_to: d.time_to || '',
    })),
});

const errors = ref({});
const isSubmitting = ref(false);
const showDeleteModal = ref(false);

// 日付を追加
const addDate = (date) => {
    const dateStr = formatDate(date);
    
    if (form.value.dates.some(d => d.date === dateStr)) {
        return;
    }
    
    form.value.dates.push({
        date: dateStr,
        time_from: '',
        time_to: '',
    });
    
    form.value.dates.sort((a, b) => a.date.localeCompare(b.date));
};

// 日付を削除
const removeDate = (index) => {
    form.value.dates.splice(index, 1);
};

// 日付フォーマット
const formatDate = (date) => {
    const d = new Date(date);
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
};

const formatDisplayDate = (dateStr) => {
    const date = new Date(dateStr);
    const weekdays = ['日', '月', '火', '水', '木', '金', '土'];
    const month = date.getMonth() + 1;
    const day = date.getDate();
    const weekday = weekdays[date.getDay()];
    return `${month}/${day}（${weekday}）`;
};

// 選択済み日付
const selectedDates = computed(() => new Set(form.value.dates.map(d => d.date)));

// 更新
const submit = () => {
    if (isSubmitting.value) return;
    
    isSubmitting.value = true;
    errors.value = {};
    
    router.put(`/e/${props.event.uuid}?key=${props.event.editKey}`, form.value, {
        onError: (e) => {
            errors.value = e;
        },
        onFinish: () => {
            isSubmitting.value = false;
        },
    });
};

// 削除
const deleteEvent = () => {
    router.delete(`/e/${props.event.uuid}?key=${props.event.editKey}`);
};

// CSV出力
const exportCsv = () => {
    window.location.href = `/e/${props.event.uuid}/export?key=${props.event.editKey}`;
};
</script>

<template>
    <Head>
        <title>{{ event.title }}を編集 - Schedule-Navi</title>
        <meta name="robots" content="noindex, nofollow" />
    </Head>
    
    <AppLayout>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">イベントを編集</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        イベント情報や候補日程を変更できます
                    </p>
                </div>
                <div class="flex gap-2">
                    <Button @click="exportCsv" variant="ghost" size="sm">
                        <ArrowDownTrayIcon class="h-4 w-4 mr-1" />
                        CSV出力
                    </Button>
                    <Link :href="`/e/${event.uuid}`">
                        <Button variant="secondary" size="sm">
                            イベントを見る
                        </Button>
                    </Link>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- 基本情報 -->
                <Card>
                    <div class="space-y-4">
                        <Input
                            v-model="form.title"
                            label="イベント名"
                            :error="errors.title"
                            required
                            id="title"
                        />

                        <Textarea
                            v-model="form.description"
                            label="説明（任意）"
                            :rows="3"
                            :maxlength="5000"
                            :error="errors.description"
                            id="description"
                        />

                        <Input
                            v-model="form.notify_email"
                            type="email"
                            label="通知先メールアドレス"
                            :error="errors.notify_email"
                            id="notify_email"
                        />

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                回答期限
                            </label>
                            <input
                                v-model="form.deadline_at"
                                type="datetime-local"
                                class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:ring-primary-500"
                            />
                        </div>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                v-model="form.is_anonymous_allowed"
                                type="checkbox"
                                class="h-4 w-4 text-primary-600 rounded border-gray-300"
                            />
                            <span class="text-sm text-gray-700">匿名での回答を許可</span>
                        </label>
                    </div>
                </Card>

                <!-- 候補日程 -->
                <Card>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <CalendarDaysIcon class="h-5 w-5 text-primary-600" />
                        候補日程
                    </h2>

                    <DatePicker
                        :selected-dates="selectedDates"
                        @select="addDate"
                        class="mb-4"
                    />

                    <div v-if="form.dates.length > 0" class="space-y-2">
                        <div class="text-sm font-medium text-gray-700 mb-2">
                            選択した日程（{{ form.dates.length }}件）
                        </div>
                        
                        <div
                            v-for="(date, index) in form.dates"
                            :key="date.date"
                            class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg"
                        >
                            <span class="font-medium text-gray-900 min-w-[100px]">
                                {{ formatDisplayDate(date.date) }}
                            </span>
                            
                            <template v-if="form.is_time_required">
                                <input
                                    v-model="date.time_from"
                                    type="time"
                                    class="px-2 py-1 border border-gray-300 rounded text-sm"
                                />
                                <span class="text-gray-400">〜</span>
                                <input
                                    v-model="date.time_to"
                                    type="time"
                                    class="px-2 py-1 border border-gray-300 rounded text-sm"
                                />
                            </template>

                            <button
                                type="button"
                                @click="removeDate(index)"
                                class="ml-auto p-1 text-gray-400 hover:text-red-500 transition-colors"
                            >
                                <TrashIcon class="h-5 w-5" />
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                v-model="form.is_time_required"
                                type="checkbox"
                                class="h-4 w-4 text-primary-600 rounded border-gray-300"
                            />
                            <span class="text-sm text-gray-700">時間も指定する</span>
                        </label>
                    </div>

                    <p v-if="errors.dates" class="mt-2 text-sm text-red-600">
                        {{ errors.dates }}
                    </p>
                </Card>

                <!-- 送信ボタン -->
                <div class="flex justify-between">
                    <Button
                        type="button"
                        variant="danger"
                        @click="showDeleteModal = true"
                    >
                        <TrashIcon class="h-5 w-5 mr-1" />
                        削除
                    </Button>
                    
                    <Button
                        type="submit"
                        variant="primary"
                        size="lg"
                        :loading="isSubmitting"
                        :disabled="form.dates.length === 0"
                    >
                        変更を保存
                    </Button>
                </div>
            </form>
        </div>

        <!-- 削除確認モーダル -->
        <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="fixed inset-0 bg-black/50" @click="showDeleteModal = false"></div>
                
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <ExclamationTriangleIcon class="h-6 w-6 text-red-600" />
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">イベントを削除</h3>
                    </div>
                    
                    <p class="text-gray-600 mb-6">
                        「{{ event.title }}」を削除しますか？<br>
                        この操作は取り消せません。
                    </p>
                    
                    <div class="flex justify-end gap-3">
                        <Button variant="ghost" @click="showDeleteModal = false">
                            キャンセル
                        </Button>
                        <Button variant="danger" @click="deleteEvent">
                            削除する
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
