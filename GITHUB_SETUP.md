# GitHubリポジトリセットアップ手順

## Step 1: GitHubでリポジトリを作成

1. https://github.com にログイン
2. 右上の **+** → **New repository**
3. 以下の設定：
   - Repository name: `schedule-navi`
   - Description: `スケジュール調整サービス Schedule-Navi`
   - Private or Public: お好みで選択
   - **Do not initialize** this repository (READMEやlicenseは追加しない)
4. **Create repository** をクリック

## Step 2: ローカルからプッシュ（PowerShell）

```powershell
# プロジェクトディレクトリに移動
cd C:\xampp\htdocs\schedule-navi

# リモートリポジトリのURLを確認（既に設定されている場合）
git remote -v

# リモートが設定されていない場合、追加
# ⚠️ あなたのGitHubユーザー名に置き換えてください
git remote add origin https://github.com/あなたのユーザー名/schedule-navi.git

# または既に設定されている場合は変更
git remote set-url origin https://github.com/あなたのユーザー名/schedule-navi.git

# ブランチ名を確認
git branch

# masterブランチをmainにリネーム（GitHubの推奨）
git branch -M main

# プッシュ
git push -u origin main
```

### 認証について

GitHubは**パスワード認証を廃止**しました。以下のいずれかを使用：

#### 方法A: Personal Access Token（推奨）

1. GitHub → **Settings** → **Developer settings** → **Personal access tokens** → **Tokens (classic)**
2. **Generate new token** → **Generate new token (classic)**
3. Note: `schedule-navi deployment`
4. Expiration: `90 days` または `No expiration`
5. Scopes: `repo` にチェック
6. **Generate token**
7. トークンをコピー（この画面を閉じると二度と表示されません！）

プッシュ時：
```
Username: あなたのGitHubユーザー名
Password: コピーしたトークン（パスワードではない）
```

#### 方法B: GitHub CLI（簡単）

```powershell
# GitHub CLIをインストール
winget install GitHub.cli

# 認証
gh auth login
# → GitHub.com を選択
# → HTTPS を選択
# → Yes (authenticate Git with your credentials)
# → ブラウザで認証

# プッシュ
git push -u origin main
```

## Step 3: さくらサーバーでクローン

SSHで接続後：

```bash
cd ~/www

# 正しいURLでクローン（あなたのユーザー名に置き換え）
git clone https://github.com/あなたのユーザー名/schedule-navi.git schedule-navi

cd schedule-navi
```

### さくらサーバーでの認証

GitHub CLI をさくらサーバーにインストールできない場合、Personal Access Tokenを使用：

```bash
# Username: あなたのGitHubユーザー名
# Password: Personal Access Token
```

---

## GitHubユーザー名の確認方法

GitHubにログイン後、右上のアイコンをクリック → **Your profile**
URLが `https://github.com/ユーザー名` になっています。

例：`https://github.com/SamuraiAgri` なら、ユーザー名は `SamuraiAgri`

---

## トラブルシューティング

### エラー: "remote: Invalid username or token"

- Personal Access Tokenを使用していますか？
- Tokenの有効期限が切れていませんか？
- Token作成時に `repo` スコープにチェックを入れましたか？

### エラー: "fatal: repository already exists"

```bash
cd ~/www
rm -rf schedule-navi
git clone https://github.com/ユーザー名/schedule-navi.git schedule-navi
```
