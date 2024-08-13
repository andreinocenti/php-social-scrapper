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
use AndreInocenti\PhpSocialScrapper\SocialScrapper;

$scrapper = new SocialScrapper();
$scrappedData = $scrapper->fetch('https://www.instagram.com/p/CT9J9Z9r1Zz/');

```


## Important information

- For twitter videos we use Twitsave API. It is a third-party service so it can be offline or not working properly.
- Videos for TikTok, Instagram, Facebook, and LinkedIn are not supported yet, the package can scrap the text and some engagement data. But the video return as a blob.



## For tests
```bash
## up the container
docker compose up -d
## exec the test into the container
docker exec php-social-scrapper ./vendor/bin/pest

## to stop the container
docker compose down -v
```

