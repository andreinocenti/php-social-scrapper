<?php
namespace AndreInocenti\PhpSocialScrapper;

use AndreInocenti\PhpSocialScrapper\Handler\FacebookHandler;
use AndreInocenti\PhpSocialScrapper\Handler\InstagramHandler;
use AndreInocenti\PhpSocialScrapper\Handler\LinkedinHandler;
use AndreInocenti\PhpSocialScrapper\Handler\TikTokHandler;
use AndreInocenti\PhpSocialScrapper\Handler\TwitterHandler;
use AndreInocenti\PhpSocialScrapper\Router\Router;

return new Router([
	'facebook.com/([^/]+)/posts/' => [FacebookHandler::class, 'posts'],
	'facebook.com/([^/]+)/videos/' => [FacebookHandler::class, 'videos'],
	'facebook.com/watch/' => [FacebookHandler::class, 'watch'],
	'instagram.com/p/' => [InstagramHandler::class, 'p'],
	'instagram.com/([^/]+)/p/' => [InstagramHandler::class, 'p'],
	'instagram.com/reel/' => [InstagramHandler::class, 'reel'],
	'linkedin.com/feed/update/' => [LinkedinHandler::class, 'feedUpdate'],
	'linkedin.com/posts/' => [LinkedinHandler::class, 'posts'],
	'tiktok.com/@([^/]+)/video/(\d+)' => [TikTokHandler::class, 'video'],
	'twitter.com/([^/]+)/status/(\d+)' => [TwitterHandler::class, 'status'],
	'x.com/([^/]+)/status/(\d+)' => [TwitterHandler::class, 'status'],
]);