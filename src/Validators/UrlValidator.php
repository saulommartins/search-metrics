<?php declare(strict_types = 1);

namespace Searchmetrics\SeniorTest\Validators;

class UrlValidator
{
    protected $rules = [

        "create" => [
            'url' => 'required|max:255',
            'code' => 'required|max:128',
        ],
        "update" => [
            'url' => 'required|max:512',
            'code' => 'required|max:128',
        ]

    ];

    protected $attributes = [
        'code' => 'Code',
        'url' => 'Url'
    ];

    public function passesOrFailCreate()
    {
        return true;
    }

    public function passesOrFailUpdate()
    {
        return true;
    }

}
