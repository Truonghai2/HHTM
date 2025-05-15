#!/bin/sh
export PORT=${PORT:-8080}
/usr/bin/supervisord -c /etc/supervisor/supervisord.conf