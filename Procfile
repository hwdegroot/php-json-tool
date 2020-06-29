# https://devcenter.heroku.com/articles/release-phase#specifying-release-phase-tasks
# Tasks that should execute during release
web: .heroku/clear-php-cache-on-heroku.sh && heroku-php-nginx -C nginx_app.conf public
worker: .heroku/clear-php-cache-on-heroku.sh && php artisan queue:work --sleep=3 --tries=3 -vvv
