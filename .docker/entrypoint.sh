#!/bin/bash

set -o pipefail

# hook to execute CMD
if [[ $# -gt 0 ]]; then
    bash -c "$*"
fi

php-fpm -t || exit 1
php-fpm --daemonize --pid /run/php-fpm.pid

sed -i 's/^user .*;$/user root;/' /etc/nginx/nginx.conf
nginx -t || exit 1
service nginx start

echo "NOTICE: Ready for connections at 0.0.0.0:8080"

tail -f /var/log/nginx/error.log | sed -u "s/^/ERROR: /" &
tail -f /var/log/nginx/access.log | sed -u "s/^/ACCESS: /"

