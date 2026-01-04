# deploy-prepare.ps1 - ローカルでビルドしてコミット・プッシュ
# 使用方法: PowerShellで実行

Write-Host "🔨 フロントエンドをビルド中..." -ForegroundColor Cyan
npm run build

if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ ビルドに失敗しました" -ForegroundColor Red
    exit 1
}

Write-Host "✅ ビルド完了" -ForegroundColor Green

# ビルドファイルをステージング
git add public/build/

# 変更があるか確認
$status = git status --porcelain
if ($status) {
    Write-Host "📝 変更をコミット中..." -ForegroundColor Cyan
    
    # コミットメッセージを入力
    $message = Read-Host "コミットメッセージを入力してください"
    if ([string]::IsNullOrWhiteSpace($message)) {
        $message = "Update: $(Get-Date -Format 'yyyy-MM-dd HH:mm')"
    }
    
    git add .
    git commit -m $message
    
    Write-Host "🚀 GitHubにプッシュ中..." -ForegroundColor Cyan
    git push origin main
    
    Write-Host ""
    Write-Host "✅ プッシュ完了！" -ForegroundColor Green
    Write-Host ""
    Write-Host "次のステップ:" -ForegroundColor Yellow
    Write-Host "1. さくらサーバーにSSH接続" -ForegroundColor White
    Write-Host "   ssh laravel-times@laravel-times.sakura.ne.jp" -ForegroundColor Gray
    Write-Host ""
    Write-Host "2. デプロイスクリプトを実行" -ForegroundColor White
    Write-Host "   cd ~/www/schedule-navi && bash deploy.sh" -ForegroundColor Gray
} else {
    Write-Host "📭 変更がありません" -ForegroundColor Yellow
}
