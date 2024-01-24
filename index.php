<?php

include_once "./vendor/autoload.php";
include_once "./helpers.php";



const URL = "https://www.autozap.ru";

function getPartsByArticle($search = '')
{

  $crawler = getCrawler(URL, 'POST', '/goods', [
    'headers' => ['Accept-Encoding' => 'gzip, deflate, br'],
    'form_params' => ['code' => $search]
  ]);

  $link = $crawler->filter('#goodLnk1');

  if ($link->count() > 0) {
    $crawler = getCrawler(URL, 'GET', strtolower($link->attr('href')), [
      'headers' => ['Accept-Encoding' => 'gzip, deflate, br']
    ]);
  }
  return getListOfParts($crawler);
}

dd(getPartsByArticle('90915-YZZE2'));
