<?php namespace Parser;

use SleepingOwl\Apist\Apist;

require_once __DIR__ . '/vendor/autoload.php';

define('MODX_API_MODE', true);
include_once __DIR__ . '/../index.php';

$providerFactory = new ProviderFactory();

$entryPoint = 'https://rabota.yandex.by/search.xml/?job_industry=298&rid=157';

$parser = new Parser($providerFactory->make($entryPoint));
$list = $parser->parse();

foreach ($list as &$item) {
    $page = (new Parser($providerFactory->make($item['url'])))->parse();

    if (is_array($page)) {
        $item = array_merge($item, $page);
    }

    $item['hash'] = substr(md5($item['url']), 0, 10);

    unset($page);

    // save to modx
    if (!$document = $modx->getObject('modDocument', ['alias' => $item['hash']])) {
        $document = $modx->newObject('modDocument');
    }

    $document->set('pagetitle', $item['title']);
    $document->set('parent', 7);
    $document->set('template', 3);
    $document->set('published', 1);
    $document->set('alias', $item['hash']);

    $document->setTVValue('v_salary', isset($item['salary']) ? $item['salary'] : '');
    $document->setTVValue('v_link', isset($item['url']) ? $item['url'] : '');
    $document->setTVValue('v_city', isset($item['city']) ? $item['city'] : '');
    $document->setTVValue('v_company', isset($item['company']) ? $item['company'] : '');
    $document->setTVValue('v_conditions', isset($item['conditions']) ? $item['conditions'] : '');
    $document->setTVValue('v_duties', isset($item['duties']) ? $item['duties'] : '');
    $document->setTVValue('v_requirements', isset($item['requirements']) ? $item['requirements'] : '');
    $document->setTVValue('v_additional', isset($item['additional']) ? $item['additional'] : '');

    if (!empty($item['email'])) {
        if (!$user = $modx->getObject('modUser', ['username' => $item['email']])) {
            $user = $modx->newObject('modUser');
            $user->set('username', $item['email']);
        }

        if ($user && $user->getOne('Profile')) {
            $user->Profile->set('email', $item['email']);
            $user->Profile->set('fullname', $item['contact']);
            $user->Profile->set('mobilephone', $item['phone']);
            $user->Profile->set('comment', $item['company']);
            $user->Profile->save();
        }

        $user->save();

        $document->createdby = $user->get('id');
    }

    $document->save();
}







//print_r($list);
