#!/usr/bin/env sh
set -eu

if [ -f .env.local ]; then
  set -a
  . ./.env.local
  set +a
fi

docker compose --env-file .env.local up -d db wordpress nginx

until docker compose --env-file .env.local --profile tools run --rm wp-cli wp core version >/dev/null 2>&1; do
  sleep 3
done

if ! docker compose --env-file .env.local --profile tools run --rm wp-cli wp core is-installed >/dev/null 2>&1; then
  docker compose --env-file .env.local --profile tools run --rm wp-cli wp core install \
    --url=http://localhost:8096 \
    --title="The Blueprint Summit" \
    --admin_user="${WP_ADMIN_USER:-blueprint_admin}" \
    --admin_password="${WP_ADMIN_PASSWORD:-change-me}" \
    --admin_email="${WP_ADMIN_EMAIL:-info@therayreynoldsuniversity.com}" \
    --skip-email
fi

docker compose --env-file .env.local --profile tools run --rm wp-cli wp plugin install elementor --activate
docker compose --env-file .env.local --profile tools run --rm wp-cli wp theme activate blueprint-summit
docker compose --env-file .env.local --profile tools run --rm wp-cli wp eval-file /var/www/scripts/configure-elementor.php
docker compose --env-file .env.local --profile tools run --rm wp-cli wp option update permalink_structure '/%postname%/'
docker compose --env-file .env.local --profile tools run --rm wp-cli wp rewrite flush

echo "Blueprint Summit WordPress is ready at http://localhost:8096/wp-admin/"

