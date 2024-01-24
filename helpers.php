<?php
include_once "./vendor/autoload.php";

use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;

function dd($render): void
{
  echo '<pre>';
  var_dump($render);
  echo '</pre>';
}


function getCrawler(string $url, string $method, string $uri, array $options = null): Crawler
{
  $response = getClient(['base_uri' => $url,])->request($method, $uri, $options);
  return  new Crawler($response->getBody()->getContents());
}

function getClient(array $config)
{
  return new Client($config);
}


function getListOfParts(Crawler $crawler): array
{
  $result = [];
  $crawler->filter('#tabGoods tr')->each(function ($node, $i) use (&$result) {

    $name = $node->filter('td.name a');
    $producer = $node->filter('td.producer');
    $price = $node->filter('[id^=sp]');

    $article = $node->filter('td.code');

    $count  = $node->filter('div.storehouse-quantity span');
    $time = $node->filter('td.article');
    $id = $node->filter('div.storehouse-quantity input[id^="g"]');

    if ($producer->count() > 0 && $producer->text() !== null) {
      $producer =  $producer->innerText();
      $article = $article->innerText();

      if ($name->count() === 0) {
        $result[$i]['name'] = $result[$i - 1]['name'];
      } else {
        $result[$i]['name'] = $name->innerText();
      }


      $producer ? $result[$i]['brand'] = $producer : $result[$i]['brand'] = $result[$i - 1]['brand'];
      $article ? $result[$i]['article'] = $article : $result[$i]['article'] = $result[$i - 1]['article'];
      $result[$i]['price'] = $price->innerText();
      $result[$i]['count'] = $count->innerText();
      $result[$i]['time'] = (int)$time->innerText();
      $result[$i]['id'] = $id->attr('value');
    }
  });

  return $result;
}
