# さくらインターネット デプロイ手順書
## Schedule-Navi (schedule-navi.com)

---

## 📋 前提条件（確認済み）

- ✅ ドメイン: **schedule-navi.com**（お名前ドットコム）
- ✅ サーバー: さくらインターネット
- ✅ データベース: さくらインターネット MySQL
- ✅ ローカル準備完了（Composer・npm build済み）

---

## 🎯 あなたが実施する作業の流れ

### 全体概要
1. お名前ドットコムでDNS設定（さくらのネームサーバーに変更）
2. さくらのコントロールパネルでドメイン登録
3. さくらでMySQLデータベース作成
4. GitHubからコードをサーバーにデプロイ
5. 環境設定ファイル（.env）を作成
6. データベースマイグレーション実行
7. パーミッション設定
8. SSL証明書（Let's Encrypt）設定
9. 動作確認

---

## Step 1: お名前ドットコムのDNS設定

### 1-1. お名前ドットコムにログイン
https://www.onamae.com/

### 1-2. ネームサーバー設定を変更

1. **ドメイン設定** → **ネームサーバーの設定** → **ネームサーバーの変更**
2. `schedule-navi.com` を選択
3. **その他のネームサーバーを使う** を選択
4. さくらインターネットのネームサーバーを入力：

```
ネームサーバー1: ns1.dns.ne.jp
ネームサーバー2: ns2.dns.ne.jp
```

5. 確認画面へ進み、設定完了

**注意**: DNS反映に最大24〜48時間かかる場合があります。

---

## Step 2: さくらインターネットの設定

### 2-1. さくらのコントロールパネルにログイン
https://secure.sakura.ad.jp/

### 2-2. ドメイン設定

1. **ドメイン/SSL** → **ドメイン/SSL**
2. **新しいドメインの追加** をクリック
3. **他社で取得したドメインを使う** を選択
4. ドメイン名を入力: `schedule-navi.com`
5. マルチドメインとして追加
6. **送信する** をクリック

### 2-3. 公開フォルダの設定（後で変更します）

初期状態では `/home/アカウント名/www` がドキュメントルートになっています。
後ほど `/home/アカウント名/schedule-navi/public` に変更します。

---

## Step 3: MySQLデータベースの作成

### 3-1. データベース作成

1. さくらのコントロールパネルで **データベース** → **データベースの設定**
2. **データベースの新規作成**
3. 以下の情報を記録：

```
データベース名: (自動生成、例: username_schedulenavi)
ユーザー名: (自動生成、例: username)
パスワード: (設定したパスワード)
データベースサーバー: (例: mysql123.db.sakura.ne.jp)
ポート: 3306
```

**これらの情報は後で.envファイルで使用します。必ずメモしてください！**

---

## Step 4: SSHでサーバーに接続

### 4-1. SSH接続情報の確認

さくらのコントロールパネル：
- **サーバー情報** → **サーバー情報の表示**
- SSH接続情報を確認

### 4-2. SSH接続（PowerShellまたはターミナル）

```bash
ssh アカウント名@アカウント名.sakura.ne.jp
# パスワードを入力
```

初回接続時は fingerprint の確認で `yes` を入力

---

## Step 5: Gitリポジトリからコードを取得

### 5-1. ホームディレクトリに移動

```bash
cd ~
pwd  # /home/アカウント名 を確認
```

### 5-2. Gitからクローン

```bash
# GitHubリポジトリからクローン（リポジトリURLは後で共有します）
git clone https://github.com/yourusername/schedule-navi.git schedule-navi

# ディレクトリに移動
cd schedule-navi

# ファイルが正しく配置されているか確認
ls -la
```

確認すべきディレクトリ：
- `app/`
- `public/`（中に `build/` ディレクトリがあること）
- `database/`
- `vendor/`（あればOK、なければ次のステップでインストール）

---

## Step 6: Composerで依存関係をインストール

### 6-1. Composerがインストールされているか確認

```bash
composer --version
```

表示されればOK。表示されない場合：

```bash
curl -sS https://getcomposer.org/installer | php
mkdir -p ~/bin
mv composer.phar ~/bin/composer
echo 'export PATH="$HOME/bin:$PATH"' >> ~/.bashrc
source ~/.bashrc
composer --version
```

### 6-2. 依存関係をインストール

```bash
cd ~/schedule-navi
composer install --no-dev --optimize-autoloader --no-interaction
```

完了するまで待機（数分かかる場合があります）

---

## Step 7: 環境設定ファイル（.env）を作成

### 7-1. .envファイルを作成

```bash
cd ~/schedule-navi
cp .env.example .env
nano .env
```

### 7-2. .envファイルを編集

以下の項目を**必ず**編集してください：

```env
APP_NAME="Schedule-Navi"
APP_ENV=production
APP_KEY=  # 次のステップで自動生成
APP_DEBUG=false  # 重要！本番環境では必ずfalse
APP_TIMEZONE=Asia/Tokyo
APP_URL=https://schedule-navi.com  # あなたのドメイン

LOG_CHANNEL=stack
LOG_LEVEL=error

# データベース設定（Step 3で作成した情報を入力）
DB_CONNECTION=mysql
DB_HOST=mysql123.db.sakura.ne.jp  # あなたのDBサーバー
DB_PORT=3306
DB_DATABASE=username_schedulenavi  # あなたのデータベース名
DB_USERNAME=username  # あなたのDBユーザー名
DB_PASSWORD=your_password  # あなたのDBパスワード

# セッション設定
SESSION_DRIVER=file
SESSION_LIFETIME=120

# キャッシュ設定
CACHE_STORE=file
QUEUE_CONNECTION=database

# メール設定（後で設定可能）
MAIL_MAILER=log  # テスト時はlog、本番ではsmtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@schedule-navi.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**nano エディタの操作:**
- 編集が終わったら `Ctrl + O` → `Enter` で保存
- `Ctrl + X` で終了

---

## Step 8: アプリケーションキーを生成

```bash
cd ~/schedule-navi
php artisan key:generate
```

自動的に `.env` の `APP_KEY` が設定されます。

---

## Step 9: データベースマイグレーション実行

### 9-1. データベース接続テスト

```bash
php artisan tinker
```

thinkerが起動したら：

```php
DB::connection()->getPdo();
```

エラーが出なければ接続成功。`exit` で終了。

### 9-2. マイグレーション実行

```bash
php artisan migrate --force
```

実行されるマイグレーション：
- ✅ `create_events_table` - イベント情報
- ✅ `create_event_dates_table` - 候補日
- ✅ `create_responses_table` - 回答者情報
- ✅ `create_response_details_table` - 回答詳細

### 9-3. マイグレーション確認

```bash
php artisan migrate:status
```

すべて `Ran` になっていればOK。

---

## Step 10: パーミッション設定

```bash
cd ~/schedule-navi

# storage と cache ディレクトリの書き込み権限
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# ストレージへのシンボリックリンク作成
php artisan storage:link
```

---

## Step 11: キャッシュの生成

```bash
cd ~/schedule-navi

# 設定キャッシュ
php artisan config:cache

# ルートキャッシュ
php artisan route:cache

# ビューキャッシュ
php artisan view:cache

# 全体最適化
php artisan optimize
```

---

## Step 12: 公開ディレクトリの設定

### 方法A: シンボリックリンク（推奨）

```bash
# 既存のwwwディレクトリをバックアップ
cd ~
mv www www_backup

# schedule-navi/public へのシンボリックリンクを作成
ln -s ~/schedule-navi/public www

# 確認
ls -la www
```

### 方法B: コントロールパネルから設定

さくらのコントロールパネル：
1. **ドメイン/SSL** → `schedule-navi.com` を選択
2. **設定** → **パス設定**
3. パスを `/home/アカウント名/schedule-navi/public` に変更
4. 保存

---

## Step 13: .htaccess の確認

`public/.htaccess` が存在することを確認：

```bash
cat ~/schedule-navi/public/.htaccess
```

内容を確認（LaravelのデフォルトRewriteルールが含まれているはず）

---

## Step 14: SSL証明書の設定（Let's Encrypt）

### 14-1. さくらのコントロールパネルでSSL設定

1. **ドメイン/SSL** → **証明書**
2. `schedule-navi.com` を選択
3. **無料SSLを設定** をクリック
4. **Let's Encrypt** を選択
5. 利用規約に同意して **無料SSLを設定する**

### 14-2. SSL証明書発行を待つ

- 発行には数分〜数十分かかります
- 完了すると「利用中」と表示されます

### 14-3. HTTPSリダイレクト設定

`public/.htaccess` の先頭に以下を追加：

```bash
nano ~/schedule-navi/public/.htaccess
```

先頭に追加：

```apache
# HTTPS強制リダイレクト
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>
```

保存して終了。

---

## Step 15: キュー（バックグラウンド処理）の設定

メール通知を送信するためのキューワーカー設定

### 15-1. Cronに登録

```bash
crontab -e
```

エディタが開いたら以下を追加：

```cron
# Laravelスケジューラー
* * * * * cd /home/アカウント名/schedule-navi && php artisan schedule:run >> /dev/null 2>&1

# キューワーカーの再起動
*/5 * * * * cd /home/アカウント名/schedule-navi && php artisan queue:restart >> /dev/null 2>&1
```

**注意**: `/home/アカウント名/` を実際のパスに置き換えてください。

保存して終了。

### 15-2. キューワーカーを手動起動（オプション）

```bash
cd ~/schedule-navi
nohup php artisan queue:work --sleep=3 --tries=3 --max-time=3600 >> storage/logs/worker.log 2>&1 &
```

---

## Step 16: 動作確認

### 16-1. ブラウザでアクセス

```
https://schedule-navi.com
```

### 16-2. 確認項目チェックリスト

- [ ] トップページが表示される
- [ ] デザインが正しく表示される（CSS読み込みOK）
- [ ] **イベントを作成** ボタンが動作する
- [ ] イベント作成フォームが表示される
- [ ] 日付選択カレンダーが動作する
- [ ] イベントが作成できる
- [ ] 作成完了画面でURLが表示される
- [ ] 共有URLにアクセスできる
- [ ] 回答ができる（○×△の選択）
- [ ] 集計が正しく表示される
- [ ] HTTPSで接続できている（鍵マーク）

### 16-3. 追加確認

以下のURLも確認：

- `https://schedule-navi.com/robots.txt` - robots.txtが表示される
- `https://schedule-navi.com/sitemap.xml` - サイトマップが表示される

---

## Step 17: エラー発生時の対処

### エラーログの確認

```bash
# Laravelのログを確認
tail -50 ~/schedule-navi/storage/logs/laravel.log

# リアルタイムでログを監視
tail -f ~/schedule-navi/storage/logs/laravel.log
```

### よくあるエラーと対処法

#### 1. "500 Internal Server Error"

```bash
# デバッグモードを一時的にON（原因特定後は必ずOFFに）
nano ~/schedule-navi/.env
# APP_DEBUG=true に変更して保存

# ブラウザで再度アクセスしてエラー内容を確認

# 確認後、必ずOFFに戻す
# APP_DEBUG=false
```

#### 2. "Permission denied" エラー

```bash
chmod -R 775 ~/schedule-navi/storage
chmod -R 775 ~/schedule-navi/bootstrap/cache
```

#### 3. CSS/JSが読み込まれない

```bash
# public/build/ が存在するか確認
ls -la ~/schedule-navi/public/build/

# なければローカルでビルドし直して再アップロード
```

#### 4. データベース接続エラー

```bash
# .envの設定を確認
cat ~/schedule-navi/.env | grep DB_

# データベース接続テスト
php artisan tinker
> DB::connection()->getPdo();
```

#### 5. キャッシュが原因のエラー

```bash
cd ~/schedule-navi

# すべてのキャッシュをクリア
php artisan optimize:clear

# キャッシュを再生成
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## 🔄 コード更新時の手順

今後、機能追加や修正をした際の更新手順：

```bash
# SSHでサーバーに接続
ssh アカウント名@アカウント名.sakura.ne.jp

cd ~/schedule-navi

# 1. メンテナンスモードON
php artisan down

# 2. 最新コードを取得
git pull origin master

# 3. 依存関係を更新
composer install --no-dev --optimize-autoloader

# 4. データベースマイグレーション（新しいテーブルがある場合）
php artisan migrate --force

# 5. キャッシュクリア
php artisan optimize:clear

# 6. キャッシュ再生成
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 7. メンテナンスモードOFF
php artisan up
```

---

## 📊 定期的なメンテナンス

### ログファイルのローテーション

ログが大きくなりすぎた場合：

```bash
# ログファイルのサイズ確認
du -sh ~/schedule-navi/storage/logs/

# 古いログを削除（30日以上前）
find ~/schedule-navi/storage/logs/ -name "*.log" -mtime +30 -delete
```

### データベースのバックアップ

```bash
# バックアップディレクトリを作成
mkdir -p ~/backups

# データベースをバックアップ
mysqldump -h mysql123.db.sakura.ne.jp -u username -p database_name > ~/backups/schedule-navi_$(date +%Y%m%d).sql
```

---

## 🔒 セキュリティチェックリスト

デプロイ完了後、以下を確認：

- [ ] `.env` ファイルの `APP_DEBUG=false` になっている
- [ ] `.env` ファイルの `APP_ENV=production` になっている
- [ ] HTTPS接続が有効（Let's Encrypt設定済み）
- [ ] `.env` ファイルが外部からアクセスできない（Laravel標準で保護済み）
- [ ] 不要なファイルが公開されていない（`public/` 以外は非公開）
- [ ] データベースのパスワードが強固
- [ ] SSH接続のパスワードが強固（可能なら公開鍵認証に変更）

---

## 📝 本番環境の情報（記録用）

デプロイ完了後、以下の情報を記録してください：

```
■ サイト情報
- URL: https://schedule-navi.com
- サーバー: さくらインターネット
- アカウント名: __________________

■ データベース情報
- データベース名: __________________
- ユーザー名: __________________
- パスワード: __________________
- ホスト: __________________

■ SSH接続情報
- ホスト: __________________.sakura.ne.jp
- ユーザー名: __________________
- パスワード: __________________

■ 管理画面URL
- さくらのコントロールパネル: https://secure.sakura.ad.jp/
- お名前ドットコム: https://www.onamae.com/

■ デプロイ日時
- 初回デプロイ: __________________
```

---

## 🎉 デプロイ完了！

すべての手順が完了したら、以下をテストしてください：

1. ✅ トップページにアクセス
2. ✅ イベントを作成
3. ✅ 回答URLをコピー
4. ✅ 別のブラウザ/シークレットモードで回答
5. ✅ 集計画面を確認
6. ✅ CSV出力を試す
7. ✅ スマートフォンからアクセス

---

## 💡 次のステップ（オプション）

### Google Analytics設定

`.env` に以下を追加：

```env
GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX
```

`resources/views/app.blade.php` にトラッキングコードを追加。

### メール通知の本番設定

SMTP設定を `.env` で行う：

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your_email@example.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@schedule-navi.com"
MAIL_FROM_NAME="Schedule-Navi"
```

### パフォーマンス監視

- New Relic
- Laravel Telescope（開発環境のみ）
- ログ監視ツール

---

## 📞 トラブル時の連絡先

- さくらインターネット サポート: https://help.sakura.ad.jp/
- お名前ドットコム サポート: https://www.onamae.com/guide/

---

以上でデプロイ作業は完了です！
不明点があれば、エラーメッセージと一緒にお知らせください。
