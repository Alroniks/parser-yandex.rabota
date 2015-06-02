<?php namespace Parser\Providers;

use Parser\BaseProvider;
use SleepingOwl\Apist\Apist;

class Rabota extends BaseProvider
{
    private $propertyMap = [
        'Город' => 'city',
        'Условия работы' => 'conditions',
        'Обязанности' => 'duties',
        'Зарплата' => 'salary',
        'Образование' => 'education',
        'Требования' => 'requirements',
        'Опыт работы' => 'experience',
        'Регистрация' => 'registration',
        'Занятость' => 'employment',
        'Название компании' => 'company',
        'Контактное лицо' => 'contact',
        'Телефон' => 'phone',
        'e-mail' => 'email',
        'Сфера деятельности' => 'scope',
        'Дата обновления' => 'updated',
        'Активно до' => 'until',
        'ID' => 'id'
    ];

    public function fetch() {

        $response = $this->get('', [
            'title' => Apist::filter('#container .appointment-title')->text()->trim(),
            'lines' => Apist::filter('#container .form-line.view-form-line')->each([
                'field' => Apist::filter('.field-name')->text()->trim(),
                'value' => Apist::filter('.adv-point')->text()->trim()
            ])
        ]);

        foreach ($response['lines'] as $v) {
            if (in_array($v['field'], array_keys($this->propertyMap))) {
                $key = $this->propertyMap[$v['field']];
                $response[$key] = $v['value'];
            }
        }

        unset($response['lines']);

        $response['email'] = $this->fetchEmail($response['email']);

        return $response;

    }

    private function fetchEmail($email)
    {
        $email = str_replace(["eval(unescape('","'))"], ['',''], $email);
        $email = urldecode($email);
        $email = str_replace(["document.write('","');"], ['',''], $email);
        $email = strip_tags($email);

        return $email;
    }

}
