<?php
namespace AndreInocenti\PhpSocialScrapper\Handler;

use AndreInocenti\PhpSocialScrapper\Handler\Helper\DataCrawler;
use AndreInocenti\PhpSocialScrapper\Handler\Helper\JSON;
use AndreInocenti\PhpSocialScrapper\ScrapperHandler;

class FacebookHandler extends ScrapperHandler {

	public function actionPosts($args){
		$crawler = $this->client->waitForVisibility('div[data-visualcompletion="ignore-dynamic"]');
		$html = $crawler->html();
		$dataCrawler = new DataCrawler(JSON::parseFromHTML($html));

		return [
			'images' => array_values($dataCrawler->queryAll(['attachment', 'media','image','uri'])),
			'text' => $dataCrawler->query(['message','text']),
			'shares' => (int) $dataCrawler->query(['share_count','count']),
			'likes' => (int) $dataCrawler->query(['reaction_count', 'count']),
			'comments' => (int) $dataCrawler->query(['total_comment_count'])
		];
	}

	public function actionVideos($args){
		$crawler = $this
		->client
		->waitForVisibility('video');

		$html = $crawler->html();

		$dataCrawler = new DataCrawler(JSON::parseFromHTML($html));

		return [
			'video' => $crawler->filter('video')->first()->attr('src'),
			'views' => (int) $dataCrawler->query(['video_view_count_renderer','video_post_view_count']),
			'likes' => (int) $dataCrawler->query(['feedback','reaction_count','count']),
			'comments' => (int) $dataCrawler->query(['feedback','total_comment_count']),
			'text' => $dataCrawler->query(['data','creation_story','message','text']),
		];
	}

	function onAction(string $action, array $args = []){
		switch($action){
			case 'posts': return $this->tryLoop('actionPosts', $args);
			case 'videos': return $this->tryLoop('actionVideos', $args);
			case 'watch': return $this->tryLoop('actionVideos', $args);
		}
	}

}