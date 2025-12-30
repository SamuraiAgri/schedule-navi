<script setup>
/**
 * イベント表示ページ
 * 回答フォームと集計結果を表示
 */
import { ref, computed } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from '@/Components/Card.vue';
import Button from '@/Components/Button.vue';
import Input from '@/Components/Input.vue';
import Textarea from '@/Components/Textarea.vue';
import StatusSelector from '@/Components/StatusSelector.vue';
import ResponseTable from '@/Components/ResponseTable.vue';
import {
    UserIcon,
    ChatBubbleLeftIcon,
    PaperAirplaneIcon,
    ChartBarIcon,
    ClipboardDocumentIcon,
    EyeIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
    statistics: {
        type: Object,
        required: true,
    },
});

const page = usePage();

// タブの状態
const activeTab = ref('respond');

// フォームデータ
const form = ref({
    participant_name: '',
    participant_email: '',
    comment: '',
    is_anonymous: false,
    answers: {},
});

// エラー
const errors = ref({});
const isSubmitting = ref(false);

// フラッシュメッセージ
const successMessage = computed(() => page.props.flash?.success);
const errorMessage = computed(() => page.props.flash?.error);

// すべての日程に回答済みか
const hasAnswers = computed(() => {
    return Object.values(form.value.answers).some(v => v !== null && v !== undefined);
});

// 回答を送信
const submitResponse = () => {
    if (isSubmitting.value) return;
    
    isSubmitting.value = true;
    errors.value = {};
    
    router.post(`/e/${props.event.uuid}/responses`, form.value, {
        onError: (e) => {
            errors.value = e;
        },
        onSuccess: () => {
            // フォームをリセット
            form.value = {
                participant_name: '',
                participant_email: '',
                comment: '',
                is_anonymous: false,
                answers: {},
            };
            activeTab.value = 'results';
        },
        onFinish: () => {
            isSubmitting.value = false;
        },
    });
};

// URLをコピー
const copyUrl = async () => {
    try {
        await navigator.clipboard.writeText(window.location.href);
        alert('URLをコピーしました');
    } catch (err) {
        console.error('コピーに失敗しました', err);
    }
};

// SEO用の説明文
const seoDescription = computed(() => {
    const dateCount = props.event.dates?.length || 0;
    return `「${props.event.title}」の日程調整ページです。${dateCount}件の候補日程から出欠を回答できます。`;
});
</script>

<template>
    <Head>
        <title>{{ event.title }} - Schedule-Navi</title>
        <meta name="description" :content="seoDescription" />
        <meta property="og:title" :content="`${event.title} - 日程調整`" />
        <meta property="og:description" :content="seoDescription" />
        <meta property="og:type" content="article" />
    </Head>
    
    <AppLayout>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- イベント情報 -->
            <Card class="mb-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">
                            {{ event.title }}
                        </h1>
                        <p v-if="event.description" class="text-gray-600 whitespace-pre-wrap">
                            {{ event.description }}
                        </p>
                    </div>
                    <button
                        @click="copyUrl"
                        class="flex-shrink-0 p-2 text-gray-400 hover:text-primary-600 transition-colors"
                        title="URLをコピー"
                    >
                        <ClipboardDocumentIcon class="h-6 w-6" />
                    </button>
                </div>
                
                <div class="mt-4 flex items-center gap-4 text-sm text-gray-500">
                    <span class="flex items-center gap-1">
                        <EyeIcon class="h-4 w-4" />
                        {{ event.viewCount }} 回閲覧
                    </span>
                    <span v-if="event.deadlineAt">
                        回答期限: {{ event.deadlineAt }}
                    </span>
                </div>
            </Card>

            <!-- フラッシュメッセージ -->
            <div v-if="successMessage" class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ successMessage }}
            </div>
            <div v-if="errorMessage" class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                {{ errorMessage }}
            </div>

            <!-- タブ切り替え -->
            <div class="flex border-b border-gray-200 mb-6">
                <button
                    @click="activeTab = 'respond'"
                    :class="[
                        'px-4 py-2 text-sm font-medium border-b-2 -mb-px transition-colors',
                        activeTab === 'respond'
                            ? 'border-primary-600 text-primary-600'
                            : 'border-transparent text-gray-500 hover:text-gray-700',
                    ]"
                >
                    <UserIcon class="h-4 w-4 inline mr-1" />
                    回答する
                </button>
                <button
                    @click="activeTab = 'results'"
                    :class="[
                        'px-4 py-2 text-sm font-medium border-b-2 -mb-px transition-colors',
                        activeTab === 'results'
                            ? 'border-primary-600 text-primary-600'
                            : 'border-transparent text-gray-500 hover:text-gray-700',
                    ]"
                >
                    <ChartBarIcon class="h-4 w-4 inline mr-1" />
                    集計を見る
                    <span class="ml-1 px-2 py-0.5 text-xs bg-gray-100 rounded-full">
                        {{ statistics.totalResponses }}
                    </span>
                </button>
            </div>

            <!-- 回答タブ -->
            <div v-show="activeTab === 'respond'">
                <form @submit.prevent="submitResponse" v-if="event.isActive">
                    <Card class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <UserIcon class="h-5 w-5 text-primary-600" />
                            あなたの情報
                        </h2>

                        <div class="space-y-4">
                            <Input
                                v-model="form.participant_name"
                                label="お名前"
                                placeholder="山田 太郎"
                                :error="errors.participant_name"
                                required
                                id="participant_name"
                            />

                            <Textarea
                                v-model="form.comment"
                                label="コメント（任意）"
                                placeholder="一言メッセージがあればどうぞ"
                                :rows="2"
                                :maxlength="1000"
                                :error="errors.comment"
                                id="comment"
                            />

                            <label v-if="event.isAnonymousAllowed" class="flex items-center gap-2 cursor-pointer">
                                <input
                                    v-model="form.is_anonymous"
                                    type="checkbox"
                                    class="h-4 w-4 text-primary-600 rounded border-gray-300 focus:ring-primary-500"
                                />
                                <span class="text-sm text-gray-700">名前を非表示にする（匿名）</span>
                            </label>
                        </div>
                    </Card>

                    <Card class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            出欠を選択
                        </h2>
                        <p class="text-sm text-gray-500 mb-4">
                            各日程について、○（参加可能）、△（未定）、×（参加不可）を選択してください。
                        </p>

                        <div class="space-y-3">
                            <div
                                v-for="date in event.dates"
                                :key="date.id"
                                class="flex flex-col sm:flex-row sm:items-center gap-3 p-3 bg-gray-50 rounded-lg"
                            >
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-gray-900">
                                        {{ date.formattedDate }}
                                    </div>
                                    <div v-if="date.formattedTime" class="text-sm text-gray-500">
                                        {{ date.formattedTime }}
                                    </div>
                                </div>
                                <StatusSelector
                                    v-model="form.answers[date.id]"
                                />
                            </div>
                        </div>

                        <p v-if="errors.answers" class="mt-3 text-sm text-red-600">
                            {{ errors.answers }}
                        </p>
                    </Card>

                    <div class="flex justify-center">
                        <Button
                            type="submit"
                            variant="primary"
                            size="lg"
                            :loading="isSubmitting"
                            :disabled="!hasAnswers"
                            class="w-full sm:w-auto"
                        >
                            <PaperAirplaneIcon class="h-5 w-5 mr-2" />
                            回答を送信
                        </Button>
                    </div>
                </form>

                <Card v-else>
                    <div class="text-center py-8 text-gray-500">
                        <p class="text-lg font-medium mb-2">このイベントは回答を受け付けていません</p>
                        <p>回答期限が過ぎているか、イベントが終了しています。</p>
                    </div>
                </Card>
            </div>

            <!-- 集計タブ -->
            <div v-show="activeTab === 'results'">
                <!-- 最適な日程 -->
                <Card v-if="statistics.summary.bestDateId && statistics.totalResponses > 0" class="mb-6">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <ChartBarIcon class="h-6 w-6 text-green-600" />
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">最も参加者が多い日程</div>
                            <div class="text-lg font-semibold text-gray-900">
                                {{ statistics.summary.bestDateLabel }}
                            </div>
                            <div class="text-sm text-green-600">
                                {{ statistics.summary.bestDateOkCount }}人が参加可能
                            </div>
                        </div>
                    </div>
                </Card>

                <!-- 回答一覧 -->
                <ResponseTable
                    :dates="event.dates"
                    :responses="event.responses"
                    :statistics="statistics"
                />

                <div v-if="statistics.totalResponses === 0" class="text-center py-12 text-gray-500">
                    <UserIcon class="h-12 w-12 mx-auto mb-4 text-gray-300" />
                    <p class="text-lg font-medium mb-2">まだ回答がありません</p>
                    <p>最初の回答者になりましょう！</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
