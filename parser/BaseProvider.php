<?php namespace Parser;

use Parser\Interfaces\ProviderInterface;
use SleepingOwl\Apist\Apist;

class BaseProvider extends Apist implements ProviderInterface
{
    protected $baseUrl;

    public function __construct($url) {
        $this->baseUrl = $url;

        parent::__construct();
    }

    public function fetch()
    {
    }

}
