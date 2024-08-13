<?php
namespace AndreInocenti\PhpSocialScrapper\Handler\Traits;

use Symfony\Component\DomCrawler\Crawler;

trait TwitterTrait
{
    function getTestIdNode($id, Crawler $crawler)
    {
        return $crawler->filter('[data-testid="' . $id . '"]')->first();
    }

    function getNumberFromLabel($id, $node)
    {
        $label = $this->getTestIdNode($id, $node)->attr('aria-label');
        $label = preg_replace('#\D#', '', $label);
        return $label ? (int)$label : null;
    }
}