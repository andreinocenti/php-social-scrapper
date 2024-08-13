# PHP Social Scrapper

It is a library to scrap social media data from a given URL.

## Installation

```bash
composer require andreinocenti/php-social-scrapper
```


## Supported Social Medias

- Facebook
- Instagram
- Twitter
- TikTok
- LinkedIn


## Usage
```php

$scrapper = new PhpSocialScrapper();
$scrappedData = $scrapper->fetch('https://www.instagram.com/p/CT9J9Z9r1Zz/');

```


## Important information

- For twitter videos we use Twitsave API. It is a third-party service so it can be offline or not working properly.




## For tests
```bash
## up the container
docker compose up -d
## exec the test into the container
docker exec php-social-scrapper ./vendor/bin/pest

## to stop the container
docker compose down -v
```

