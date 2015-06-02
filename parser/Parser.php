<?php namespace Parser;

use Parser\Interfaces\ProviderInterface;

class Parser
{
    protected $provider;

    public function __construct(ProviderInterface $provider = null)
    {
        $this->provider = $provider;
    }

    public function parse()
    {
        if (!$this->provider) {
            return 'Provider not found';
        }

        return $this->provider->fetch();
    }

}
