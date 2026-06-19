# CLUB LUMIÈRE（仮称） — Cabaret Club Website

大阪・北新地（仮）の高級キャバクラ向けウェブサイト。
**WordPress（PHPサーバー単体）＋ 自作の軽量カスタムテーマ**で構築します。

> リポジトリ名に `test` が含まれるため、現状はプロトタイプ／検証目的として進めています。
> 本番品質で実装し、本番ホスティングと独自ドメインは Phase 5 で確定します。

---

## 確定した構成（Architecture）

```
クライアント ─[ wp-admin で入稿 ]─┐
                                  ├─▶ WordPress (PHP + MySQL / 1台) ─▶ 訪問者
自作テーマ（HTML/CSS/JS・GSAP/Lenis）┘     ＝ CMS と公開サイトを兼ねる
```

- **WordPress 単体（PHP + MySQL）** … CMS と公開サイトを兼ねる1台構成。クライアントは `wp-admin` から入稿。
- **自作の軽量カスタムテーマ** … HTML/PHP テンプレート + CSS + JavaScript。デザインとアニメーションはコードで管理（このリポジトリ）。
- **Next.js / Vercel / Supabase は不使用**（シンプルさ優先。将来必要になれば拡張可能）。
- アニメーションの正体はブラウザ上の JavaScript（GSAP / Lenis）なので、WordPress 単体でも洗練された動きを実現できます。

### 技術スタック
| 領域 | 採用 |
|---|---|
| CMS | WordPress 6.x / PHP 8.x / MySQL |
| テーマ開発 | Vite + Tailwind CSS（軽量ビルド） |
| アニメーション | GSAP (ScrollTrigger) + Lenis（慣性スクロール）。必要に応じ barba.js / Swup（ページ遷移） |
| カスタムフィールド | WordPress 標準のメタボックス（外部プラグイン不要・自作プラグインで定義） |
| アイコン | 不使用（線・タイポグラフィ・モーションで表現） |

---

## サイト構成（Pages）
- トップ / コンセプト / キャスト紹介 / 料金システム / お知らせ(News) / 求人・採用 / アクセス・店舗情報 / ギャラリー

## コンテンツモデル（WordPress）

Phase 1 で以下を `lumiere-core` プラグインに実装済み（ACF 等の外部プラグインに依存しない標準実装）。

| 種別 | 実装 | 主なフィールド |
|---|---|---|
| キャスト | カスタム投稿タイプ `cast` | 写真(アイキャッチ), プロフィール(本文), ローマ字, キャッチコピー, 身長, 血液型, 誕生月, 趣味, Instagram/X, おすすめ表示, 表示順 |
| お知らせ | カスタム投稿タイプ `news` + タクソノミー `news_category` | 日付, カテゴリー, 本文, サムネイル, 抜粋 |
| 求人 | カスタム投稿タイプ `recruit` | 本文, 雇用形態, 給与, 勤務時間, 休日・待遇, 表示順 |
| 店舗情報/料金/SNS/ギャラリー | （Phase 2 で設定ページ等として実装予定） | 店名, 住所, TEL, 営業時間, 料金表, 画像 |

---

## デザイン方針
- **トーン**: 明るく華やかな高級感（参考: barcelona.co.jp/susukino のトーンに寄せて提案）
- **配色**: アイボリーホワイト基調 + 淡いブラッシュピンク + シャンパンゴールド（文字は温かみのあるダークブラウン）
- **タイポgrafィ**: 明朝体主体（Shippori Mincho / Noto Serif JP）+ 欧文 Cormorant Garamond、一部縦組み
- **モーション**: Lenis 慣性スクロール、GSAP ScrollTrigger によるパララックス / 行リビール / 横スクロール固定 / 数値カウント、フルスクリーンメニュー
- **アイコン完全排除**

---

## 開発ロードマップ
- [x] **Phase 0**: デザイン方針の確定（本リポジトリ `design-preview/` の実物プレビューで確認）
- [x] **Phase 1**: ローカル WordPress（Docker）+ コンテンツ基盤（`cast`/`news`/`recruit` のCPT・カスタムフィールド・サンプルデータ）
- [x] **Phase 2**: テーマのテンプレート実装（トップ・各一覧/詳細・ヘッダー/フッター/メニュー）＝ CMS データと接続
- [x] **Phase 3**: アニメーション実装（Lenis 慣性スクロール・GSAP リビール/横スクロール/数値カウント/パララックス・全画面メニュー）
- [x] **Phase 4**: 最適化・QA（SEO/OGPメタ・JSON-LD・head スリム化・script defer・画像サイズ／アクセシビリティ：スキップリンク・main ランドマーク・フォーカス表示・OGP画像）
- [ ] **Phase 5**: 本番デプロイ（PHPサーバー）・独自ドメイン（＋ページキャッシュ等の本番チューニング）

### テーマ構成（`wp-content/themes/lumiere/`）
`front-page.php`（トップ1ページ） / `archive-cast.php`・`single-cast.php` / `archive-news.php`・`single-news.php` / `archive-recruit.php`・`single-recruit.php` / `header.php`・`footer.php` / `template-parts/`（cast-card・news-item） / `inc/site-data.php`（店舗情報・料金等の一元データ） / `assets/`（CSS・JS）

---

## デザインプレビューの見方

**公開URL（GitHub Pages）**: https://sera-areslot.github.io/cabaret_club_test_wordpress/
（`main` への push で自動更新されます）

またはローカルで `design-preview/index.html` をブラウザで開いてください
（フォント・アニメーションを CDN から読み込むためインターネット接続が必要です）。

```bash
# 簡易ローカルサーバー（任意）
cd design-preview && python3 -m http.server 4173
# → http://localhost:4173 をブラウザで開く
```

> 注: プレビュー内のブランド名・キャスト・住所・料金等はすべて**仮のプレースホルダー**です。
> 実データは Phase 1 以降に WordPress 側で投入します。

---

## ローカル開発（WordPress / Docker）

Docker Desktop（または Docker Engine + Compose v2）が必要です。

```bash
cp .env.example .env        # 任意で編集
bash scripts/setup.sh       # 起動 + インストール + 有効化 + サンプル投入
```

- サイト: `http://localhost:8080/`
- 管理画面: `http://localhost:8080/wp-admin/`（既定 `admin` / `admin`）
- 左メニューに **キャスト / お知らせ / 求人** が表示されます。

| コマンド | 内容 |
|---|---|
| `docker compose up -d` | 起動 |
| `docker compose down` | 停止 |
| `docker compose down -v` | 破棄（DB・WP ごと初期化） |
| `bash scripts/seed.sh` | サンプル再投入（既存があれば自動スキップ） |

### ディレクトリ構成
```
design-preview/                 静的デザインプレビュー（Phase 0）
docker-compose.yml              ローカル WordPress（WP + MariaDB + WP-CLI）
wp-content/
  plugins/lumiere-core/         コンテンツ基盤プラグイン（CPT・カスタムフィールド）
  themes/lumiere/               カスタムテーマ（Phase 2 で本実装）
scripts/                        setup.sh / seed.sh
```

> 注: 本リポジトリを作成したセッションの実行環境は外部レジストリへの接続が制限されており、
> Docker イメージ取得が不可のため**起動確認はお手元のローカルで行ってください**
> （PHP 構文チェック `php -l` は実施済みです）。
