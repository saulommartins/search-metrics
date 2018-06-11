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
        try
        {
            $result = Url::where('url', '=', $url);
            $data = $result->first()->original;
            if (is_null($data)) {
                throw new \Exception('No records found.');
            }
        }catch (\Exception $e ) {
            throw new \Exception('Error trying to retrive data.');
        }
        return $data;
    }

    /**
     * @return array
     */
    public function findLastInserted() : array
    {
        try
        {
            $result = Url::where("id",">", 0)
                ->orderBY("id","desc");
            $data = $result->first()->original;
            if (is_null($data)) {
                throw new \Exception('No records found.');
            }
        }catch (\Exception $e ) {
            throw new \Exception('Error trying to retrive data.');
        }
        return $data;
    }
}


