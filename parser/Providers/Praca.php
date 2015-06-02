<?php namespace Parser\Providers;

use Parser\BaseProvider;
use SleepingOwl\Apist\Apist;

class Praca extends BaseProvider
{

    public function fetch()
    {

        $response = $this->get('', [
            'title' => Apist::filter('.vacancy-view__vacancy-info .vacancy-view__title')->text()->trim()
        ]);

        return $response;

    }

}
