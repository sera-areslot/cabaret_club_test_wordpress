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
| カスタムフィールド | ACF（無料）ほか |
| アイコン | 不使用（線・タイポグラフィ・モーションで表現） |

---

## サイト構成（Pages）
- トップ / コンセプト / キャスト紹介 / 料金システム / お知らせ(News) / 求人・採用 / アクセス・店舗情報 / ギャラリー

## コンテンツモデル（WordPress・予定）
| 種別 | 実装 | 主なフィールド |
|---|---|---|
| キャスト | カスタム投稿タイプ `cast` | 写真, プロフィール, 身長/誕生日/血液型等の属性, 表示順, SNS |
| お知らせ | 投稿 or `news` | 日付, カテゴリ, 本文, サムネイル |
| 求人 | 固定ページ + ACF | 職種, 待遇, 条件, FAQ |
| 店舗情報/料金/SNS | ACF オプションページ | 店名, 住所, TEL, 営業時間, 料金表 |
| ギャラリー | ACF ギャラリー | 画像 |

---

## デザイン方針
- **トーン**: 大人・高級感・洗練（参考: barcelona.co.jp/susukino のトーンに寄せて提案）
- **配色**: 黒〜チャコール基調 + シャンパンゴールド + オフホワイト
- **タイポgrafィ**: 明朝体主体（Shippori Mincho / Noto Serif JP）+ 欧文 Cormorant Garamond、一部縦組み
- **モーション**: Lenis 慣性スクロール、GSAP ScrollTrigger によるパララックス / 行リビール / 横スクロール固定 / 数値カウント、フルスクリーンメニュー
- **アイコン完全排除**

---

## 開発ロードマップ
- [x] **Phase 0**: デザイン方針の確定（本リポジトリ `design-preview/` の実物プレビューで確認）
- [ ] **Phase 1**: ローカル WordPress（Docker）+ コンテンツモデル（CPT / ACF）
- [ ] **Phase 2**: テーマのテンプレート実装（全ページ）
- [ ] **Phase 3**: アニメーション実装
- [ ] **Phase 4**: 最適化（キャッシュ / 画像 / SEO / OGP）・QA
- [ ] **Phase 5**: 本番デプロイ（PHPサーバー）・独自ドメイン

---

## デザインプレビューの見方

`design-preview/index.html` をブラウザで開いてください
（フォント・アニメーションを CDN から読み込むためインターネット接続が必要です）。

```bash
# 簡易ローカルサーバー（任意）
cd design-preview && python3 -m http.server 4173
# → http://localhost:4173 をブラウザで開く
```

> 注: プレビュー内のブランド名・キャスト・住所・料金等はすべて**仮のプレースホルダー**です。
> 実データは Phase 1 以降に WordPress 側で投入します。
