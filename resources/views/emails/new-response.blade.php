<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新しい回答がありました</title>
    <style>
        body {
            font-family: 'Noto Sans JP', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #3B82F6;
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .content {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }
        .info-box {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 4px;
        }
        .value {
            font-size: 16px;
            font-weight: 500;
        }
        .button {
            display: inline-block;
            background-color: #3B82F6;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            margin: 10px 5px 10px 0;
        }
        .button-secondary {
            background-color: #6b7280;
        }
        .footer {
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0; font-size: 20px;">Schedule-Navi</h1>
    </div>
    
    <div class="content">
        <h2 style="margin-top: 0;">新しい回答がありました</h2>
        
        <p>
            「<strong>{{ $event->title }}</strong>」に新しい回答が投稿されました。
        </p>
        
        <div class="info-box">
            <div class="label">回答者</div>
            <div class="value">{{ $response->display_name }}</div>
        </div>
        
        @if($response->comment)
        <div class="info-box">
            <div class="label">コメント</div>
            <div class="value">{{ $response->comment }}</div>
        </div>
        @endif
        
        <div class="info-box">
            <div class="label">回答日時</div>
            <div class="value">{{ $response->created_at->format('Y年m月d日 H:i') }}</div>
        </div>
        
        <div style="margin-top: 20px;">
            <a href="{{ $eventUrl }}" class="button">集計を確認する</a>
            <a href="{{ $editUrl }}" class="button button-secondary">イベントを編集</a>
        </div>
    </div>
    
    <div class="footer">
        <p>
            このメールは Schedule-Navi から自動送信されています。<br>
            通知を停止するには、イベントの編集画面で通知設定を変更してください。
        </p>
    </div>
</body>
</html>
