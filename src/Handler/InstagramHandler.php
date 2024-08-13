<?php
namespace AndreInocenti\PhpSocialScrapper\Handler;

use AndreInocenti\PhpSocialScrapper\Handler\Helper\DataCrawler;
use AndreInocenti\PhpSocialScrapper\Handler\Helper\JSON;
use AndreInocenti\PhpSocialScrapper\Handler\Traits\GetNumberFromText;
use AndreInocenti\PhpSocialScrapper\ScrapperHandler;

class InstagramHandler extends ScrapperHandler {
	use GetNumberFromText;

	private function getArticleCrawler(){
		return $this->client->waitForVisibility('article');
	}

	function actionDefault($args = []){
		$crawler = $this->getArticleCrawler();

		$articleSides = $crawler->filter('article > div > div');

		$mediaNode = $articleSides->first();
		$contentNode = $articleSides->last();

		$images = $mediaNode
		->filter('img')
		->reduce(function($node){
			return strpos($node->getAttribute('src'), 'http') === 0;
		})
		->each(function($node){
			return trim($node->getAttribute('src'));
		});

		$videos = $mediaNode->filter('video')->each(function($node){
			return $node->getAttribute('src');
		});

		$description = $crawler->filter('meta[name="description"]')->first()->attr('content');

		$html = $crawler->html();
		$dataCrawler = new DataCrawler(JSON::parseFromHTML($html));

		return [
			'text' => $dataCrawler->query(['meta', 'title']),
			'images' => $images,
			'videos' => $videos,
			'likes' => $this->getNumberFromText('likes', $description),
			'comments' => $this->getNumberFromText('comments', $description),
		];
	}

	function onAction(string $action, array $args = []){
		return $this->tryLoop('actionDefault', $args);
	}

}