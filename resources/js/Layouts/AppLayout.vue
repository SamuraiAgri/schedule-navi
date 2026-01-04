<script setup>
/**
 * アプリケーション共通レイアウト
 * ヘッダー・フッターを含む基本構造
 */
import { Link } from '@inertiajs/vue3';
import { CalendarDaysIcon } from '@heroicons/vue/24/outline';

defineProps({
    title: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <!-- ヘッダー -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- ロゴ -->
                    <Link href="/" class="flex items-center space-x-2 text-primary-600 hover:text-primary-700 transition-colors">
                        <CalendarDaysIcon class="h-8 w-8" />
                        <span class="text-xl font-bold">Schedule-Navi</span>
                    </Link>

                    <!-- ナビゲーション -->
                    <nav class="flex items-center space-x-4">
                        <Link
                            href="/events/create"
                            class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors"
                        >
                            イベントを作成
                        </Link>
                    </nav>
                </div>
            </div>
        </header>

        <!-- メインコンテンツ -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- フッター -->
        <footer class="bg-gray-800 text-gray-300 mt-auto">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- About -->
                    <div class="md:col-span-2">
                        <div class="flex items-center space-x-2 text-white mb-4">
                            <CalendarDaysIcon class="h-6 w-6" />
                            <span class="text-lg font-bold">Schedule-Navi</span>
                        </div>
                        <p class="text-sm text-gray-400 mb-4">
                            シンプルで使いやすい無料の日程調整サービスです。
                            会員登録不要で、飲み会から会議まであらゆるシーンでご利用いただけます。
                        </p>
                    </div>

                    <!-- Links -->
                    <div>
                        <h3 class="text-white font-semibold mb-4">サービス</h3>
                        <ul class="space-y-2 text-sm">
                            <li>
                                <Link href="/events/create" class="hover:text-white transition-colors">
                                    イベント作成
                                </Link>
                            </li>
                            <li>
                                <Link href="/about" class="hover:text-white transition-colors">
                                    使い方
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <!-- Legal -->
                    <div>
                        <h3 class="text-white font-semibold mb-4">法的情報</h3>
                        <ul class="space-y-2 text-sm">
                            <li>
                                <Link href="/privacy" class="hover:text-white transition-colors">
                                    プライバシーポリシー
                                </Link>
                            </li>
                            <li>
                                <Link href="/terms" class="hover:text-white transition-colors">
                                    利用規約
                                </Link>
                            </li>
                            <li>
                                <Link href="/contact" class="hover:text-white transition-colors">
                                    お問い合わせ
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-500">
                    <p>&copy; {{ new Date().getFullYear() }} Schedule-Navi. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>
