<?php declare(strict_types = 1);

namespace Searchmetrics\SeniorTest\Repositories;

use Searchmetrics\SeniorTest\Entities\Url;

/**
 * Class UrlRepositoryEloquent
 */
class UrlRepositoryEloquent extends Url
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Url::class;
    }

    public function findCodeByUrl($url)
    {
        $result = Url::where('url','=',$url);
        return $result->get();
    }
}


