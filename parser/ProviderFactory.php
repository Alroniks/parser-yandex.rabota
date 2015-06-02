<?php namespace Parser;

class ProviderFactory
{

    public function make($source)
    {

        $chunks = parse_url($source);

        if (empty($chunks['host'])) {
            throw new \OutOfRangeException('Root section of url not found');
        }

        $root = explode('.', $chunks['host']);
        array_pop($root);

        $provider = join('', array_map(function($e) {
            return ucfirst($e);
        }, $root));
        $className = "Parser\\Providers\\" . $provider;

        if (!class_exists($className)) {
            return null;
        }

        return new $className($source);

    }

}
