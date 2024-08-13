<?php
namespace AndreInocenti\PhpSocialScrapper\Handler;

use AndreInocenti\PhpSocialScrapper\Handler\Traits\TwitterTrait;
use AndreInocenti\PhpSocialScrapper\ScrapperHandler;

class TwitterHandler extends ScrapperHandler {
	use TwitterTrait;

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

	function getViewCount(){
		return $this->getNumberFromText('Views', $this->getArticleEnd()) ?: $this->getNumberFromText('View', $this->getArticleEnd());
	}

	function getShareCount(){
		return $this->getNumberFromLabel('retweet', $this->getArticleCrawler())
			?: $this->getNumberFromText('Reposts', $this->getArticleEnd())
			?: $this->getNumberFromText('Repost', $this->getArticleEnd());
	}

	function getCommentCount(){
		return $this->getNumberFromText('Quotes', $this->getArticleEnd()) ?: $this->getNumberFromText('Quote', $this->getArticleEnd());
	}

	function getReplyCount(){
		return $this->getNumberFromLabel('reply', $this->getArticleCrawler())
			?: $this->getNumberFromText('Replies', $this->getArticleEnd())
			?: $this->getNumberFromText('Reply', $this->getArticleEnd());
	}

	function getLikeCount(){
		return $this->getNumberFromLabel('like', $this->getArticleCrawler())
			?: $this->getNumberFromText('Likes', $this->getArticleEnd())
			?: $this->getNumberFromText('Like', $this->getArticleEnd());
	}

	function getBookmarkCount(){
		return $this->getNumberFromLabel('bookmark', $this->getArticleCrawler())
			?: $this->getNumberFromText('Bookmarks', $this->getArticleEnd())
			?: $this->getNumberFromText('Bookmark', $this->getArticleEnd());
	}

	function getImages(){
		return $this->getArticleNode()
			->filterXPath('//img[contains(@src, "media")]')
			->each(fn ($node) => preg_replace('/([?&])name=[^&]+(&|$)/', '$1', $node->attr('src')));
	}

	function getVideos(): array{
		$videosNodes = $this->getArticleNode()
			->filterXPath('//video//source');
		if(!$videosNodes->count()) return [];

		$apiUrl = "https://twitsave.com/info?url={$this->url}";
		$crawler = $this->client->request('GET', $apiUrl);
		$url = $crawler->filter('div.origin-top-right')
			->first()
			->filter('a')
			->first()
			->getAttribute('href');
		return $url ? [$url] : [];
	}

	function actionDefault(){
		return [
			'bookmarks' => $this->getBookmarkCount(),
			'comments' => $this->getCommentCount(),
			'likes' => $this->getLikeCount(),
			'replies' => $this->getReplyCount(),
			'share' => $this->getShareCount(),
			'text' => $this->getTestIdNode('tweetText', $this->getArticleCrawler())->text(),
			'views' => $this->getViewCount(),
			'images' => $this->getImages(),
			'videos' => $this->getVideos(),
		];
	}

	function onAction(string $action, array $args = []){
		return $this->tryLoop('actionDefault', $args);
	}

}
