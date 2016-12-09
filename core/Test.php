<?php

require __DIR__.'../../vendor/autoload.php';
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

$goutteClient = new Client();
$guzzleClient = new GuzzleClient(array(
    'timeout' => 60,
));
$goutteClient->setClient($guzzleClient);

$crawler = $goutteClient->request('GET', 'https://twig.readthedocs.io/');

$crawler = $goutteClient->click($crawler->selectLink('Introduction')->link());
$crawler->filter('h2')->each(function ($node) {
    print $node->text()."\n";
});
