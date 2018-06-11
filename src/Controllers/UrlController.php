<?php declare(strict_types = 1);

namespace Searchmetrics\SeniorTest\Controllers;


use Searchmetrics\SeniorTest\Config\DatabaseConnection;
use Searchmetrics\SeniorTest\Repositories\UrlRepositoryEloquent;

class UrlController
{
    public function getCodeByUrl( string $url = "") : string
    {
        $databaseConnect  = new DatabaseConnection();
        $databaseConnect->connect();
        try {
            $urlRepository = new UrlRepositoryEloquent();
            $data = $urlRepository->findCodeByUrl($url);
            return json_encode([
                'code' => $data['code']
            ]);
        }catch (\Exception $e) {
            return json_encode([
                'error' => "This url is not registered."
            ]);
        }

    }
}