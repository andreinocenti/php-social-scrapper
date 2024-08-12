<?php
namespace AndreInocenti\PhpSocialScrapper\Handler;

use AndreInocenti\PhpSocialScrapper\ScrapperHandler;

class TwitterHandler extends ScrapperHandler {

	private $articleNode;
	private $articleCrawler;
	private $articleEnd;


	function getArticleCrawler(){
		return $this->articleCrawler ?: ($this->articleCrawler = $this->client->waitForVisibility('article'));
	}

	function getArticleNode(){
		return $this->articleNode ?: ($this->articleNode = $this->getArticleCrawler()->filter('article')->first());
	}

	function getArticleEnd(){
		if(!$this->articleEnd){
			$articleText = $this->getArticleNode()->text();
			$this->articleEnd = substr($articleText, -100);
		}
		return $this->articleEnd;
	}

	function getTestIdNode($id){
		return $this->getArticleCrawler()->filter('[data-testid="'.$id.'"]')->first();
	}

	function getNumberFromLabel($id){
		$label = $this->getTestIdNode($id)->attr('aria-label');
		$label = preg_replace('#\D#', '', $label);
		return $label ? (int)$label : null;
	}

	function getNumberFromText($sufix){
		if(preg_match('#([\d,]+)\s+'.$sufix.'#', $this->getArticleEnd(), $matches)){
			return (int) preg_replace('#,#', '', $matches[1]);
		}
		if(preg_match('#([\d\.]+)([MK])\s+'.$sufix.'#', $this->getArticleEnd(), $matches)){
			$value = (int) preg_replace('#,#', '', $matches[1]);
			switch($matches[2]){
				case 'M': return $value * 1000000;
				case 'K': return $value * 1000;
			}
			return $value;
		}
		return null;
	}

	function getViewCount(){
		return $this->getNumberFromText('Views') ?: $this->getNumberFromText('View');
	}

	function getShareCount(){
		return $this->getNumberFromLabel('retweet') ?: $this->getNumberFromText('Reposts') ?: $this->getNumberFromText('Repost');
	}

	function getCommentCount(){
		return $this->getNumberFromText('Quotes') ?: $this->getNumberFromText('Quote');
	}

	function getReplyCount(){
		return $this->getNumberFromLabel('reply') ?: $this->getNumberFromText('Replies') ?: $this->getNumberFromText('Reply');
	}

	function getLikeCount(){
		return $this->getNumberFromLabel('like') ?: $this->getNumberFromText('Likes') ?: $this->getNumberFromText('Like');
	}

	function getBookmarkCount(){
		return $this->getNumberFromLabel('bookmark') ?: $this->getNumberFromText('Bookmarks') ?: $this->getNumberFromText('Bookmark');
	}

	function getImages(){
		return $this
		->getTestIdNode('card.layoutLarge.media')
		->filter('img')
		->each(function($node){
			return $node->attr('src');
		});
	}

	function getVideos(){
		return $this
		->getTestIdNode('videoComponent')
		->filter('video')
		->each(function($node){
			return $node->attr('src');
		});
	}

	function actionDefault(){
		return [
			'bookmarks' => $this->getBookmarkCount(),
			'comments' => $this->getCommentCount(),
			'likes' => $this->getLikeCount(),
			'replies' => $this->getReplyCount(),
			'share' => $this->getShareCount(),
			'text' => $this->getTestIdNode('tweetText')->text(),
			'views' => $this->getViewCount(),
			'images' => $this->getImages(),
			'videos' => $this->getVideos(),
		];
	}

	function onAction(string $action, array $args = []){
		return $this->tryLoop('actionDefault', $args);
	}

}
