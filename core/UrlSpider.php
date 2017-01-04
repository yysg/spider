<?php

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

class UrlSpider
{
    public $newUrlPool = array();
    public $oldUrlPool = array();
    public $htmlData = array();

    public function createRequest($initUrl)
    {
        $client = new Client();
//        $jar = new \GuzzleHttp\Cookie\CookieJar();
//        $jar->setCookie('pauth=UGFja2FnaXN0XFdlYkJ1bmRsZVxFbnRpdHlcVXNlcjplWGx6Wnc9PToxNTEyNzA0MzM4OjdiZmY1NzY0MjJhMzY5ODhiNDg2OGRhZWU3MmE0ZDRhMDYxMGExNzAxMDhiZjNkMWUzZGQyNWQxYzQ3MTU5M2I%3D; packagist=9i7b6kgs8hnh1udgl2tdprlav5; __utmt=1; __utma=240009474.1110899501.1481618304.1481618304.1481618304.1; __utmb=240009474.1.10.1481618304; __utmc=240009474; __utmz=240009474.1481618304.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none)');
        $guzzleClient = new GuzzleClient(array(
            'timeout' => 60,
            'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.110 Safari/537.36',
//    'cookie' => $jar
        ));
        $client->setClient($guzzleClient);
        $this->newUrlPool[] = $initUrl;
        if (count($this->newUrlPool) != 0) {
            self::call($client, array_shift($this->newUrlPool));
        } else {
            var_dump('========running end========');
            die();
        }
//        $crawler = $client->click($crawler->selectLink('Sign in')->link());
//
//        $form = $crawler->selectButton('Log in')->form();
//        $crawler = $client->submit($form, array('_username' => 'yysg', '_password' => 'pa123123'));
// get all href

        for ($i = 0; $i < 2; $i++) {
            self::call($client, array_shift($this->newUrlPool));
        }
//        var_dump($this->newUrlPool);
//        var_dump($this->oldUrlPool);
    }

    public function call($client, $initUrl)
    {
        $crawler = $client->request('GET', $initUrl);
        $this->oldUrlPool[] = $initUrl;
        // add url to pool
        $crawler->filter('li > a')->each(function ($node) {
            $uri = $node->attr('href');
            $flg = preg_match('/^\/packages/', $uri);
            if ($flg === 1 && !in_array($uri, $this->oldUrlPool)) {
                $this->newUrlPool[] = $uri;
            }

        });
        $crawler->filter('div.facts >p ')->each(function ($node) {
            $html = str_replace(array("", " ", "\t", "\n", "\r", " "), array("", "", "", "", "", "", ""), $node->text());
            if (preg_match('/\d+/', $html, $value) != null) {
                preg_match('/\D+/', $html, $key);
                $this->htmlData[array_shift($key)] = array_shift($value);

            }
        });
        var_dump($this->htmlData);
        die();
    }

}