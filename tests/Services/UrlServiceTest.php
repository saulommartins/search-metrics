<?php declare(strict_types = 1);

namespace Searchmetrics\SeniorTest\Tests\Services;

use PHPUnit\Framework\TestCase;
use Searchmetrics\SeniorTest\Entities\Url;
use Searchmetrics\SeniorTest\Repositories\UrlRepositoryEloquent;
use Searchmetrics\SeniorTest\Services\UrlService;
use Searchmetrics\SeniorTest\Validators\UrlValidator;


class UrlServiceTest extends TestCase
{
    /**
     * @var \Searchmetrics\SeniorTest\Services\UrlService;
     */
    private $service;


    function setUp()
    {
        parent::setUp();
        $this->service = new UrlService(new UrlRepositoryEloquent(), new UrlValidator());

        $this->service = $this->createMock(UrlService::class);
    }

    /**
     * @test
     */
    public function instantiation_works() : void
    {
        $urlModel = new Url();
        $urlRepository = new UrlRepositoryEloquent();
        $urlValidator = new UrlValidator();
        self::assertInstanceOf(Url::class, $urlModel);
        self::assertInstanceOf(UrlRepositoryEloquent::class, $urlRepository);
        self::assertInstanceOf(UrlValidator::class, $urlValidator);
    }


    /**
     * @return mixed[]
     */
    public function provideGeneratorExpectations() : array
    {
        $providers = [];

        $file = \fopen(__DIR__ . '/../Resources/url_ids.txt', 'r');

        if (false !== $file) {
            while (($line = \fgets($file)) !== false) {
                $providers[] = \explode("\t|\t", \trim($line));
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
        $this->service->method('create')
            ->will($this->returnValue([
                'url' => $url,
                'code' => $expectedId
            ])
        );

        $data['url'] = $url;
        $result = $this->service->create($data);

        self::assertSame(
            $expectedId,
            $result['code'],
            \sprintf('Expected URL ID generator to return ID [%s], got [%s] instead.', $expectedId, $generatedId)
        );
    }
}
