<script setup>
/**
 * イベント作成完了ページ
 * 共有URLと編集URLを表示
 */
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from '@/Components/Card.vue';
import Button from '@/Components/Button.vue';
import {
    CheckCircleIcon,
    ClipboardDocumentIcon,
    ClipboardDocumentCheckIcon,
    LinkIcon,
    PencilSquareIcon,
    ArrowTopRightOnSquareIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
});

const copiedShare = ref(false);
const copiedEdit = ref(false);

// URLをクリップボードにコピー
const copyToClipboard = async (url, type) => {
    try {
        await navigator.clipboard.writeText(url);
        if (type === 'share') {
            copiedShare.value = true;
            setTimeout(() => copiedShare.value = false, 2000);
        } else {
            copiedEdit.value = true;
            setTimeout(() => copiedEdit.value = false, 2000);
        }
    } catch (err) {
        console.error('コピーに失敗しました', err);
    }
};
</script>

<template>
    <Head>
        <title>イベントを作成しました - Schedule-Navi</title>
        <meta name="robots" content="noindex, nofollow" />
    </Head>
    
    <AppLayout>
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- 成功メッセージ -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
                    <CheckCircleIcon class="h-10 w-10 text-green-600" />
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    イベントを作成しました！
                </h1>
                <p class="text-gray-600">
                    「{{ event.title }}」の日程調整ページが作成されました。
                </p>
            </div>

            <!-- 共有URL -->
            <Card class="mb-6">
                <div class="flex items-start gap-3 mb-3">
                    <LinkIcon class="h-6 w-6 text-primary-600 flex-shrink-0 mt-0.5" />
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">共有URL</h2>
                        <p class="text-sm text-gray-500">
                            このURLを参加者に共有してください
                        </p>
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <input
                        type="text"
                        :value="event.shareUrl"
                        readonly
                        class="flex-1 px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-700 select-all"
                    />
                    <Button
                        @click="copyToClipboard(event.shareUrl, 'share')"
                        :variant="copiedShare ? 'primary' : 'secondary'"
                        size="md"
                    >
                        <ClipboardDocumentCheckIcon v-if="copiedShare" class="h-5 w-5" />
                        <ClipboardDocumentIcon v-else class="h-5 w-5" />
                    </Button>
                </div>
                
                <p v-if="copiedShare" class="text-sm text-green-600 mt-2">
                    コピーしました！
                </p>
            </Card>

            <!-- 編集URL -->
            <Card class="mb-8">
                <div class="flex items-start gap-3 mb-3">
                    <PencilSquareIcon class="h-6 w-6 text-yellow-600 flex-shrink-0 mt-0.5" />
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">編集URL</h2>
                        <p class="text-sm text-gray-500">
                            <span class="text-red-600 font-medium">重要:</span>
                            このURLは編集・削除に必要です。必ず保存してください。
                        </p>
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <input
                        type="text"
                        :value="event.editUrl"
                        readonly
                        class="flex-1 px-3 py-2 bg-yellow-50 border border-yellow-300 rounded-lg text-sm text-gray-700 select-all"
                    />
                    <Button
                        @click="copyToClipboard(event.editUrl, 'edit')"
                        :variant="copiedEdit ? 'primary' : 'secondary'"
                        size="md"
                    >
                        <ClipboardDocumentCheckIcon v-if="copiedEdit" class="h-5 w-5" />
                        <ClipboardDocumentIcon v-else class="h-5 w-5" />
                    </Button>
                </div>

                <p v-if="copiedEdit" class="text-sm text-green-600 mt-2">
                    コピーしました！
                </p>
            </Card>

            <!-- アクションボタン -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <Link :href="event.shareUrl">
                    <Button variant="primary" size="lg" class="w-full sm:w-auto">
                        <ArrowTopRightOnSquareIcon class="h-5 w-5 mr-2" />
                        イベントページを開く
                    </Button>
                </Link>
                <Link href="/events/create">
                    <Button variant="secondary" size="lg" class="w-full sm:w-auto">
                        別のイベントを作成
                    </Button>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
