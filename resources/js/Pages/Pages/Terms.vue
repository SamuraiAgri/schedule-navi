<script setup>
import { Head } from '@inertiajs/vue3'
</script>

<template>
  <Head title="利用規約 | Schedule-Navi" />
  
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">利用規約</h1>
        
        <div class="prose prose-lg max-w-none text-gray-700">
          <p class="text-sm text-gray-500 mb-8">最終更新日: 2026年1月4日</p>

          <section class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">第1条（適用）</h2>
            <p>
              本規約は、Schedule-Navi（以下「当サービス」）の利用に関する条件を定めるものです。
              当サービスを利用するすべてのユーザーは、本規約に同意したものとみなします。
            </p>
          </section>

          <section class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">第2条（サービス内容）</h2>
            <p>当サービスは、以下の機能を無料で提供します：</p>
            <ul class="list-disc pl-6 mt-2">
              <li>日程調整イベントの作成</li>
              <li>参加者による回答の入力・集計</li>
              <li>イベントデータのCSVエクスポート</li>
            </ul>
          </section>

          <section class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">第3条（禁止事項）</h2>
            <p>ユーザーは、以下の行為をしてはなりません：</p>
            <ul class="list-disc pl-6 mt-2">
              <li>法令または公序良俗に反する行為</li>
              <li>当サービスの運営を妨害する行為</li>
              <li>他のユーザーに迷惑をかける行為</li>
              <li>不正アクセスやハッキング行為</li>
              <li>当サービスのデータを不正に収集する行為</li>
              <li>商用目的での不正利用</li>
            </ul>
          </section>

          <section class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">第4条（データの取り扱い）</h2>
            <p>
              当サービスに入力されたイベントデータは、作成から一定期間経過後、
              自動的に削除されることがあります。重要なデータはユーザー自身で
              バックアップを取ることを推奨します。
            </p>
          </section>

          <section class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">第5条（免責事項）</h2>
            <p>当サービスは、以下について一切の責任を負いません：</p>
            <ul class="list-disc pl-6 mt-2">
              <li>サービスの中断・停止によって生じた損害</li>
              <li>データの消失・破損によって生じた損害</li>
              <li>ユーザー間のトラブル</li>
              <li>第三者によるデータへの不正アクセス</li>
            </ul>
          </section>

          <section class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">第6条（サービスの変更・終了）</h2>
            <p>
              当サービスは、ユーザーへの事前通知なく、サービスの内容を変更、
              または提供を終了することがあります。
            </p>
          </section>

          <section class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">第7条（規約の変更）</h2>
            <p>
              当サービスは、必要に応じて本規約を変更することがあります。
              変更後の規約は、本ページに掲載した時点で効力を生じます。
            </p>
          </section>

          <section class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">第8条（準拠法・管轄）</h2>
            <p>
              本規約の解釈は日本法に準拠するものとし、紛争が生じた場合は
              東京地方裁判所を第一審の専属的合意管轄裁判所とします。
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
