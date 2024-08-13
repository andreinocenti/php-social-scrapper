<?php

use AndreInocenti\PhpSocialScrapper\SocialScrapper;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertTrue;

test('Scrap Instagram image post data', function(){
    $url = 'https://www.instagram.com/p/C-kaCeCpTYh/?hl=pt&img_index=1';
    $scrapper = new SocialScrapper();
    $data = $scrapper->fetch($url);
    assertTrue($data['url'] == $url);
    assertTrue($data['action'] == 'p');
    assertTrue($data['controller'] == 'InstagramHandler');
    assertTrue($data['data']['comments'] > 1);
    assertTrue($data['data']['likes'] > 1);
    assertNotEmpty($data['data']['text']);
    assertCount(2, $data['data']['images']);
    assertCount(0, $data['data']['videos']);
});

// test('Scrap Instagram Reel data', function () {
//     $url = 'https://www.instagram.com/reel/C-TVkt6AWRj/?hl=pt';
//     $scrapper = new SocialScrapper();
//     $data = $scrapper->fetch($url);
//     assertTrue($data['url'] == $url);
//     assertTrue($data['action'] == 'reel');
//     assertTrue($data['controller'] == 'InstagramHandler');
//     assertTrue($data['data']['comments'] > 1);
//     assertTrue($data['data']['likes'] > 1);
//     assertNotEmpty($data['data']['text']);
//     assertCount(1, $data['data']['videos']);
// });


// test('Scrap Instagram video post data', function () {
//     $url = 'https://www.instagram.com/p/C98RY26ArNN/?hl=pt';
//     $scrapper = new SocialScrapper();
//     $data = $scrapper->fetch($url);
//     dd($data);
//     assertTrue($data['url'] == $url);
//     assertTrue($data['action'] == 'p');
//     assertTrue($data['controller'] == 'InstagramHandler');
//     assertTrue($data['data']['comments'] > 1);
//     assertTrue($data['data']['likes'] > 1);
//     assertNotEmpty($data['data']['text']);
//     assertCount(1, $data['data']['videos']);
// });