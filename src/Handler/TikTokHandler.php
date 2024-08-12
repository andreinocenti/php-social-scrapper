<?php
namespace AndreInocenti\PhpSocialScrapper\Handler;

use AndreInocenti\PhpSocialScrapper\Handler\Helper\DataCrawler as HelperDataCrawler;
use AndreInocenti\PhpSocialScrapper\Handler\Helper\JSON;
use AndreInocenti\PhpSocialScrapper\ScrapperHandler;

class TikTokHandler extends ScrapperHandler {

	function onAction(string $action, array $args = []){
		$crawler = $this->client->waitFor('#__UNIVERSAL_DATA_FOR_REHYDRATION__');
		$html = $crawler->html();
		$dataCrawler = new HelperDataCrawler(JSON::parseFromHTML($html));
		return [
			'comments' => $dataCrawler->query(['itemStruct', 'stats', 'commentCount']),
			'text' => $dataCrawler->query(['itemStruct', 'desc']),
			'likes' => $dataCrawler->query(['itemStruct', 'stats', 'diggCount']),
			'bookmark' => $dataCrawler->query(['itemStruct', 'textExtra', 2, 'end']),
			'share' => $dataCrawler->query(['itemStruct', 'stats', 'shareCount']),
			'video' => $dataCrawler->query(['itemStruct', 'video', 'playAddr']),
		];
	}


}