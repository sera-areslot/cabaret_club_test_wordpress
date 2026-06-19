#!/usr/bin/env bash
# CLUB LUMIÈRE — 初回セットアップ
#   - コンテナ起動 → WordPress インストール → テーマ/プラグイン有効化 → サンプル投入
#
#   使い方:  bash scripts/setup.sh
set -euo pipefail
cd "$(dirname "$0")/.."

# .env が無ければ雛形からコピー
if [ ! -f .env ]; then
  cp .env.example .env
  echo "ℹ️  .env を作成しました（.env.example からコピー）"
fi

# .env を読み込み
set -a; # shellcheck disable=SC1091
source .env; set +a

WP_URL="${WP_URL:-http://localhost:8080}"

echo "▶ コンテナを起動します..."
docker compose up -d db wordpress

echo "▶ WordPress(コアファイル/DB) の準備を待機します..."
for i in $(seq 1 60); do
  if docker compose run --rm wpcli wp core version >/dev/null 2>&1; then
    break
  fi
  sleep 3
done

if docker compose run --rm wpcli wp core is-installed >/dev/null 2>&1; then
  echo "ℹ️  既にインストール済みです。"
else
  echo "▶ WordPress をインストールします..."
  docker compose run --rm wpcli wp core install \
    --url="${WP_URL}" \
    --title="CLUB LUMIÈRE" \
    --admin_user="${WP_ADMIN_USER:-admin}" \
    --admin_password="${WP_ADMIN_PASSWORD:-admin}" \
    --admin_email="${WP_ADMIN_EMAIL:-admin@example.com}" \
    --skip-email
fi

echo "▶ パーマリンク・言語・テーマ・プラグインを設定します..."
docker compose run --rm wpcli wp rewrite structure '/%postname%/' --hard
docker compose run --rm wpcli wp language core install ja_JP >/dev/null 2>&1 || true
docker compose run --rm wpcli wp site switch-language ja_JP >/dev/null 2>&1 || true
docker compose run --rm wpcli wp plugin activate lumiere-core
docker compose run --rm wpcli wp theme activate lumiere || true

echo "▶ サンプルコンテンツを投入します..."
bash scripts/seed.sh

echo ""
echo "✅ 完了しました。"
echo "   サイト   : ${WP_URL}/"
echo "   管理画面 : ${WP_URL}/wp-admin/  (${WP_ADMIN_USER:-admin} / ${WP_ADMIN_PASSWORD:-admin})"
echo "   ※ キャスト/お知らせ/求人 が左メニューに表示されます。"
