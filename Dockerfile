FROM node:22-bookworm-slim AS web-builder
WORKDIR /app/web
COPY web/package*.json ./
RUN npm ci
COPY web/ ./
ENV API_ORIGIN=http://127.0.0.1:8000
RUN npm run build

FROM php:8.3-cli-bookworm
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl ca-certificates gnupg mariadb-server mariadb-client \
    && docker-php-ext-install pdo pdo_mysql \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app
COPY backend/ ./backend/
COPY --from=web-builder /app/web ./web/
COPY entrypoint.sh ./entrypoint.sh
RUN chmod +x ./entrypoint.sh \
    && mkdir -p /app/.runtime /app/backend/var/sessions \
    && chmod -R 777 /app/.runtime /app/backend/var

EXPOSE 3000
ENTRYPOINT ["/app/entrypoint.sh"]
