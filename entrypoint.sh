#!/bin/sh
set -e

echo "==> Preparing MariaDB runtime directories..."
mkdir -p /run/mysqld /var/lib/mysql
chown -R mysql:mysql /run/mysqld /var/lib/mysql

if [ ! -d "/var/lib/mysql/mysql" ]; then
    echo "==> Initializing MariaDB data directory..."
    mariadb-install-db --user=mysql --datadir=/var/lib/mysql >/dev/null 2>&1 || mysql_install_db --user=mysql --datadir=/var/lib/mysql >/dev/null 2>&1 || true
fi

echo "==> Starting MariaDB daemon..."
mysqld --user=mysql --datadir=/var/lib/mysql &

echo "==> Waiting for MariaDB to accept connections..."
for i in $(seq 1 30); do
    if mariadb-admin ping --silent >/dev/null 2>&1 || mysqladmin ping --silent >/dev/null 2>&1; then
        echo "==> MariaDB is ready."
        break
    fi
    sleep 1
done

echo "==> Setting up database and user..."
mariadb -u root <<EOF || mysql -u root <<EOF || true
CREATE DATABASE IF NOT EXISTS cybergigiyena CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'cg_app'@'127.0.0.1' IDENTIFIED BY 'cg_docker_pass';
GRANT ALL PRIVILEGES ON cybergigiyena.* TO 'cg_app'@'127.0.0.1';
FLUSH PRIVILEGES;
EOF

export DB_DSN='mysql:host=127.0.0.1;port=3306;dbname=cybergigiyena;charset=utf8mb4'
export DB_USER='cg_app'
export DB_PASSWORD='cg_docker_pass'

echo "==> Running backend setup (migrations, seeds)..."
php backend/bin/setup.php || echo "Warning: setup.php finished with status $?"

echo "==> Starting PHP API on 127.0.0.1:8000..."
php -S 127.0.0.1:8000 -t backend/public backend/public/index.php &

PORT="${PORT:-3000}"
echo "==> Starting Next.js on 0.0.0.0:${PORT}..."
cd /app/web
exec node node_modules/next/dist/bin/next start --hostname 0.0.0.0 --port "$PORT"
