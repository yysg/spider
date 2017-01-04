<?php

require __DIR__.'../../vendor/autoload.php';
require __DIR__.'/UrlSpider.php';
$urlSpider = new UrlSpider();
$urlSpider->createRequest('https://packagist.org/packages/fabpot/goutte');


