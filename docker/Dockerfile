FROM php:8.3-fpm-alpine

# CHROMIU
ENV PANTHER_NO_SANDBOX 1
# Not mandatory, but recommended
ENV PANTHER_CHROME_ARGUMENTS='--disable-dev-shm-usage'
RUN apk add chromium chromium-chromedriver
