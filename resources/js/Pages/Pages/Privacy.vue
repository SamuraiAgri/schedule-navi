<script setup>
import { Head } from '@inertiajs/vue3'
</script>

<template>
  <Head title="プライバシーポリシー | Schedule-Navi" />
  
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">プライバシーポリシー</h1>
        
        <div class="prose prose-lg max-w-none text-gray-700">
          <p class="text-sm text-gray-500 mb-8">最終更新日: 2026年1月4日</p>

          <section class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">1. はじめに</h2>
            <p>
              Schedule-Navi（以下「当サービス」）は、ユーザーのプライバシーを尊重し、
              個人情報の保護に努めています。本プライバシーポリシーは、
              当サービスにおける情報の収集、使用、開示に関する方針を説明します。
            </p>
          </section>

          <section class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">2. 収集する情報</h2>
            <p>当サービスでは、以下の情報を収集することがあります：</p>
            <ul class="list-disc pl-6 mt-2">
              <li>イベント作成時に入力された情報（イベント名、日程候補など）</li>
              <li>回答者が入力した情報（名前、参加可否など）</li>
              <li>アクセスログ（IPアドレス、ブラウザ情報など）</li>
              <li>Cookieによる情報</li>
            </ul>
          </section>

          <section class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">3. 情報の利用目的</h2>
            <p>収集した情報は、以下の目的で利用します：</p>
            <ul class="list-disc pl-6 mt-2">
              <li>日程調整サービスの提供・運営</li>
              <li>サービスの改善・新機能の開発</li>
              <li>不正利用の防止</li>
              <li>統計データの作成（個人を特定しない形式）</li>
            </ul>
          </section>

          <section class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">4. 広告について</h2>
            <p>
              当サービスでは、Google AdSenseを利用した広告を掲載しています。
              Google AdSenseはCookieを使用してユーザーの興味に基づいた広告を配信することがあります。
            </p>
            <p class="mt-2">
              ユーザーは、
              <a href="https://www.google.com/settings/ads" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline">
                Googleの広告設定ページ
              </a>
              でパーソナライズ広告を無効にすることができます。
            </p>
          </section>

          <section class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">5. 第三者への情報提供</h2>
            <p>
              当サービスは、法令に基づく場合を除き、ユーザーの同意なく個人情報を
              第三者に提供することはありません。
            </p>
          </section>

          <section class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">6. データの保管</h2>
            <p>
              イベントデータは作成から一定期間経過後、自動的に削除されます。
              ユーザーはいつでも自身が作成したイベントを削除することができます。
            </p>
          </section>

          <section class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">7. お問い合わせ</h2>
            <p>
              本ポリシーに関するお問い合わせは、
              <a href="/contact" class="text-blue-600 hover:underline">お問い合わせページ</a>
              よりご連絡ください。
            </p>
          </section>

          <section class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">8. ポリシーの変更</h2>
            <p>
              当サービスは、必要に応じて本ポリシーを変更することがあります。
              変更後のポリシーは、本ページに掲載した時点で効力を生じます。
            </p>
          </section>
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
