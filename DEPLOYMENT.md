# Schedule-Navi 本番環境デプロイ手順書

## 📋 前提条件

- さくらインターネット スタンダードプラン以上
- SSH接続可能
- MySQLデータベース作成済み
- ドメイン設定済み（例: schedule-navi.com）

---

## 🚀 デプロイ手順

### Step 1: ローカルでビルド（開発PC）

本番用のアセットをビルドします。

```powershell
# プロジェクトディレクトリに移動
cd C:\xampp\htdocs\schedule-navi

# 依存関係を本番用にインストール
composer install --no-dev --optimize-autoloader

# フロントエンドをビルド
npm install
npm run build

# .envファイルを準備（後でサーバー上で編集）
# .env.example をコピーして .env.production として保存
```

**重要**: `public/build/` ディレクトリが生成されていることを確認してください。

---

### Step 2: GitHubにプッシュ（推奨）

Gitリポジトリを使う場合：

```powershell
# Gitリポジトリを初期化（まだの場合）
git init
git add .
git commit -m "Initial commit"

# GitHubにプッシュ
git remote add origin https://github.com/yourusername/schedule-navi.git
git branch -M main
git push -u origin main
```

**または FTP/SFTP でアップロード**する場合は Step 3へ

---

### Step 3: サーバーにSSH接続

```bash
# さくらインターネットにSSH接続
ssh username@yourdomain.sakura.ne.jp
# パスワードを入力
```

---

### Step 4: プロジェクトをサーバーに配置

#### 方法A: Git経由（推奨）

```bash
# ホームディレクトリに移動
cd ~

# Gitからクローン
git clone https://github.com/yourusername/schedule-navi.git
cd schedule-navi

# または既存のディレクトリがある場合
cd ~/schedule-navi
git pull origin main
```

#### 方法B: FTP/SFTP経由

FileZilla等で以下のファイル・フォルダをアップロード：

```
アップロード対象:
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
│   └── build/  ← 必須！ビルド済みアセット
├── resources/
├── routes/
├── storage/
├── vendor/     ← composer install --no-dev で生成
├── .env.example
├── artisan
├── composer.json
├── composer.lock
└── package.json

アップロード不要:
├── node_modules/  ← アップロード不要
├── tests/
└── .git/
```

---

### Step 5: Composer依存関係をインストール

```bash
cd ~/schedule-navi

# Composerがインストールされているか確認
composer --version

# インストールされていない場合
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# 本番用依存関係をインストール
composer install --no-dev --optimize-autoloader --no-interaction
```

---

### Step 6: 環境変数を設定

```bash
cd ~/schedule-navi

# .envファイルを作成
cp .env.example .env
nano .env  # またはvi .env
```

以下の内容を設定：

```env
APP_NAME="Schedule-Navi"
APP_ENV=production
APP_KEY=  # Step 7で生成
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_LEVEL=error

# データベース設定（さくらインターネットの情報）
DB_CONNECTION=mysql
DB_HOST=mysqlXXX.db.sakura.ne.jp
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# セッション設定
SESSION_DRIVER=file
SESSION_LIFETIME=120

# キャッシュ設定
CACHE_DRIVER=file
QUEUE_CONNECTION=database

# メール設定（必要に応じて）
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Ctrl + O → Enter → Ctrl + X** で保存

---

### Step 7: アプリケーションキーを生成

```bash
php artisan key:generate
```

これで `.env` の `APP_KEY` が自動設定されます。

---

### Step 8: データベースマイグレーション実行

```bash
# マイグレーション実行
php artisan migrate --force

# 確認
php artisan migrate:status
```

**注意**: `--force` は本番環境で必須のオプションです。

---

### Step 9: パーミッション設定

```bash
cd ~/schedule-navi

# storage と cache ディレクトリの書き込み権限
chmod -R 775 storage bootstrap/cache

# ファイルの所有者を設定（さくらの場合は不要な場合が多い）
# chown -R www-data:www-data storage bootstrap/cache
```

---

### Step 10: キャッシュ最適化

```bash
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

### Step 11: シンボリックリンク設定

ストレージへのアクセス用：

```bash
php artisan storage:link
```

---

### Step 12: Webサーバー設定（さくらインターネット）

#### A. .htaccess を public/ に配置

`public/.htaccess` を作成/編集：

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

#### B. ドキュメントルートを public/ に設定

さくらインターネットのコントロールパネルで：

1. **ドメイン設定** → 対象ドメインを選択
2. **パス設定** を `/home/username/schedule-navi/public` に変更
3. 保存

または、シンボリックリンクを使う方法：

```bash
# www ディレクトリのバックアップ
mv ~/www ~/www_backup

# schedule-navi/public へのシンボリックリンクを作成
ln -s ~/schedule-navi/public ~/www
```

---

### Step 13: SSL証明書設定（Let's Encrypt）

さくらインターネットのコントロールパネルで：

1. **ドメイン/SSL** → **証明書**
2. **Let's Encrypt** を選択
3. ドメインを選択して証明書発行

**HTTPSリダイレクト設定** (`public/.htaccess` に追加):

```apache
# HTTPS強制リダイレクト
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

---

### Step 14: キュー（ジョブ）の設定

メール通知などのバックグラウンド処理用：

```bash
# キューワーカーをテスト実行
php artisan queue:work --tries=3 --timeout=90

# 問題なければ、Cronに登録
crontab -e
```

以下を追加：

```cron
# Laravelスケジューラー（毎分実行）
* * * * * cd /home/username/schedule-navi && php artisan schedule:run >> /dev/null 2>&1

# キューワーカーを5分ごとに再起動（メモリリーク対策）
*/5 * * * * cd /home/username/schedule-navi && php artisan queue:restart >> /dev/null 2>&1
```

**Supervisor（推奨）** が使える場合：

```ini
[program:schedule-navi-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /home/username/schedule-navi/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=username
numprocs=1
redirect_stderr=true
stdout_logfile=/home/username/schedule-navi/storage/logs/worker.log
stopwaitsecs=3600
```

---

### Step 15: 動作確認

ブラウザでアクセス：

```
https://yourdomain.com
```

#### 確認項目チェックリスト

- [ ] トップページが表示される
- [ ] イベント作成ができる
- [ ] 回答ができる
- [ ] 集計が正しく表示される
- [ ] CSS/JSが正しく読み込まれる
- [ ] HTTPSで接続できる
- [ ] robots.txtが表示される (`/robots.txt`)
- [ ] サイトマップが表示される (`/sitemap.xml`)

#### ログ確認

```bash
# エラーログを確認
tail -f ~/schedule-navi/storage/logs/laravel.log

# エラーがある場合
php artisan optimize:clear
chmod -R 775 storage bootstrap/cache
```

---

## 🔄 更新時の手順

コードを更新した際の手順：

```bash
# サーバーにSSH接続
ssh username@yourdomain.com

cd ~/schedule-navi

# 1. メンテナンスモードON
php artisan down

# 2. 最新コードを取得（Git使用時）
git pull origin main

# 3. 依存関係を更新
composer install --no-dev --optimize-autoloader

# 4. データベースマイグレーション（必要な場合）
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

## 🔧 トラブルシューティング

### 問題: "500 Internal Server Error"

```bash
# デバッグモードを一時的にON
nano .env
# APP_DEBUG=true に変更

# エラーログを確認
tail -50 storage/logs/laravel.log

# 終わったら必ずOFFに戻す
# APP_DEBUG=false
```

### 問題: "Class not found"

```bash
composer dump-autoload --optimize
php artisan optimize:clear
```

### 問題: CSS/JSが読み込まれない

```bash
# public/build/ が存在するか確認
ls -la public/build/

# なければローカルでビルドしてアップロード
# または
npm install
npm run build
```

### 問題: パーミッションエラー

```bash
chmod -R 775 storage bootstrap/cache
php artisan storage:link
```

### 問題: データベース接続エラー

```bash
# .envの設定を確認
cat .env | grep DB_

# データベース接続テスト
php artisan tinker
> DB::connection()->getPdo();
```

---

## 📊 パフォーマンス最適化（オプション）

### OPcache有効化

`php.ini` に追加（さくらの場合は不要な場合あり）：

```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
```

### Redis導入（高速化）

```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

---

## 🔒 セキュリティチェックリスト

- [ ] `APP_DEBUG=false` になっている
- [ ] `APP_ENV=production` になっている
- [ ] `.env` ファイルがGit管理外
- [ ] HTTPS接続が有効
- [ ] 不要なファイルが公開されていない
- [ ] データベース認証情報が安全
- [ ] CSRF保護が有効（Laravel標準）
- [ ] レート制限が設定されている（実装済み）

---

## 📝 本番環境で必要なファイル構成

```
~/schedule-navi/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
│   ├── build/          ← ビルド済みJS/CSS（必須）
│   ├── favicon.svg
│   ├── og-image.svg
│   ├── robots.txt
│   ├── .htaccess      ← Webサーバー設定
│   └── index.php
├── resources/
├── routes/
├── storage/
│   ├── app/
│   ├── framework/     ← 書き込み権限必要
│   └── logs/          ← 書き込み権限必要
├── vendor/            ← composer installで生成
├── .env              ← 本番用設定（必須）
├── artisan
├── composer.json
└── composer.lock
```

---

## 🎯 まとめ

### 初回デプロイ時に必要な作業

1. ✅ ローカルで `npm run build`
2. ✅ サーバーにコードをアップロード（Git or FTP）
3. ✅ `composer install --no-dev`（サーバー上）
4. ✅ `.env` 設定
5. ✅ `php artisan key:generate`
6. ✅ `php artisan migrate --force`
7. ✅ パーミッション設定
8. ✅ キャッシュ生成
9. ✅ Webサーバー設定

### 更新時に必要な作業

1. ✅ コード取得（git pull）
2. ✅ composer install
3. ✅ マイグレーション（必要時）
4. ✅ キャッシュクリア＆再生成

---

## 📞 サポート

問題が発生した場合：

1. `storage/logs/laravel.log` を確認
2. `.env` の設定を確認
3. パーミッションを確認
4. キャッシュをクリア

不明点があれば、お気軽にお問い合わせください。
