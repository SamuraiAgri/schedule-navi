<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>利用規約 | Schedule-Navi</title>
    <meta name="description" content="Schedule-Naviの利用規約です。サービスをご利用いただく際の条件について説明しています。">
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
                <h2 class="text-2xl font-bold text-gray-900 mb-6">利用規約</h2>

                <p class="text-gray-600 mb-8 text-sm">最終更新日: {{ date('Y年m月d日') }}</p>

                <section class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">第1条（適用）</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        本利用規約（以下「本規約」）は、Schedule-Navi（以下「当サービス」）の利用条件を定めるものです。
                        ユーザーは、本規約に同意した上で、当サービスを利用するものとします。
                    </p>
                </section>

                <section class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">第2条（利用登録）</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        当サービスの利用を希望する者は、本規約に同意の上、所定の方法で利用登録を行うものとします。
                        利用登録は、当サービスが承認した時点で完了します。
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        当サービスは、以下のいずれかに該当する場合、利用登録を承認しないことがあります。
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4 mt-2">
                        <li>登録情報に虚偽の内容が含まれる場合</li>
                        <li>過去に本規約違反により利用停止処分を受けたことがある場合</li>
                        <li>その他、当サービスが不適切と判断した場合</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">第3条（アカウント管理）</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        ユーザーは、自己の責任において、ログイン情報を管理するものとします。
                        ログイン情報の管理不十分、使用上の過誤、第三者の使用等による損害について、当サービスは一切の責任を負いません。
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        ログイン情報が第三者に使用されたことを知った場合は、直ちに当サービスに通知してください。
                    </p>
                </section>

                <section class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">第4条（禁止事項）</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        ユーザーは、当サービスの利用にあたり、以下の行為を行ってはなりません。
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                        <li>法令または公序良俗に違反する行為</li>
                        <li>犯罪行為に関連する行為</li>
                        <li>当サービスのサーバーやネットワークを妨害する行為</li>
                        <li>当サービスの運営を妨害するおそれのある行為</li>
                        <li>他のユーザーの情報を収集する行為</li>
                        <li>他のユーザーに成りすます行為</li>
                        <li>当サービスの知的財産権を侵害する行為</li>
                        <li>不正アクセスやリバースエンジニアリング等の技術的な攻撃行為</li>
                        <li>その他、当サービスが不適切と判断する行為</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">第5条（サービスの提供・停止）</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        当サービスは、以下の場合、ユーザーへの事前通知なく、サービスの全部または一部の提供を停止または中断することがあります。
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                        <li>システムの保守、点検、修理を行う場合</li>
                        <li>火災、停電、天災等の不可抗力によりサービスの提供が困難な場合</li>
                        <li>コンピューター、通信回線等が事故により停止した場合</li>
                        <li>その他、当サービスが必要と判断した場合</li>
                    </ul>
                    <p class="text-gray-700 leading-relaxed mt-4">
                        当サービスは、サービスの停止または中断により、ユーザーまたは第三者が被ったいかなる損害についても、一切の責任を負いません。
                    </p>
                </section>

                <section class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">第6条（知的財産権）</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        当サービスに含まれるコンテンツ、ロゴ、デザイン等の知的財産権は、当サービスまたは正当な権利者に帰属します。
                        ユーザーは、これらを無断で複製、転載、配布等することはできません。
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        ユーザーが当サービスに投稿したコンテンツの知的財産権は、ユーザーに帰属します。
                        ただし、当サービスは、サービスの提供、改善、プロモーション等の目的で、これらのコンテンツを使用できるものとします。
                    </p>
                </section>

                <section class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">第7条（免責事項）</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        当サービスは、サービスの内容、正確性、安全性、有用性等について、いかなる保証も行いません。
                        ユーザーは、自己の責任において当サービスを利用するものとします。
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        当サービスは、ユーザーが当サービスを利用することにより生じた損害について、
                        当サービスの故意または重大な過失による場合を除き、一切の責任を負いません。
                    </p>
                </section>

                <section class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">第8条（利用規約の変更）</h3>
                    <p class="text-gray-700 leading-relaxed">
                        当サービスは、必要に応じて本規約を変更することがあります。
                        変更後の利用規約は、本ページに掲載した時点から効力を生じるものとします。
                        ユーザーが変更後も当サービスを継続して利用した場合、変更後の利用規約に同意したものとみなします。
                    </p>
                </section>

                <section class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">第9条（準拠法・管轄裁判所）</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        本規約の解釈にあたっては、日本法を準拠法とします。
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        当サービスに関して紛争が生じた場合には、当サービスの所在地を管轄する裁判所を専属的合意管轄とします。
                    </p>
                </section>

                <section>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">第10条（お問い合わせ）</h3>
                    <p class="text-gray-700 leading-relaxed">
                        本規約に関するお問い合わせは、サイト内のお問い合わせフォームよりご連絡ください。
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