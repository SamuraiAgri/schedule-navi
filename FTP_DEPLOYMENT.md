# FTP/SFTP デプロイ手順（Git不使用）

Gitを使わずにFTP/SFTPで直接アップロードする方法です。

---

## 必要なツール

- **FileZilla** (推奨): https://filezilla-project.org/
- または **WinSCP**: https://winscp.net/

---

## Step 1: さくらサーバーの接続情報を確認

さくらのコントロールパネルで確認：

```
ホスト名: あなたのアカウント名.sakura.ne.jp
ユーザー名: あなたのアカウント名
パスワード: サーバーのパスワード
ポート: 22 (SFTP)
プロトコル: SFTP - SSH File Transfer Protocol
```

---

## Step 2: FileZillaで接続

1. FileZillaを起動
2. 上部のクイック接続バー：
   - ホスト: `sftp://アカウント名.sakura.ne.jp`
   - ユーザー名: `アカウント名`
   - パスワード: `サーバーパスワード`
   - ポート: `22`
3. **クイック接続** をクリック

---

## Step 3: アップロードするファイル・フォルダ

### ローカル（左側）
```
C:\xampp\htdocs\schedule-navi
```

### サーバー（右側）
```
/home/laravel-times/www/schedule-navi
```

### アップロード対象

以下を**必ず**アップロード：

```
✅ アップロード必須:
├── app/                    ← 全てのディレクトリとファイル
├── bootstrap/
│   ├── app.php
│   ├── providers.php
│   └── cache/             ← 中身は空でOK（.gitignoreのみ）
├── config/                ← 全ての設定ファイル
├── database/
│   ├── migrations/        ← マイグレーションファイル必須
│   ├── factories/
│   └── seeders/
├── public/
│   ├── build/            ← ビルド済みアセット（必須！）
│   ├── favicon.svg
│   ├── og-image.svg
│   ├── robots.txt
│   └── index.php         ← 必須
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── routes/               ← ルート定義
├── storage/
│   ├── app/
│   │   ├── private/
│   │   └── public/
│   ├── framework/
│   │   ├── cache/
│   │   ├── sessions/
│   │   ├── testing/
│   │   └── views/
│   └── logs/
├── vendor/               ← Composer依存関係（全て）
├── .env.example          ← テンプレート
├── artisan               ← Laravelコマンド
├── composer.json
├── composer.lock         ← 必須
├── package.json
├── DEPLOYMENT.md         ← 手順書
└── SAKURA_DEPLOYMENT.md  ← 手順書

❌ アップロード不要:
├── .git/                 ← Git管理情報
├── node_modules/         ← npm依存関係
├── tests/                ← テストファイル
├── .env                  ← ローカル設定（サーバーで別途作成）
└── phpunit.xml
```

---

## Step 4: アップロード実行

### 推奨方法：ディレクトリごとアップロード

1. 左側（ローカル）で `schedule-navi` フォルダを選択
2. 右側（サーバー）で `/home/laravel-times/www` を開く
3. `schedule-navi` フォルダを右クリック → **アップロード**
4. 完了を待つ（10〜20分程度）

### 注意点

- **public/build/** ディレクトリが必ずアップロードされていることを確認
- **vendor/** ディレクトリが完全にアップロードされていることを確認（サイズ大）
- `.env` ファイルは**アップロードしない**（サーバーで別途作成）

---

## Step 5: 転送モードの確認

FileZilla設定：
1. **編集** → **設定** → **転送** → **ファイルタイプ**
2. **自動** または **バイナリ** を選択（推奨）

---

## Step 6: アップロード後の確認（SSH）

```bash
ssh laravel-times@laravel-times.sakura.ne.jp

cd ~/www/schedule-navi

# ディレクトリ構造を確認
ls -la

# 必須ファイルの存在確認
ls -la public/build/
ls -la vendor/
ls -la artisan
```

確認項目：
- [ ] `public/build/` ディレクトリが存在する
- [ ] `public/build/manifest.json` が存在する
- [ ] `public/build/assets/` 内にJSとCSSファイルがある
- [ ] `vendor/` ディレクトリが存在する
- [ ] `vendor/autoload.php` が存在する
- [ ] `artisan` ファイルが存在する

---

## Step 7: Composerで依存関係を再インストール（推奨）

FTPでvendorをアップロードしても、サーバー環境の違いでエラーが出る場合があります。
再インストールを推奨：

```bash
cd ~/www/schedule-navi

# vendorを削除
rm -rf vendor

# 再インストール
composer install --no-dev --optimize-autoloader --no-interaction
```

---

## Step 8: 以降の手順

SAKURA_DEPLOYMENT.md の **Step 6: 環境変数を設定** から続行してください。

---

## 更新時の手順

コード修正後、再度アップロード：

```bash
# SSH接続
ssh laravel-times@laravel-times.sakura.ne.jp

cd ~/www/schedule-navi

# メンテナンスモードON
php artisan down

# FileZillaで変更ファイルのみアップロード

# キャッシュクリア
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# メンテナンスモードOFF
php artisan up
```

---

## トラブルシューティング

### 問題: アップロードが途中で止まる

- 接続タイムアウトの可能性
- **転送** → **再接続して再開** を試す
- または、フォルダを分割してアップロード

### 問題: パーミッションエラー

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 問題: "Class not found" エラー

```bash
cd ~/www/schedule-navi
composer dump-autoload --optimize
```

---

## 推奨: 差分アップロードの設定

FileZillaで効率的に更新：

1. **表示** → **ディレクトリの比較** → **有効化**
2. 変更されたファイルのみ黄色でハイライト
3. 黄色のファイルだけを選択してアップロード

---

## まとめ

FTP/SFTP方式の場合：

1. ✅ ローカルでビルド（`npm run build`）
2. ✅ FileZillaで全ファイルをアップロード
3. ✅ サーバーで `composer install --no-dev`
4. ✅ `.env` 作成と設定
5. ✅ `php artisan migrate --force`
6. ✅ パーミッション設定

以降は SAKURA_DEPLOYMENT.md の手順に従ってください。
