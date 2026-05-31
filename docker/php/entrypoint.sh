#!/bin/sh
# public/ volume'u image'dan gelen dosyalarla güncelle
# (her zaman image kazanır — böylece yeni build'ler otomatik yayılır)
if [ -d /var/www/public-src ]; then
    cp -a /var/www/public-src/. /var/www/public/
fi

# storage symlink'i yoksa oluştur
if [ ! -L /var/www/public/storage ]; then
    ln -s /var/www/storage/app/public /var/www/public/storage
fi

exec "$@"
