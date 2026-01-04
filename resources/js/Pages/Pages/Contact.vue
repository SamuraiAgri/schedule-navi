<script setup>
import { Head } from '@inertiajs/vue3'
</script>

<template>
  <Head title="お問い合わせ | Schedule-Navi" />
  
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">お問い合わせ</h1>
        
        <div class="prose prose-lg max-w-none text-gray-700">
          <p class="text-center mb-8">
            Schedule-Naviに関するお問い合わせは、以下のメールアドレスまでご連絡ください。
          </p>

          <div class="bg-blue-50 rounded-xl p-8 text-center mb-8">
            <p class="text-gray-600 mb-2">メールアドレス</p>
            <a href="mailto:support@schedule-navi.com" class="text-2xl font-semibold text-blue-600 hover:text-blue-800">
              support@schedule-navi.com
            </a>
          </div>

          <div class="bg-gray-50 rounded-xl p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">お問い合わせの際のお願い</h2>
            <ul class="list-disc pl-6 space-y-2">
              <li>お問い合わせ内容を具体的にご記載ください</li>
              <li>不具合報告の場合は、ご利用のブラウザとOSをお知らせください</li>
              <li>返信までに数日かかる場合があります</li>
            </ul>
          </div>
        </div>

        <div class="mt-12 text-center">
          <a href="/" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            トップページに戻る
          </a>
        </div>
      </div>
    </div>
  </div>
</template>
