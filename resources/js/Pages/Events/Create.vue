<script setup>
/**
 * イベント作成ページ
 * カレンダーUIで候補日程を選択し、イベントを作成
 */
import { ref, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from '@/Components/Card.vue';
import Button from '@/Components/Button.vue';
import Input from '@/Components/Input.vue';
import Textarea from '@/Components/Textarea.vue';
import DatePicker from '@/Components/DatePicker.vue';
import {
    CalendarDaysIcon,
    TrashIcon,
    PlusIcon,
    ChevronUpIcon,
    ChevronDownIcon,
} from '@heroicons/vue/24/outline';

// フォームデータ
const form = ref({
    title: '',
    description: '',
    notify_email: '',
    is_time_required: false,
    is_anonymous_allowed: true,
    dates: [],
});

const errors = ref({});
const isSubmitting = ref(false);
const showAdvancedOptions = ref(false);

// 日付を追加
const addDate = (date) => {
    const dateStr = formatDate(date);
    
    // 重複チェック
    if (form.value.dates.some(d => d.date === dateStr)) {
        return;
    }
    
    form.value.dates.push({
        date: dateStr,
        time_from: '',
        time_to: '',
    });
    
    // 日付順にソート
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

// 表示用の日付フォーマット
const formatDisplayDate = (dateStr) => {
    const date = new Date(dateStr);
    const weekdays = ['日', '月', '火', '水', '木', '金', '土'];
    const month = date.getMonth() + 1;
    const day = date.getDate();
    const weekday = weekdays[date.getDay()];
    return `${month}/${day}（${weekday}）`;
};

// フォーム送信
const submit = () => {
    if (isSubmitting.value) return;
    
    isSubmitting.value = true;
    errors.value = {};
    
    router.post('/events', form.value, {
        onError: (e) => {
            errors.value = e;
        },
        onFinish: () => {
            isSubmitting.value = false;
        },
    });
};

// 選択済み日付のSet
const selectedDates = computed(() => new Set(form.value.dates.map(d => d.date)));
</script>

<template>
    <Head>
        <title>イベントを作成 - Schedule-Navi</title>
        <meta name="description" content="新しい日程調整イベントを作成します。イベント名と候補日程を入力するだけで、30秒で日程調整ページが完成。" />
        <meta property="og:title" content="イベントを作成 - Schedule-Navi" />
        <meta property="og:description" content="新しい日程調整イベントを作成。30秒で完了します。" />
    </Head>
    
    <AppLayout>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">イベントを作成</h1>
                <p class="mt-1 text-sm text-gray-500">
                    イベント名と候補日程を入力して、日程調整を始めましょう。
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- 基本情報 -->
                <Card>
                    <div class="space-y-4">
                        <Input
                            v-model="form.title"
                            label="イベント名"
                            placeholder="例: 新年会の日程調整"
                            :error="errors.title"
                            required
                            id="title"
                        />

                        <Textarea
                            v-model="form.description"
                            label="説明（任意）"
                            placeholder="イベントの詳細や注意事項を入力してください"
                            :rows="3"
                            :maxlength="5000"
                            :error="errors.description"
                            id="description"
                        />
                    </div>
                </Card>

                <!-- 候補日程 -->
                <Card>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <CalendarDaysIcon class="h-5 w-5 text-primary-600" />
                        候補日程
                    </h2>

                    <!-- カレンダー -->
                    <DatePicker
                        :selected-dates="selectedDates"
                        @select="addDate"
                        class="mb-4"
                    />

                    <!-- 選択済み日程リスト -->
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
                            
                            <!-- 時間指定 -->
                            <template v-if="form.is_time_required">
                                <input
                                    v-model="date.time_from"
                                    type="time"
                                    class="px-2 py-1 border border-gray-300 rounded text-sm"
                                    placeholder="開始"
                                />
                                <span class="text-gray-400">〜</span>
                                <input
                                    v-model="date.time_to"
                                    type="time"
                                    class="px-2 py-1 border border-gray-300 rounded text-sm"
                                    placeholder="終了"
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

                    <p v-else class="text-sm text-gray-500 text-center py-4">
                        カレンダーから日付をクリックして候補日程を追加してください
                    </p>

                    <!-- 時間指定オプション -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                v-model="form.is_time_required"
                                type="checkbox"
                                class="h-4 w-4 text-primary-600 rounded border-gray-300 focus:ring-primary-500"
                            />
                            <span class="text-sm text-gray-700">時間も指定する</span>
                        </label>
                    </div>

                    <p v-if="errors.dates" class="mt-2 text-sm text-red-600">
                        {{ errors.dates }}
                    </p>
                </Card>

                <!-- 詳細オプション -->
                <Card>
                    <button
                        type="button"
                        @click="showAdvancedOptions = !showAdvancedOptions"
                        class="w-full flex items-center justify-between text-left"
                    >
                        <span class="font-medium text-gray-900">詳細オプション</span>
                        <ChevronDownIcon
                            :class="['h-5 w-5 text-gray-400 transition-transform', { 'rotate-180': showAdvancedOptions }]"
                        />
                    </button>

                    <div v-show="showAdvancedOptions" class="mt-4 pt-4 border-t border-gray-200 space-y-4">
                        <Input
                            v-model="form.notify_email"
                            type="email"
                            label="通知先メールアドレス"
                            placeholder="example@email.com"
                            :error="errors.notify_email"
                            id="notify_email"
                        />
                        <p class="text-xs text-gray-500 -mt-2">
                            新しい回答があった際に通知を受け取れます
                        </p>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                v-model="form.is_anonymous_allowed"
                                type="checkbox"
                                class="h-4 w-4 text-primary-600 rounded border-gray-300 focus:ring-primary-500"
                            />
                            <span class="text-sm text-gray-700">匿名での回答を許可する</span>
                        </label>
                    </div>
                </Card>

                <!-- 送信ボタン -->
                <div class="flex justify-center">
                    <Button
                        type="submit"
                        variant="primary"
                        size="lg"
                        :loading="isSubmitting"
                        :disabled="form.dates.length === 0"
                        class="w-full sm:w-auto"
                    >
                        <PlusIcon class="h-5 w-5 mr-2" />
                        イベントを作成する
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
