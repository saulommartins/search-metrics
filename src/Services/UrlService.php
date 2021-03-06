<?php declare(strict_types = 1);

namespace Searchmetrics\SeniorTest\Services;


use Searchmetrics\SeniorTest\Network\SearchMetricsUrlIdGenerator;
use Searchmetrics\SeniorTest\Repositories\UrlRepositoryEloquent as UrlRepository;
use Searchmetrics\SeniorTest\Validators\UrlValidator;
use Carbon\Carbon;

/**
 * Class UrlService
 * @package Searchmetrics\SeniorTest\Services
 */
class UrlService
{
    /**
     * @var UrlRepositoryEloquent
     */
    protected $repository;

    /**
     * @var UrlValidator
     */
    protected $validator;

    /**
     * UrlService constructor.
     * @param UrlRepository $repository
     * @param UrlValidator $validator
     */
    public function __construct( $repository,  $validator)
    {
        if (isset($repository)) {
            $this->setRepository($repository);
        }
        if (isset($validator)) {
            $this->setValidator($validator);
        }
    }

    /**
     * @param UrlRepository $repository
     */
    private function setRepository(UrlRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UrlValidator $validator
     */
    private function setValidator(UrlValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @return  UrlRepository
     */
    public function r() : UrlRepository
    {
        return $this->repository;
    }

    /**
     * @return  UrlValidator
     */
    public function v() : UrlValidator
    {
        return $this->validator;
    }

    /**
     * Delete register on database.
     * @param int $id
     * @return string
     */
    public function delete(int $id) : string
    {
        try {
            $obj = $this->repository->delete($id);

            return $this->response(['result' => 'MESSAGES.DATA_REMOVED', 'data' => ['id' => $id]]);

        } catch (ValidatorException $e) {
            return $this->response($e->getMessageBag(),501);

        }
    }

    /**
     * Insert register on database.
     * @param array $data
     * @return string
     */
    public function create(array $data) : string
    {
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        $data['code']       = (new SearchMetricsUrlIdGenerator())->generate($data['url']);
        try {
            $this->validator->passesOrFailCreate();
            $obj = $this->repository->create($data);
            return $this->response(['result' => 'MESSAGES.DATA_SAVED', 'data' => ['id' => $obj->id, 'code' => $obj->code]]);

        } catch (ValidatorException $e) {
            return $this->response($e->getMessageBag(),501);

        }
    }

    /**
     * Update register on database
     * @param array $data
     * @param int $id
     * @return string
     */
    public function update(array $data, int $id) : string
    {
        $data['updated_at'] = Carbon::now();
        try {
            $this->validator->with($data)->passesOrFailUpdate();
            $obj = $this->repository->update($data, $id);
            return $this->response(['result' => 'MESSAGES.DATA_SAVED', 'data' => ['id' => $obj->id, 'code' => $obj->code]]);
        } catch (ValidatorException $e) {
            return $this->response($e->getMessageBag(),501);
        }
    }

    /**
     * @param string $data
     * @return string
     */
    private function response (array $data) :string
    {
        return json_encode($data);
    }
}