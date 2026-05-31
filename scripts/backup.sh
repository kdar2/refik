#!/usr/bin/env bash
# Refik günlük yedek scripti — production sunucusunda cron ile çalışır.
# Crontab örneği (her gün 03:30):
#   30 3 * * * /opt/refik/scripts/backup.sh >> /var/log/refik-backup.log 2>&1

set -euo pipefail

PROJECT_DIR="${PROJECT_DIR:-/opt/refik}"
BACKUP_DIR="${BACKUP_DIR:-/var/backups/refik}"
RETAIN_DAYS="${RETAIN_DAYS:-14}"

DATE="$(date +%Y%m%d-%H%M%S)"
mkdir -p "$BACKUP_DIR"

cd "$PROJECT_DIR"

# 1) MySQL dump
echo "[$(date)] DB dump başlıyor…"
docker compose -f docker-compose.prod.yml exec -T mysql \
    sh -c 'exec mysqldump --single-transaction --quick --routines --triggers --events \
            -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE"' \
    | gzip -9 > "$BACKUP_DIR/db-$DATE.sql.gz"

# 2) Storage tarball (uploads, logs, cache vs. — sadece uploads/audit-reports vs. iyi olur ama hepsi yedeğe gidiyor)
echo "[$(date)] Storage tarball…"
docker run --rm \
    -v "${PWD}/storage:/data:ro" \
    -v "$BACKUP_DIR:/backup" \
    alpine sh -c "tar -czf /backup/storage-$DATE.tar.gz -C /data ." || true

# 3) S3'e yükle (opsiyonel — AWS_BUCKET env tanımlıysa)
if [ -n "${AWS_BUCKET:-}" ]; then
    echo "[$(date)] S3'e yükleniyor (s3://$AWS_BUCKET/refik/)…"
    aws s3 cp "$BACKUP_DIR/db-$DATE.sql.gz"      "s3://$AWS_BUCKET/refik/db/db-$DATE.sql.gz"
    aws s3 cp "$BACKUP_DIR/storage-$DATE.tar.gz" "s3://$AWS_BUCKET/refik/storage/storage-$DATE.tar.gz"
fi

# 4) Eski yerel yedekleri temizle
find "$BACKUP_DIR" -type f -name "*.gz" -mtime +"$RETAIN_DAYS" -delete

echo "[$(date)] Yedek tamam: $BACKUP_DIR"
