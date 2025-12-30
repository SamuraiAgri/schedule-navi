<script setup>
/**
 * トップページ（ホーム）
 * サービス紹介と「イベントを作成する」への導線
 */
import { Head, Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Button from '@/Components/Button.vue';
import {
    CalendarDaysIcon,
    UserGroupIcon,
    ChartBarIcon,
    ClockIcon,
    DevicePhoneMobileIcon,
    ShieldCheckIcon,
} from '@heroicons/vue/24/outline';

// 構造化データ（JSON-LD）を追加
onMounted(() => {
    const script = document.createElement('script');
    script.type = 'application/ld+json';
    script.text = JSON.stringify({
        "@context": "https://schema.org",
        "@type": "WebApplication",
        "name": "Schedule-Navi",
        "description": "30秒で作れるシンプルな日程調整サービス。飲み会から会議まで、会員登録不要で無料でご利用いただけます。",
        "url": window.location.origin,
        "applicationCategory": "UtilitiesApplication",
        "operatingSystem": "All",
        "offers": {
            "@type": "Offer",
            "price": "0",
            "priceCurrency": "JPY"
        },
        "featureList": [
            "日程調整",
            "出欠管理",
            "会員登録不要",
            "無料",
            "スマホ対応"
        ]
    });
    document.head.appendChild(script);
});

// 3ステップの説明
const steps = [
    {
        number: '1',
        title: 'イベントを作成',
        description: 'イベント名と候補日程を入力するだけ。30秒で完了します。',
        icon: CalendarDaysIcon,
    },
    {
        number: '2',
        title: 'URLを共有',
        description: '作成されたURLをLINEやメールで参加者に共有。',
        icon: UserGroupIcon,
    },
    {
        number: '3',
        title: '結果を確認',
        description: '全員の回答がリアルタイムで集計。最適な日程が一目瞭然。',
        icon: ChartBarIcon,
    },
];

// 機能紹介
const features = [
    {
        title: 'シンプル操作',
        description: '直感的なUIで誰でも簡単に使えます。会員登録も不要。',
        icon: ClockIcon,
    },
    {
        title: 'モバイル対応',
        description: 'スマートフォンでも快適に操作。外出先からでも回答可能。',
        icon: DevicePhoneMobileIcon,
    },
    {
        title: 'プライバシー保護',
        description: '匿名回答にも対応。必要以上の個人情報は収集しません。',
        icon: ShieldCheckIcon,
    },
];
</script>

<template>
    <Head>
        <title>シンプルな日程調整サービス - Schedule-Navi</title>
        <meta name="description" content="30秒で作れるシンプルな日程調整サービス。飲み会から会議まで、会員登録不要で無料でご利用いただけます。" />
        <meta property="og:title" content="Schedule-Navi - シンプルな日程調整サービス" />
        <meta property="og:description" content="30秒で作れるシンプルな日程調整サービス。飲み会から会議まで、会員登録不要で無料。" />
    </Head>
    
    <AppLayout>
        <!-- ヒーローセクション -->
        <section class="bg-gradient-to-br from-primary-50 to-white py-16 sm:py-24">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    日程調整を<span class="text-primary-600">もっとシンプル</span>に
                </h1>
                <p class="text-lg sm:text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                    飲み会から会議まで、あらゆるシーンで使える無料の日程調整ツール。<br class="hidden sm:inline">
                    会員登録なしで今すぐ始められます。
                </p>
                <Link href="/events/create">
                    <Button variant="primary" size="lg">
                        <CalendarDaysIcon class="h-5 w-5 mr-2" />
                        イベントを作成する
                    </Button>
                </Link>
            </div>
        </section>

        <!-- 3ステップセクション -->
        <section class="py-16 bg-white">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl sm:text-3xl font-bold text-center text-gray-900 mb-12">
                    3ステップで日程調整完了
                </h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div
                        v-for="step in steps"
                        :key="step.number"
                        class="text-center"
                    >
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-100 text-primary-600 mb-4">
                            <component :is="step.icon" class="h-8 w-8" />
                        </div>
                        <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary-600 text-white text-sm font-bold mb-3">
                            {{ step.number }}
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ step.title }}</h3>
                        <p class="text-gray-600">{{ step.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 機能紹介セクション -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl sm:text-3xl font-bold text-center text-gray-900 mb-12">
                    Schedule-Naviの特徴
                </h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div
                        v-for="feature in features"
                        :key="feature.title"
                        class="bg-white rounded-lg p-6 shadow-sm"
                    >
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-primary-100 text-primary-600 mb-4">
                            <component :is="feature.icon" class="h-6 w-6" />
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ feature.title }}</h3>
                        <p class="text-gray-600 text-sm">{{ feature.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTAセクション -->
        <section class="py-16 bg-primary-600">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">
                    さっそく日程調整を始めましょう
                </h2>
                <p class="text-primary-100 mb-8">
                    会員登録不要・完全無料でご利用いただけます
                </p>
                <Link href="/events/create">
                    <Button variant="secondary" size="lg" class="bg-white hover:bg-gray-50">
                        イベントを作成する
                    </Button>
                </Link>
            </div>
        </section>
    </AppLayout>
</template>
