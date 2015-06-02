<?php namespace Parser\Providers;

use Parser\BaseProvider;
use SleepingOwl\Apist\Apist;

class Riv extends BaseProvider
{

    public function fetch()
    {

        $response = $this->get('', [
            'title' => Apist::filter('#main-content h1')->text()->trim(),
            'salary' => Apist::filter('#main-content .tulin')->text()->str_replace('бел.руб.','')->trim(),
            'conditions' => Apist::filter('#main-content .us_cl ul')->text()->trim(),
            'duties' => Apist::filter('#main-content .ob_cl ul')->text()->trim(),
            'requirements' => Apist::filter('#main-content .tr_cl ul')->text()->trim(),
            'additional' => Apist::filter('#main-content .tr_cl ul')->text()->trim(),
            'company' => Apist::filter('#main-content .company a')->text()->trim(),
            'contact' => Apist::filter('#main-content .colorblue')->text()->trim()
        ]);

        $contact = explode("\n", $response['contact']);
        $response['phone'] = isset($contact[1]) ? trim($contact[1]) : '';
        $response['contact'] = isset($contact[2]) ? trim($contact[2]) : '';

        return $response;

    }

}
