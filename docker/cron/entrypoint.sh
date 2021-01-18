#!/bin/bash

# Run scheduler
while [ true ]
do
  php /var/www/everymedia/artisan schedule:run
  sleep 60
done
