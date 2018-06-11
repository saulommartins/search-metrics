<?php declare(strict_types = 1);

namespace Searchmetrics\SeniorTest\Entities;


use Illuminate\Database\Eloquent\Model as Eloquent;

class Url extends Eloquent
{

    /**
     * The name of the table.
     * @var string
     */
    protected $table = 'urls';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [

        'url', 'code'

    ];

}