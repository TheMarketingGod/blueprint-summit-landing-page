#!/bin/bash
set -euxo pipefail
export DEBIAN_FRONTEND=noninteractive

apt-get update
apt-get install -y git docker.io docker-compose ca-certificates curl
systemctl enable --now docker

mkdir -p /opt/blueprint-summit
if [ ! -d /opt/blueprint-summit/.git ]; then
  git clone https://github.com/TheMarketingGod/blueprint-summit-landing-page.git /opt/blueprint-summit
else
  git -C /opt/blueprint-summit pull --ff-only
fi

cd /opt/blueprint-summit
cat > .env.gcp <<'ENV'
WP_DB_PASSWORD=__WP_DB_PASSWORD__
WP_DB_ROOT_PASSWORD=__WP_DB_ROOT_PASSWORD__
WP_ADMIN_USER=blueprint_admin
WP_ADMIN_PASSWORD=__WP_ADMIN_PASSWORD__
WP_ADMIN_EMAIL=info@therayreynoldsuniversity.com
ENV

docker-compose -f docker-compose.gcp.yml --env-file .env.gcp up -d db wordpress nginx

until docker-compose -f docker-compose.gcp.yml --env-file .env.gcp --profile tools run --rm wp-cli wp core version >/dev/null 2>&1; do
  sleep 5
done

WPCLI="docker-compose -f docker-compose.gcp.yml --env-file .env.gcp --profile tools run --rm wp-cli wp"
if ! $WPCLI core is-installed >/dev/null 2>&1; then
  $WPCLI core install \
    --url=https://summit.therayreynoldsuniversity.com \
    --title='The Blueprint Summit' \
    --admin_user=blueprint_admin \
    --admin_password=__WP_ADMIN_PASSWORD__ \
    --admin_email=info@therayreynoldsuniversity.com \
    --skip-email
fi

$WPCLI plugin install elementor --activate
$WPCLI theme activate blueprint-summit
$WPCLI eval-file /var/www/scripts/configure-elementor.php
$WPCLI option update home 'https://summit.therayreynoldsuniversity.com'
$WPCLI option update siteurl 'https://summit.therayreynoldsuniversity.com'
$WPCLI option update permalink_structure '/%postname%/'
$WPCLI rewrite flush
