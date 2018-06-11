<?php declare(strict_types = 1);
namespace Searchmetrics\SeniorTest\Tests\Services;

use PHPUnit\Framework\TestCase;
use Searchmetrics\SeniorTest\Config\DatabaseConnection;
use Searchmetrics\SeniorTest\Entities\Url;
use Searchmetrics\SeniorTest\Migrations\UrlMigration;
use Searchmetrics\SeniorTest\Repositories\UrlRepositoryEloquent;
use Searchmetrics\SeniorTest\Services\UrlService;
use Searchmetrics\SeniorTest\Validators\UrlValidator;


class UrlServiceTest extends TestCase
{
    /**
     * @var \Searchmetrics\SeniorTest\Services\UrlService;
     */
    private $service;
    private $urlModel;
    private $urlRepository;
    private $urlValidator;

    function setUp()
    {
        parent::setUp();
        $this->service = new UrlService(new UrlRepositoryEloquent(), new UrlValidator());
        $databaseConnect  = new DatabaseConnection();
        $databaseConnect->connect();
        $migration = new UrlMigration();
        if (!$migration->hasTable()) {
            $migration->create();
        }
    }

    /**
     * @test
     */
    public function instantiation_works() : void
    {
        $this->urlModel = new Url();
        $this->urlRepository = new UrlRepositoryEloquent();
        $this->urlValidator = new UrlValidator();
        self::assertInstanceOf(Url::class, $this->urlModel);
        self::assertInstanceOf(UrlRepositoryEloquent::class, $this->urlRepository);
        self::assertInstanceOf(UrlValidator::class, $this->urlValidator);
    }


    /**
     * @return mixed[]
     */
    public function provideGeneratorExpectations() : array
    {
        $providers = [];

        $file = \fopen(__DIR__ . '/../Resources/url_ids.txt', 'r');

        if (false !== $file) {
            $i=1;
            while (($line = \fgets($file)) !== false) {
                $i++;
                $providers[] = \explode("\t|\t", \trim($line));
                if ($i>=10){
                    break;
                }
            }

            \fclose($file);
        }

        return $providers;
    }

    /**
     * @dataProvider provideGeneratorExpectations
     */
    public function testCreateUrl(string $url, $expectedId) : void
    {

        $data['url'] = $url;
        $result = json_decode($this->service->create($data));
        self::assertSame(
            $expectedId,
            $result->data->code,
            \sprintf('Expected URL ID generator to return ID [%s], got [%s] instead.', $expectedId, $generatedId)
        );
    }

    /**
     * @test
     */
    public function testDeleteUrl() : void
    {
        $data = UrlRepositoryEloquent::findLastInserted();
        $message = json_decode($this->service->delete((int)$data['id']));

        self::assertSame(
            "MESSAGES.DATA_REMOVED",
            $message->result,
            \sprintf('Expected a successfull message [%s].', $result->result)
        );
    }
}
