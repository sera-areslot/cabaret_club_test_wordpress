#!/usr/bin/env bash
# サンプルコンテンツ投入（WP-CLI の eval-file 経由）。再実行は安全（既存があればスキップ）。
set -euo pipefail
cd "$(dirname "$0")/.."

echo "▶ サンプルコンテンツを投入します..."
docker compose run --rm -T wpcli wp eval-file wp-content/plugins/lumiere-core/dev-seed.php
