<?php
namespace AndreInocenti\PhpSocialScrapper;
use Symfony\Component\Panther\Client;

/*
Classe privada pra ser usada apenas nesta classe
*/

class ShutdownListerner
{

    private static $list = [];

    public static function createClient()
    {
        $client = Client::createChromeClient();
        self::$list[] = $client;
        return $client;
    }

    public static function close($client)
    {
        try {
            $client->close();
            $client->quit();
        } catch (\Exception $e) {
        }
    }

    public static function onShutdown()
    {
        foreach (self::$list as $client) {
            self::close($client);
        }
    }
}

register_shutdown_function([ShutdownListerner::class, "onShutdown"]);

class ScrapperHandler
{

    public $client;
    public $url;
    public $crawler;

    public function __construct(string $url)
    {
        $client = ShutdownListerner::createClient();
        ShutdownListerner::close($client);
        $client = ShutdownListerner::createClient();
        $this->crawler = $client->request('GET', $url);
        $this->client = $client;
        $this->url = $url;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getCrawler()
    {
        return $this->getClient()->getCrawler();
    }

    public function getURL()
    {
        return $this->url;
    }

    public function getHTML()
    {
        return $this->getCrawler()->html();
    }


    function onAction(string $action, array $args = [])
    {
        return [];
    }

    function onStart() {}

    function onEnd($result)
    {
        return $result;
    }


    function onError(\Exception $error)
    {
        return $error->getMessage();
    }

    function tryLoop($action, $args, $tryCount = 5)
    {
        try {
            return call_user_func([$this, $action], $args, $tryCount);
        } catch (\Exception $e) {
            if ($tryCount > 0) {
                usleep($tryCount * 500000);
                return $this->tryLoop($action, $args, $tryCount - 1);
            }
            throw $e;
        }
    }
}
