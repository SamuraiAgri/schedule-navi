<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Button from '@/Components/Button.vue'
import {
    CalendarDaysIcon,
    ShareIcon,
    ChartBarIcon,
    ClockIcon,
    CheckCircleIcon,
    QuestionMarkCircleIcon,
} from '@heroicons/vue/24/outline'

// 構造化データ
import { onMounted } from 'vue'

onMounted(() => {
    const script = document.createElement('script')
    script.type = 'application/ld+json'
    script.text = JSON.stringify({
        "@context": "https://schema.org",
        "@type": "HowTo",
        "name": "Schedule-Naviの使い方",
        "description": "Schedule-Naviを使った日程調整の方法を3ステップで解説します。",
        "step": [
            {
                "@type": "HowToStep",
                "name": "イベントを作成",
                "text": "イベント名と候補日程を入力してイベントを作成します。"
            },
            {
                "@type": "HowToStep",
                "name": "URLを共有",
                "text": "作成されたURLをLINEやメールで参加者に送ります。"
            },
            {
                "@type": "HowToStep",
                "name": "結果を確認",
                "text": "全員の回答が集まったら、最適な日程を確認します。"
            }
        ]
    })
    document.head.appendChild(script)
})

const steps = [
    {
        number: 1,
        title: 'イベントを作成する',
        icon: CalendarDaysIcon,
        description: 'トップページの「イベントを作成する」ボタンをクリックします。',
        details: [
            'イベント名を入力（例：「新年会」「プロジェクトMTG」）',
            '候補日程をカレンダーから選択',
            '必要に応じてメモを追加',
            '「作成」ボタンをクリック',
        ],
    },
    {
        number: 2,
        title: 'URLを共有する',
        icon: ShareIcon,
        description: 'イベント作成後に表示されるURLを参加者に共有します。',
        details: [
            '表示されたURLをコピー',
            'LINEやメール、Slackなどで共有',
            '参加者はアプリのインストール不要',
            'URLをクリックするだけで回答可能',
        ],
    },
    {
        number: 3,
        title: '回答を集計・確認する',
        icon: ChartBarIcon,
        description: '参加者からの回答がリアルタイムで集計されます。',
        details: [
            '回答状況をリアルタイムで確認',
            '○△×で各日程の参加可否が一目瞭然',
            '最適な日程が自動でハイライト',
            'CSVエクスポートも可能',
        ],
    },
]

const faqs = [
    {
        question: '会員登録は必要ですか？',
        answer: 'いいえ、会員登録は不要です。誰でもすぐにイベントを作成・回答できます。',
    },
    {
        question: '料金はかかりますか？',
        answer: 'Schedule-Naviは完全無料でご利用いただけます。隠れた費用もありません。',
    },
    {
        question: 'スマートフォンで使えますか？',
        answer: 'はい、スマートフォン・タブレット・PCすべてで快適にご利用いただけます。',
    },
    {
        question: 'イベントのデータはいつまで保存されますか？',
        answer: 'イベントデータは一定期間保存されます。重要なイベントはCSVでバックアップをお勧めします。',
    },
    {
        question: '回答を匿名で行えますか？',
        answer: 'はい、名前欄に任意の表示名を入力できるため、匿名での回答も可能です。',
    },
]
</script>

<template>
    <Head>
        <title>使い方ガイド - Schedule-Navi</title>
        <meta name="description" content="Schedule-Naviの使い方を詳しく解説。3ステップで簡単に日程調整ができます。会員登録不要・完全無料。" />
    </Head>

    <AppLayout>
        <!-- ヒーロー -->
        <section class="bg-gradient-to-br from-primary-50 to-white py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    Schedule-Naviの使い方
                </h1>
                <p class="text-lg text-gray-600">
                    3ステップで簡単に日程調整。誰でもすぐに使えます。
                </p>
            </div>
        </section>

        <!-- ステップガイド -->
        <section class="py-16 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="space-y-16">
                    <div v-for="step in steps" :key="step.number" class="flex flex-col md:flex-row gap-8">
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center">
                                <component :is="step.icon" class="h-8 w-8 text-primary-600" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary-600 text-white text-sm font-bold">
                                    {{ step.number }}
                                </span>
                                <h2 class="text-xl font-bold text-gray-900">{{ step.title }}</h2>
                            </div>
                            <p class="text-gray-600 mb-4">{{ step.description }}</p>
                            <ul class="space-y-2">
                                <li v-for="detail in step.details" :key="detail" class="flex items-start gap-2">
                                    <CheckCircleIcon class="h-5 w-5 text-green-500 flex-shrink-0 mt-0.5" />
                                    <span class="text-gray-700">{{ detail }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-12 bg-primary-50">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">さっそく試してみましょう</h2>
                <p class="text-gray-600 mb-6">会員登録不要・完全無料でご利用いただけます</p>
                <Link href="/events/create">
                    <Button variant="primary" size="lg">
                        <CalendarDaysIcon class="h-5 w-5 mr-2" />
                        イベントを作成する
                    </Button>
                </Link>
            </div>
        </section>

        <!-- FAQ -->
        <section class="py-16 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-gray-900 text-center mb-12">
                    <QuestionMarkCircleIcon class="h-8 w-8 inline-block mr-2 text-primary-600" />
                    よくある質問
                </h2>
                <div class="space-y-6">
                    <div v-for="faq in faqs" :key="faq.question" class="bg-gray-50 rounded-lg p-6">
                        <h3 class="font-semibold text-gray-900 mb-2">Q. {{ faq.question }}</h3>
                        <p class="text-gray-600">A. {{ faq.answer }}</p>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
