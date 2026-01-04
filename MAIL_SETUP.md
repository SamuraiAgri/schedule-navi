# メール設定ガイド - Schedule-Navi

## さくらレンタルサーバーでのメール設定

### 1. メールアドレス作成済み
- `support@schedule-navi.com`

### 2. 本番環境 .env 設定

```env
MAIL_MAILER=smtp
MAIL_HOST=初期ドメイン.sakura.ne.jp
MAIL_PORT=587
MAIL_USERNAME=support@schedule-navi.com
MAIL_PASSWORD=JHzS]GO6Unsx1rY<
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@schedule-navi.com
MAIL_FROM_NAME="Schedule-Navi"
```

### 3. SMTPホスト名について
さくらサーバーのSMTPホストは以下のいずれかを使用：
- `初期ドメイン.sakura.ne.jp`（例：`abc12345.sakura.ne.jp`）
- または `ユーザー名.sakura.ne.jp`

コントロールパネルの「サーバ情報」で確認できます。

### 4. テストメール送信
サーバーで以下のコマンドを実行してテスト：
```bash
php artisan tinker
>>> Mail::raw('テストメール', function($m) { $m->to('your-email@example.com')->subject('Test'); });
```

### 5. トラブルシューティング
- 送信できない場合は、MAIL_PORTを465に変更し、MAIL_ENCRYPTIONをsslに変更
- さくらサーバーでは、送信元ドメインが同一でないとエラーになる場合があります
