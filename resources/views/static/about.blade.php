<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule-Naviについて | Schedule-Navi</title>
    <meta name="description" content="Schedule-Naviは、スケジュール管理を簡単かつ効率的に行うためのWebアプリケーションです。使い方やサービスの特徴についてご紹介します。">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- ヘッダー -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-900">Schedule-Navi</h1>
                    <a href="/" class="text-blue-600 hover:text-blue-800">ホームへ戻る</a>
                </div>
            </div>
        </header>

        <!-- メインコンテンツ -->
        <main class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <article class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Schedule-Naviについて</h2>

                <section class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">サービス概要</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Schedule-Naviは、個人や小規模チームのスケジュール管理を簡単かつ効率的に行うためのWebアプリケーションです。
                        日々の予定を整理し、タスク管理を一元化することで、生産性の向上をサポートします。
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        直感的なインターフェースと柔軟な機能により、初心者から上級者まで幅広くご利用いただけます。
                        会員登録不要で基本機能をお試しいただけるため、まずは気軽にご利用ください。
                    </p>
                </section>

                <section class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">主な機能</h3>
                    <div class="space-y-4">
                        <div class="border-l-4 border-blue-500 pl-4">
                            <h4 class="font-semibold text-gray-800 mb-2">カレンダー表示</h4>
                            <p class="text-gray-700">月表示・週表示・日表示の3つのビューで予定を確認できます。見やすいレイアウトで、一目で予定を把握できます。</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-4">
                            <h4 class="font-semibold text-gray-800 mb-2">予定の作成・編集</h4>
                            <p class="text-gray-700">タイトル、日時、メモ、カテゴリーなど、詳細な予定情報を登録できます。繰り返し予定にも対応しています。</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-4">
                            <h4 class="font-semibold text-gray-800 mb-2">リマインダー機能</h4>
                            <p class="text-gray-700">予定の前にメールやブラウザ通知でお知らせします。大切な予定を忘れる心配がありません。</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-4">
                            <h4 class="font-semibold text-gray-800 mb-2">カテゴリー管理</h4>
                            <p class="text-gray-700">仕事、プライベート、勉強など、用途に応じてカテゴリー分けが可能です。色分けで視覚的に区別できます。</p>
                        </div>
                    </div>
                </section>

                <section class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">ご利用方法</h3>
                    <ol class="list-decimal list-inside space-y-3 text-gray-700">
                        <li>トップページから「新規登録」または「ログイン」を選択してください</li>
                        <li>会員登録がお済みでない方は、メールアドレスとパスワードで簡単に登録できます</li>
                        <li>ダッシュボードから「予定を追加」ボタンをクリックして、新しい予定を作成します</li>
                        <li>カレンダー上で予定をクリックすると、詳細の確認や編集ができます</li>
                        <li>設定画面から、通知方法やカレンダーの表示設定をカスタマイズできます</li>
                    </ol>
                </section>

                <section class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">推奨環境</h3>
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="text-gray-700 mb-2"><strong>ブラウザ:</strong></p>
                        <ul class="list-disc list-inside text-gray-700 ml-4 mb-4">
                            <li>Google Chrome（最新版）</li>
                            <li>Mozilla Firefox（最新版）</li>
                            <li>Microsoft Edge（最新版）</li>
                            <li>Safari（最新版）</li>
                        </ul>
                        <p class="text-gray-700 mb-2"><strong>デバイス:</strong></p>
                        <ul class="list-disc list-inside text-gray-700 ml-4">
                            <li>PC（Windows、Mac、Linux）</li>
                            <li>タブレット（iPad、Android）</li>
                            <li>スマートフォン（iPhone、Android）</li>
                        </ul>
                    </div>
                </section>

                <section class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">よくある質問</h3>
                    <div class="space-y-4">
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2">Q: 利用料金はかかりますか？</h4>
                            <p class="text-gray-700 pl-4">A: 基本機能は無料でご利用いただけます。一部の高度な機能については、今後プレミアムプランを提供予定です。</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2">Q: データのバックアップは取られていますか？</h4>
                            <p class="text-gray-700 pl-4">A: はい、定期的にバックアップを取得しています。万が一の場合でもデータの復旧が可能です。</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2">Q: 他のカレンダーアプリと連携できますか？</h4>
                            <p class="text-gray-700 pl-4">A: 現在、iCalendar形式でのエクスポート機能を提供しています。Google CalendarやOutlookとの連携も開発中です。</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2">Q: スマートフォンでも使えますか？</h4>
                            <p class="text-gray-700 pl-4">A: はい、レスポンシブデザインを採用しているため、スマートフォンやタブレットからも快適にご利用いただけます。</p>
                        </div>
                    </div>
                </section>

                <section>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">お問い合わせ</h3>
                    <p class="text-gray-700 leading-relaxed">
                        ご不明な点やご要望がございましたら、お気軽にお問い合わせください。
                        より良いサービスの提供のため、皆様のご意見をお待ちしております。
                    </p>
                </section>
            </article>
        </main>

        <!-- フッター -->
        <footer class="bg-white border-t mt-12">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="text-center text-gray-500 text-sm">
                    <p>&copy; {{ date('Y') }} Schedule-Navi. All rights reserved.</p>
                    <div class="mt-2">
                        <a href="/privacy" class="text-blue-600 hover:text-blue-800 mx-2">プライバシーポリシー</a>
                        <a href="/terms" class="text-blue-600 hover:text-blue-800 mx-2">利用規約</a>
                        <a href="/about" class="text-blue-600 hover:text-blue-800 mx-2">このサイトについて</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
