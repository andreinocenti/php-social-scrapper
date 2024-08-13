<?php

namespace AndreInocenti\PhpSocialScrapper\Handler;

use AndreInocenti\PhpSocialScrapper\Handler\Traits\GetNumberFromText;
use AndreInocenti\PhpSocialScrapper\Handler\Traits\TwitterTrait;
use AndreInocenti\PhpSocialScrapper\ScrapperHandler;
use Symfony\Component\DomCrawler\Crawler;

class TwitterProfileHandler extends ScrapperHandler
{
    use TwitterTrait, GetNumberFromText;

    private $timeline;
    private $mainCrawler;
    private $articleEnd;


    function getMainCrawler()
    {
        return $this->mainCrawler ?: ($this->mainCrawler = $this->client->waitForVisibility('main'));
    }

    function getTimelineNode()
    {
        return $this->timeline ?: ($this->timeline = $this->client->waitForVisibility('//div[contains(@aria-label, "Timeline:")]'));
    }


    function getPostsUrl()
    {
        return $this->getTimelineNode()
            ->filter('[data-testid="tweet"]')
            ->each(function (Crawler $node) {
                return $node->filter('div[data-testid="User-Name"] a[dir="ltr"][role="link"]')
                    ->first()
                    ->attr('href');
            });
    }

    function getFollowersCount()
    {
        return $this->getNumberFromText('Followers', $this->getMainCrawler()->text())
            ?: $this->getNumberFromText('Follower', $this->getMainCrawler()->text());
    }

    function getSubscriptionCount()
    {
        return $this->getNumberFromText('Subscriptions', $this->getMainCrawler()->text())
            ?: $this->getNumberFromText('Subscription', $this->getMainCrawler()->text());
    }

    function actionDefault()
    {
        return [
            'posts' => $this->getPostsUrl(),
            'following' => $this->getNumberFromText('Following', $this->getMainCrawler()->text()),
            'followers' => $this->getFollowersCount(),
            'subscription' => $this->getSubscriptionCount(),
        ];
    }

    function onAction(string $action, array $args = [])
    {
        return $this->tryLoop('actionDefault', $args);
    }
}
