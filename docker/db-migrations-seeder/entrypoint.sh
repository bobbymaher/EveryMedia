#!/usr/bin/env bash


main() {
  cd /var/www/everymedia
  php artisan migrate --seed
}

main
