FROM node:22-bookworm-slim AS web-builder
WORKDIR /app/web
COPY web/package*.json ./
RUN npm ci
COPY web/ ./
ENV API_ORIGIN=http://127.0.0.1:8000
RUN npm run build

FROM php:8.3-cli-bookworm
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl ca-certificates gnupg default-mysql-server default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app
COPY backend/ ./backend/
COPY --from=web-builder /app/web ./web/
RUN mkdir -p /app/.runtime /app/backend/var/sessions && chmod -R 777 /app/.runtime /app/backend/var

EXPOSE 3000
CMD ["sh", "-c", "service mariadb start && mariadb -u root -e \"CREATE DATABASE IF NOT EXISTS cybergigiyena CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; CREATE USER IF NOT EXISTS 'cg_app'@'127.0.0.1' IDENTIFIED BY 'cg_docker_pass'; GRANT ALL PRIVILEGES ON cybergigiyena.* TO 'cg_app'@'127.0.0.1'; FLUSH PRIVILEGES;\" && DB_DSN='mysql:host=127.0.0.1;port=3306;dbname=cybergigiyena;charset=utf8mb4' DB_USER='cg_app' DB_PASSWORD='cg_docker_pass' php backend/bin/setup.php && DB_DSN='mysql:host=127.0.0.1;port=3306;dbname=cybergigiyena;charset=utf8mb4' DB_USER='cg_app' DB_PASSWORD='cg_docker_pass' php -S 127.0.0.1:8000 -t backend/public backend/public/index.php & cd web && npx next start --hostname 0.0.0.0 --port ${PORT:-3000}"]
