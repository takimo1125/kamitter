# Kamitter

Twitter 自動化ツール - ツイート、いいね、フォローなどを自動化し、アカウント運用を効率化する Web アプリケーション

## 📋 目次

-   [アプリの概要](#アプリの概要)
-   [開発背景](#開発背景)
-   [主な機能](#主な機能)
-   [使用技術](#使用技術)
-   [技術スタックの詳細](#技術スタックの詳細)
-   [環境構築](#環境構築)
-   [API 仕様](#api仕様)
-   [データベース設計](#データベース設計)
-   [テスト](#テスト)
-   [デプロイ](#デプロイ)
-   [コントリビューション](#コントリビューション)
-   [ライセンス](#ライセンス)

## アプリの概要

Kamitter は、Twitter アカウントの運用を自動化するための Web アプリケーションです。複数の Twitter アカウントを一元管理し、スケジュールに基づいた自動ツイート、いいね、フォロー・アンフォローなどの操作を実行できます。

### 主な特徴

-   **複数アカウント対応**: 1 つのアカウントで複数の Twitter アカウントを管理
-   **スケジュール機能**: 指定した日時に自動でツイートを投稿
-   **自動いいね**: 条件に合致するツイートに自動でいいねを付与
-   **自動フォロー**: キーワードベースでフォロワーを獲得
-   **自動アンフォロー**: フォローバックしてないユーザーを自動でアンフォロー
-   **監視機能**: アカウント停止や制限を自動で検知

## 開発背景

### 課題

-   Twitter 運用に多くの時間を割く必要がある
-   複数の Twitter アカウントを個別に管理するのが煩雑
-   継続的なフォロー・いいねが手作業では限界
-   アカウント運用の効率化が必要

### 解決策

自動化機能を統合した Web アプリケーションにより、以下の課題を解決：

1. **時間の節約**: スケジュール機能で事前にツイートを設定し、自動投稿
2. **一元管理**: 複数の Twitter アカウントを 1 つのダッシュボードで管理
3. **運用最適化**: 自動フォロー・アンフォローの機能により、効率的なフォロワー拡大
4. **安全性**: フレンドリーなレート制限とフィルタリング機能でアカウント保護

## 主な機能

### 1. 自動ツイート機能

-   スケジュール投稿: 日時を指定してツイートを自動投稿
-   複数アカウント対応: 異なる Twitter アカウントに同時投稿
-   ステータス管理: 未送信・ツイート済みのステータスを管理

### 2. 自動いいね機能

-   キーワードフィルタリング: 指定したキーワードを含むツイートを自動でいいね
-   条件設定: 除外ワードやタイミングを細かく設定可能
-   レート制限対応: Twitter のレート制限に準拠した自動実行

### 3. 自動フォロー機能

-   ターゲット設定: 特定のキーワードで検索し、条件に合致するユーザーを自動フォロー
-   履歴管理: フォロー履歴を保存し、重複を防止

### 4. 自動アンフォロー機能

-   フォローバック監視: フォローバックしないユーザーを自動でアンフォロー
-   アクティブユーザー検査: 非アクティブなユーザーを検出
-   アンフォロー履歴: 操作履歴を記録

### 5. システム管理機能

-   オン/オフ制御: システム全体の稼働状況を管理
-   ステータス表示: 各機能の実行状況をダッシュボードで確認

### 6. 認証・セキュリティ

-   OAuth 認証: Twitter API を使用した安全な認証
-   セッション管理: CSRF トークンによる脆弱性対策
-   複数アカウント切り替え: 管理画面で簡単にアカウントを切り替え可能

## 使用技術

### フロントエンド

| 技術        | バージョン | 用途                 |
| ----------- | ---------- | -------------------- |
| Vue.js      | 2.6.10     | UI フレームワーク    |
| Vue Router  | 3.1.3      | ルーティング管理     |
| Vuex        | 3.1.2      | 状態管理             |
| Element UI  | 2.13.0     | UI コンポーネント    |
| Axios       | 0.19       | HTTP クライアント    |
| SASS        | 1.15.2     | CSS プリプロセッサ   |
| Webpack     | -          | モジュールバンドラー |
| Laravel Mix | 4.0.7      | ビルドツール         |

### バックエンド

| 技術              | バージョン | 用途                        |
| ----------------- | ---------- | --------------------------- |
| PHP               | 7.2+       | サーバーサイド言語          |
| Laravel Framework | 6.2        | Web フレームワーク          |
| MySQL/PostgreSQL  | -          | データベース                |
| Guzzle HTTP       | 6.5        | HTTP リクエストクライアント |
| TwitterOAuth      | 1.1        | Twitter API 連携            |
| Laravel Socialite | 4.3        | OAuth 認証                  |
| SendGrid          | 2.1        | メール送信                  |

### インフラ・開発環境

| 技術     | 用途                 |
| -------- | -------------------- |
| Composer | PHP 依存管理         |
| NPM      | JavaScript 依存管理  |
| PHPUnit  | テストフレームワーク |
| Git      | バージョン管理       |

## 技術スタックの詳細

### フロントエンド構成

#### アーキテクチャ

```
resources/js/
├── app.js              # エントリーポイント
├── app.vue             # ルートコンポーネント
├── router.js           # ルーティング設定
├── components/         # 再利用可能なコンポーネント
│   ├── Header.vue
│   ├── Sidebar.vue
│   └── ...
├── pages/              # ページコンポーネント
│   ├── Login.vue
│   ├── Dashboard.vue
│   └── ...
└── store/              # Vuexストア
    ├── modules/
    └── index.js
```

#### 状態管理

Vuex を使用した状態管理により、グローバルな状態を一元管理：

-   **ユーザー状態**: ログイン状態、プロフィール情報
-   **ツイート状態**: 自動ツイートの設定とリスト
-   **いいね状態**: 自動いいねの設定とフィルター
-   **システム状態**: システムの稼働状況

### バックエンド構成

#### アーキテクチャ

```
app/
├── Console/
│   └── Commands/       # 自動化コマンド
│       ├── AutoTweet.php
│       ├── AutoLike.php
│       ├── AutoFollow.php
│       └── AutoUnfollow.php
├── Http/
│   ├── Controllers/    # APIコントローラー
│   ├── Middleware/     # ミドルウェア
│   └── Requests/       # フォームリクエスト
├── Providers/          # サービスプロバイダー
├── Models/             # Eloquentモデル
└── Mail/               # メール送信クラス
```

#### スケジューラー

Laravel のスケジューラーを使用し、以下のタイミングで自動実行：

-   **自動ツイート**: 1 分ごとに実行
-   **自動いいね**: 毎時 50 分と 51 分に実行
-   **自動アンフォロー**: 毎時 0 分と 1 分に実行
-   **自動フォロー**: 毎時 0 分と 1 分に実行
-   **フォローバック監視**: 毎時 10 分と 11 分に実行
-   **アクティブユーザー監視**: 毎時 30 分と 31 分に実行

### データベース設計

#### ER 図

主要なテーブル構成：

```
users (ユーザー)
├── twitter_users (Twitterアカウント情報)
├── automatic_tweets (自動ツイート設定)
├── automatic_likes (自動いいね設定)
├── follow_targets (フォローターゲット)
├── follow_histories (フォロー履歴)
├── unfollow_targets (アンフォローターゲット)
├── unfollow_histories (アンフォロー履歴)
├── filter_words (フィルター単語)
└── password_resets (パスワードリセット)
```

#### 主なリレーションシップ

-   `users` 1:N `twitter_users` - 1 ユーザーが複数の Twitter アカウントを持つ
-   `users` 1:N `automatic_tweets` - ユーザーごとの自動ツイート設定
-   `users` 1:N `automatic_likes` - ユーザーごとの自動いいね設定
-   `twitter_users` 1:N `automatic_tweets` - Twitter アカウントごとの自動ツイート
-   `filter_words` 1:N `automatic_likes` - フィルター単語との関係

## 環境構築

### 必要要件

-   PHP 7.2 以上
-   Composer
-   Node.js 8.12.0
-   NPM 6.0.0
-   MySQL 5.7 以上 / PostgreSQL 10 以上
-   Twitter Developer アカウント

### セットアップ手順

#### 1. リポジトリのクローン

```bash
git clone https://github.com/your-username/kamitter.git
cd kamitter
```

#### 2. 依存関係のインストール

```bash
# PHP依存関係のインストール
composer install

# JavaScript依存関係のインストール
npm install
```

#### 3. 環境変数の設定

`.env.example`をコピーして`.env`ファイルを作成：

```bash
cp .env.example .env
```

`.env`ファイルを編集し、以下の設定を行います：

```env
APP_NAME=Kamitter
APP_ENV=local
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kamitter
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Twitter API認証情報
TWITTER_CLIENT_ID=your_twitter_client_id
TWITTER_CLIENT_SECRET=your_twitter_client_secret
CALLBACK_URL=http://localhost/auth/twitter/callback

# メール送信設定（SendGrid）
SENDGRID_API_KEY=your_sendgrid_api_key
MAIL_DRIVER=sendgrid
```

#### 4. アプリケーションキーの生成

```bash
php artisan key:generate
```

#### 5. データベースのセットアップ

```bash
# マイグレーション実行
php artisan migrate

# シーダー実行（オプション）
php artisan db:seed
```

#### 6. フロントエンドのビルド

開発環境用：

```bash
npm run dev
```

本番環境用：

```bash
npm run production
```

#### 7. サーバーの起動

```bash
# 開発サーバー起動
php artisan serve

# または HTTPS経由
php artisan serve --host=0.0.0.0 --port=8000
```

#### 8. スケジューラーの設定

cron を設定してスケジューラーを有効化：

```bash
# crontabを編集
crontab -e

# 以下の行を追加
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### 開発環境の起動確認

ブラウザで以下の URL にアクセス：

```
http://localhost:8000
```

### トラブルシューティング

#### コンポーネントが表示されない

```bash
npm run watch
```

で開発サーバーを起動し、ホットリロードが有効かを確認してください。

#### スケジューラーが動作しない

```bash
# 手動でスケジューラーを実行
php artisan schedule:run

# 特定のコマンドをテスト
php artisan auto:tweet
php artisan auto:like
```

## API 仕様

### 認証 API

#### 会員登録

```http
POST /api/register
Content-Type: application/json

{
  "name": "username",
  "email": "user@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

#### ログイン

```http
POST /api/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}
```

#### ログアウト

```http
POST /api/logout
```

#### ユーザー情報取得

```http
GET /api/user
```

### Twitter 認証 API

#### Twitter 連携開始

```http
GET /auth/twitter/oauth
```

#### Twitter コールバック

```http
GET /auth/twitter/callback
```

#### Twitter ログアウト

```http
POST /api/twitter/logout
```

#### Twitter アカウント削除

```http
DELETE /api/twitter/{id}
```

#### アクティブ Twitter アカウント ID 取得

```http
GET /api/twitter/id
```

#### アクティブ Twitter アカウント ID 設定

```http
POST /api/twitter/{id}
```

#### Twitter アカウント一覧取得

```http
GET /api/twitter/user/list
```

#### Twitter アカウント情報取得

```http
GET /api/twitter/user/info/{id}
```

### 自動ツイート API

#### ツイート追加

```http
POST /api/tweet
Content-Type: application/json

{
  "twitter_user_id": 1,
  "text": "ツイート内容",
  "submit_date": "2024-01-01 12:00:00"
}
```

#### ツイート一覧取得

```http
GET /api/tweet
```

#### ツイート編集

```http
PUT /api/tweet/{id}
Content-Type: application/json

{
  "text": "更新後のツイート内容",
  "submit_date": "2024-01-01 12:00:00"
}
```

#### ツイート削除

```http
DELETE /api/tweet/{id}
```

### 自動いいね API

#### いいね設定追加

```http
POST /api/like
Content-Type: application/json

{
  "twitter_user_id": 1,
  "filter_word_id": 1,
  "search_word": "検索キーワード"
}
```

#### いいね設定一覧取得

```http
GET /api/like
```

#### いいね設定編集

```http
PUT /api/like/{id}
Content-Type: application/json

{
  "filter_word_id": 1,
  "search_word": "更新後の検索キーワード"
}
```

#### いいね設定削除

```http
DELETE /api/like/{id}
```

### フィルター単語 API

#### フィルター単語追加

```http
POST /api/filter
Content-Type: application/json

{
  "twitter_user_id": 1,
  "word": "フィルター単語"
}
```

#### フィルター単語一覧取得

```http
GET /api/filter
```

#### フィルター単語詳細取得

```http
GET /api/filter/{id}
```

#### フィルター単語編集

```http
PUT /api/filter/{id}
Content-Type: application/json

{
  "word": "更新後のフィルター単語"
}
```

#### フィルター単語削除

```http
DELETE /api/filter/{id}
```

### システム管理 API

#### システム状態取得

```http
GET /api/system/status
```

#### システム起動

```http
POST /api/system/run
```

#### システム停止

```http
POST /api/system/stop
```

## テスト

### テスト実行

```bash
# 全テスト実行
vendor/bin/phpunit

# 特定のテストファイルを実行
vendor/bin/phpunit tests/Feature/LoginApiTest.php

# 詳細な出力
vendor/bin/phpunit --verbose
```

### テストカバレッジ

主要なテストファイル：

-   `tests/Feature/LoginApiTest.php` - ログイン API
-   `tests/Feature/RegisterApiTest.php` - 会員登録 API
-   `tests/Feature/LogoutApiTest.php` - ログアウト API
-   `tests/Feature/UserApiTest.php` - ユーザー API

## デプロイ

### 本番環境へのデプロイ手順

#### 1. サーバー環境の準備

-   PHP 7.2+
-   Composer
-   Node.js & NPM
-   MySQL/PostgreSQL
-   Web サーバー（Apache/Nginx）

#### 2. コードのデプロイ

```bash
# リポジトリをクローン
git clone https://github.com/your-username/kamitter.git

# 依存関係のインストール（本番モード）
composer install --no-dev --optimize-autoloader
npm ci --production

# 環境変数の設定
cp .env.example .env
# .envファイルを編集

# アプリケーションキーの生成
php artisan key:generate

# キャッシュの最適化
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 3. データベースのセットアップ

```bash
# マイグレーション実行
php artisan migrate --force

# シーダー実行（必要に応じて）
php artisan db:seed --force
```

#### 4. フロントエンドのビルド

```bash
npm run production
```

#### 5. スケジューラーの設定

crontab を設定：

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

#### 6. Web サーバーの設定

Nginx 設定例：

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path-to-your-project/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### 7. 権限の設定

```bash
chown -R www-data:www-data /path-to-your-project
chmod -R 755 /path-to-your-project
chmod -R 775 /path-to-your-project/storage
chmod -R 775 /path-to-your-project/bootstrap/cache
```

### Heroku へのデプロイ

```bash
# Heroku CLIログイン
heroku login

# アプリケーション作成
heroku create kamitter-prod

# 環境変数設定
heroku config:set APP_KEY=$(php artisan key:generate --show)
heroku config:set DB_CONNECTION=mysql
heroku config:set DB_HOST=...
# その他の環境変数設定

# デプロイ
git push heroku master

# マイグレーション実行
heroku run php artisan migrate

# スケジューラー設定
heroku addons:create scheduler:standard
# Heroku Schedulerでコマンドを追加: php artisan schedule:run
```

## コントリビューション

### 開発フロー

1. **ブランチの作成**

```bash
git checkout -b feature/your-feature-name
```

2. **変更のコミット**

```bash
git add .
git commit -m "Add some feature"
```

3. **プッシュ**

```bash
git push origin feature/your-feature-name
```

4. **プルリクエストの作成**

GitHub 上でプルリクエストを作成し、変更内容を説明してください。

### コーディング規約

-   PSR-12 コーディング規約に従う
-   各機能には適切なテストを記述する
-   コミットメッセージは明確に記述する

## ライセンス

MIT License

Copyright (c) 2024 Kamitter

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
