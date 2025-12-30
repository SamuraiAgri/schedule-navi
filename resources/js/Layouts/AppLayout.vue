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
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col sm:flex-row items-center justify-between text-sm text-gray-500">
                    <div class="flex items-center space-x-2 mb-2 sm:mb-0">
                        <CalendarDaysIcon class="h-5 w-5" />
                        <span>Schedule-Navi</span>
                    </div>
                    <p>&copy; {{ new Date().getFullYear() }} Schedule-Navi. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>
