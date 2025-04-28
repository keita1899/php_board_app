FROM php:8.2-apache

# 必要なPHP拡張をインストール
RUN docker-php-ext-install pdo pdo_mysql

# Apacheのmod_rewriteを有効化
RUN a2enmod rewrite

# (必要に応じて) 設定ファイルをコピー
# COPY ./apache.conf /etc/apache2/sites-available/000-default.conf