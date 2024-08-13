<?php

use AndreInocenti\PhpSocialScrapper\SocialScrapper;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertTrue;

test('Scrapper twitter status with photo and video', function () {
    $url = 'https://x.com/SpaceX/status/1822963658621943823';
    $scrapper = new SocialScrapper();
    $data = $scrapper->request($url);
    assertTrue($data['url'] == $url);
    assertTrue($data['action'] == 'status');
    assertTrue($data['controller'] == 'TwitterHandler');
    assertTrue($data['data']['bookmarks'] > 1);
    assertTrue($data['data']['comments'] > 1);
    assertTrue($data['data']['likes'] > 1);
    assertTrue($data['data']['share'] > 1);
    assertTrue($data['data']['views'] > 1);
    assertNotEmpty($data['data']['text']);
    assertCount(3, $data['data']['images']);
    assertCount(1, $data['data']['videos']);
});

test('Scrapper twitter status that has only text', function () {
    $url = 'https://x.com/elonmuskphone/status/1822964141822197826';
    $scrapper = new SocialScrapper();
    $data = $scrapper->request($url);
    assertTrue($data['url'] == $url);
    assertTrue($data['action'] == 'status');
    assertTrue($data['controller'] == 'TwitterHandler');
    assertTrue($data['data']['bookmarks'] > 1);
    assertTrue($data['data']['likes'] > 1);
    assertTrue($data['data']['share'] > 1);
    assertTrue($data['data']['views'] > 1);
    assertNotEmpty($data['data']['text']);
    assertCount(0, $data['data']['images']);
    assertCount(0, $data['data']['videos']);
});

test('Scrapper twitter status that has only one photo', function () {
    $url = 'https://x.com/SpaceX/status/1822804891397661028';
    $scrapper = new SocialScrapper();
    $data = $scrapper->request($url);
    assertTrue($data['url'] == $url);
    assertTrue($data['action'] == 'status');
    assertTrue($data['controller'] == 'TwitterHandler');
    assertTrue($data['data']['bookmarks'] > 1);
    assertTrue($data['data']['comments'] > 1);
    assertTrue($data['data']['likes'] > 1);
    assertTrue($data['data']['share'] > 1);
    assertTrue($data['data']['views'] > 1);
    assertNotEmpty($data['data']['text']);
    assertCount(1, $data['data']['images']);
    assertCount(0, $data['data']['videos']);
});


test('Scrap twitter profile status list', function () {
    $url = 'https://x.com/SpaceX';
    $scrapper = new SocialScrapper();
    $data = $scrapper->request($url);
    assertTrue($data['url'] == $url);
    assertTrue($data['action'] == 'profile');
    assertTrue($data['controller'] == 'TwitterProfileHandler');
    assertTrue($data['data']['posts'] > 4);
    assertTrue($data['data']['following'] > 1);
    assertTrue($data['data']['followers'] > 1);
    assertTrue($data['data']['subscription'] >= 1);
});