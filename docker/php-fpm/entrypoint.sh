#!/usr/bin/env bash

prepare_laravel_storage_dir() {
  install -d -v -g nobody -m 755 -o nobody \
    /var/www/everymedia/storage/framework \
    /var/www/everymedia/storage/framework/cache \
    /var/www/everymedia/storage/framework/views \
    /var/www/everymedia/storage/framework/sessions \
    /var/www/everymedia/storage/logs \
    /var/www/everymedia/storage/uploads
}

main() {
  local -a cmd=(
    "/usr/sbin/php-fpm"
    "-F"
  )

  prepare_laravel_storage_dir

  exec "${cmd[@]}"
}

main
