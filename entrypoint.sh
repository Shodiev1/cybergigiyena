#!/bin/sh
set -e

echo "==> Preparing MariaDB runtime directories..."
mkdir -p /run/mysqld /var/lib/mysql
chown -R mysql:mysql /run/mysqld /var/lib/mysql

if [ ! -d "/var/lib/mysql/mysql" ]; then
    echo "==> Initializing MariaDB database..."
    if command -v mariadb-install-db >/dev/null 2>&1; then
        mariadb-install-db --user=mysql --datadir=/var/lib/mysql >/dev/null 2>&1 || true
    elif command -v mysql_install_db >/dev/null 2>&1; then
        mysql_install_db --user=mysql --datadir=/var/lib/mysql >/dev/null 2>&1 || true
    fi
fi

echo "==> Starting MariaDB..."
if command -v mariadbd >/dev/null 2>&1; then
    mariadbd --user=mysql --datadir=/var/lib/mysql &
elif command -v mysqld >/dev/null 2>&1; then
    mysqld --user=mysql --datadir=/var/lib/mysql &
fi

echo "==> Waiting for database to be ready..."
for i in 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15 16 17 18 19 20 21 22 23 24 25 26 27 28 29 30; do
    if mariadb-admin ping --silent >/dev/null 2>&1 || mysqladmin ping --silent >/dev/null 2>&1; then
        echo "==> Database ready."
        break
    fi
    sleep 1
done

echo "==> Setting up database users..."
SQL_INIT="CREATE DATABASE IF NOT EXISTS cybergigiyena CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; CREATE USER IF NOT EXISTS 'cg_app'@'127.0.0.1' IDENTIFIED BY 'cg_docker_pass'; GRANT ALL PRIVILEGES ON cybergigiyena.* TO 'cg_app'@'127.0.0.1'; FLUSH PRIVILEGES;"
mariadb -u root -e "$SQL_INIT" >/dev/null 2>&1 || mysql -u root -e "$SQL_INIT" >/dev/null 2>&1 || true

export DB_DSN='mysql:host=127.0.0.1;port=3306;dbname=cybergigiyena;charset=utf8mb4'
export DB_USER='cg_app'
export DB_PASSWORD='cg_docker_pass'

echo "==> Running backend migrations..."
php backend/bin/setup.php

echo "==> Starting PHP API server..."
php -S 127.0.0.1:8000 -t backend/public backend/public/index.php &

PORT="${PORT:-3000}"
echo "==> Starting Next.js frontend on port ${PORT}..."
cd /app/web
exec node node_modules/next/dist/bin/next start --hostname 0.0.0.0 --port "$PORT"
