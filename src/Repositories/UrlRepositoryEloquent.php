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
    public function model() : string
    {
        return Url::class;
    }

    /**
     * @param string $url
     * @return array
     */
    public function findCodeByUrl(string $url = "") : array
    {
        if ($url === '') {
            throw new \InvalidArgumentException('URL string must not be empty!');
        }
        $result = Url::where('url','=',$url);
        return $result->first()->original;
    }
}


