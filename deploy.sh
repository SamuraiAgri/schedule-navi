#!/bin/bash
# deploy.sh - さくらサーバーへのデプロイスクリプト
# 使用方法: SSHでサーバーに接続後、 ~/www/schedule-navi で実行

set -e

echo "🚀 デプロイを開始します..."

# メンテナンスモードON
php artisan down --retry=60

# 最新コードを取得
echo "📥 最新コードを取得中..."
git pull origin main

# Composer依存関係を更新
echo "📦 Composer依存関係を更新中..."
composer install --no-dev --optimize-autoloader --no-interaction

# マイグレーション（新しいテーブルがある場合）
echo "🗄️ データベースマイグレーション..."
php artisan migrate --force

# キャッシュクリア
echo "🧹 キャッシュをクリア中..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

# キャッシュ再生成
echo "⚡ キャッシュを再生成中..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# パーミッション設定
chmod -R 775 storage bootstrap/cache

# メンテナンスモードOFF
php artisan up

echo "✅ デプロイ完了！"
echo "🌐 https://schedule-navi.com"
