<?php
namespace AndreInocenti\PhpSocialScrapper\Handler;

use AndreInocenti\PhpSocialScrapper\Handler\Helper\DataCrawler;
use AndreInocenti\PhpSocialScrapper\Handler\Helper\JSON;
use AndreInocenti\PhpSocialScrapper\ScrapperHandler;

class LinkedinHandler extends ScrapperHandler {

	function getTestIdNode($id){
		return $this->getCrawler()->filter('[data-test-id="'.$id.'"]')->first();
	}

	function getCommentCount(){
		$value = $this->getTestIdNode('social-actions__comments')->attr('data-num-comments');
		return $value ? (int)$value : null;
	}

	function getLikeCount(){
		$value = trim($this->getTestIdNode('social-actions__reaction-count')->text());
		return $value ? (int)$value : null;
	}

	function getImages(){
		return $this
		->getTestIdNode('article-content')
		->filter('img')
		->each(function($node){
			return $node->attr('src');
		});
	}

	function getVideos(){
		return $this
		->getCrawler()
		->filter('article')
		->first()
		->filter('video')
		->each(function($node){
			return $node->attr('src');
		});
	}

	function actionPost($args = []){
		$crawler = $this->client->waitFor('script[type="application/ld+json"]');
		$html = $crawler->html();
		$dataCrawler = new DataCrawler(JSON::parseFromHTML($html));

		return [
			'text' => $dataCrawler->query(['articleBody']),
			'likes' => $this->getLikeCount(),
			'comments' => $this->getCommentCount(),
			'images' => $this->getImages(),
			'videos' => $this->getVideos(),
		];
	}

	function onAction(string $action, array $args = []){
		switch($action) {
			case 'feedUpdate': return $this->tryLoop('actionPost', $args);
			case 'posts': return $this->tryLoop('actionPost', $args);
		}
	}

}