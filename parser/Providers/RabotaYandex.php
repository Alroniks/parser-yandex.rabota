<?php namespace Parser\Providers;

use GuzzleHttp\Client;
use Parser\BaseProvider;
use SleepingOwl\Apist\Apist;

class RabotaYandex extends BaseProvider
{
    private $items = [];

    public function fetch()
    {
        $response = $this->get('', [
            'count' => Apist::filter('.b-serp-control__title')->text()->trim()
        ]);

        $count = intval($this->clear($response['count']));
//        $count = 1;
        $pages = ceil($count / 10);

        for ($i = 1; $i <= $pages; $i++) {
            $this->getPage($i);
        }

        return $this->items;
    }

    private function getPage($number)
    {
        $this->setBaseUrl($this->getBaseUrl() . "&page_num=$number");
        $this->setGuzzle(new Client(['base_url' => $this->baseUrl]));

        $response = $this->get('',
            Apist::filter('.b-serp-item')->each([
                'salary' => $this->clear(Apist::filter('.b-salary__value')->text()->trim()),
                'title' => Apist::filter('.b-serp-item__title a')->text()->trim(),
                'url' => Apist::filter('.b-serp-item__title a')->attr('href')
            ])
        );

        $this->items = array_merge($this->items, $response);

        $this->setBaseUrl(str_replace("&page_num=$number", '', $this->getBaseUrl()));
    }

    private function clear($string)
    {
        return is_string($string)
            ? trim(str_replace('&nbsp;', '', htmlentities($string)))
            : $string;
    }

}
