# 本番公開ガイド（Phase 5）

CLUB LUMIÈRE を本番のレンタルサーバー（PHP + MySQL）へ公開する手順です。
構成は **WordPress 単体 + 自作テーマ `lumiere` + プラグイン `lumiere-core`**。Vercel/Supabase は使いません。

---

## 1. サーバー要件
- PHP 8.0 以上（推奨 8.2+）
- MySQL 5.7+ / MariaDB 10.4+
- HTTPS（Let's Encrypt 等の無料SSLで可）
- 推奨ホスト例（国内・WordPress向け）: **エックスサーバー / ConoHa WING / さくらのレンタルサーバ**、海外マネージドなら **Kinsta / WP Engine**

## 2. デプロイ対象
このリポジトリのうち本番へ反映するのは以下の2つだけです（WordPress本体はホスト側で用意）。
```
wp-content/themes/lumiere/      ← テーマ
wp-content/plugins/lumiere-core/ ← コンテンツ基盤プラグイン
```
> `design-preview/`, `docker-compose.yml`, `scripts/`, `dev-seed.php` は開発用で本番不要です。

## 3. 手順
1. **WordPress を用意**：ホストの「WordPress簡単インストール」等で新規インストール。
2. **テーマ/プラグインを配置**：上記2ディレクトリを `wp-content/` 配下へアップロード（SFTP / Git デプロイ / 管理画面のZIPアップロードのいずれか）。
3. **有効化**：管理画面 → プラグイン「LUMIÈRE Core」を有効化 → 外観 → テーマ「LUMIÈRE」を有効化。
4. **パーマリンク**：設定 → パーマリンク → 「投稿名」(`/%postname%/`) で保存（`/cast/` 等の一覧URLが有効になります）。
5. **コンテンツ入力**：キャスト／お知らせ／求人を登録。キャストは「アイキャッチ画像」に写真を設定（未設定時は上品なグラデーション表示）。
6. **店舗情報・料金・SNS**：現在は `wp-content/themes/lumiere/inc/site-data.php` で一元管理。実値に書き換えるか、`lumiere_site_data` フィルターで上書き（将来は管理画面の設定ページ化も可能）。
7. **サイトタイトル**：設定 → 一般 で店名・キャッチフレーズを設定。
8. **検索エンジン公開**：設定 → 表示設定 → 「検索エンジンがサイトをインデックスしないようにする」の**チェックを外す**（公開時）。
9. **SSL/HTTPS**：ホストでSSLを有効化し、一般設定のURLを `https://` に。常時HTTPSへリダイレクト（下記 .htaccess 参照）。
10. **独自ドメイン**：DNS をホストに向け、WordPress アドレス/サイトアドレスをドメインに設定。

## 4. 本番チューニング（推奨）
- **ページキャッシュ**：`LiteSpeed Cache`（LiteSpeed系ホスト）または `WP Super Cache`。加えて **Cloudflare（無料）** でCDN/キャッシュ。
- **画像**：大きな写真はアップロード前に最適化。WebP変換プラグイン（EWWW 等）も有効。
- **アセットの自己ホスト（任意）**：現在 GSAP/Lenis/Google Fonts は CDN 読み込み。完全自己ホストにしたい場合は `functions.php` の `wp_enqueue_script/style` をローカルファイルに差し替え可能（ご希望あれば対応します）。

### .htaccess 追記例（Apache：gzip・ブラウザキャッシュ・HTTPS・最小セキュリティ）
WordPress の `# BEGIN WordPress` ブロックより**前**に追記：
```apache
# HTTPS 常時化
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteCond %{HTTPS} !=on
  RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>

# gzip 圧縮
<IfModule mod_deflate.c>
  AddOutputFilterByType DEFLATE text/html text/css application/javascript application/json image/svg+xml
</IfModule>

# ブラウザキャッシュ
<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresByType text/css "access plus 1 month"
  ExpiresByType application/javascript "access plus 1 month"
  ExpiresByType image/jpeg "access plus 1 year"
  ExpiresByType image/png "access plus 1 year"
  ExpiresByType image/webp "access plus 1 year"
  ExpiresByType image/svg+xml "access plus 1 year"
</IfModule>

# 最小セキュリティヘッダー
<IfModule mod_headers.c>
  Header set X-Content-Type-Options "nosniff"
  Header set X-Frame-Options "SAMEORIGIN"
  Header set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>
```

### wp-config.php 推奨設定
```php
define( 'DISALLOW_FILE_EDIT', true ); // 管理画面からのファイル編集を禁止
define( 'WP_POST_REVISIONS', 10 );    // リビジョン上限
define( 'WP_DEBUG', false );          // 本番はデバッグ無効
```
※ 認証用ソルト（SALT）は https://api.wordpress.org/secret-key/1.1/salt/ で再生成して設定。

## 5. 公開後チェック
- [ ] トップ／キャスト一覧・詳細／お知らせ／求人／アクセス が表示される
- [ ] スマホ・PC でスクロールアニメーションが動く
- [ ] OGP（SNS共有時の画像・タイトル）が出る（`https://〜/` を各SNSのデバッガで確認）
- [ ] 検索エンジン公開ON・SSL・独自ドメイン
- [ ] バックアップ（ホストの自動バックアップ or プラグイン）
- [ ] WordPress / プラグインの自動更新
