<?php declare(strict_types = 1);

namespace Searchmetrics\SeniorTest\Services;


use Searchmetrics\SeniorTest\Network\SearchMetricsUrlIdGenerator;
use Searchmetrics\SeniorTest\Repositories\UrlRepositoryEloquent as UrlRepository;
use Searchmetrics\SeniorTest\Validators\UrlValidator;
use Carbon\Carbon;

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

    private function setValidator( UrlValidator $validator)
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
    public function delete($id)
    {
        try {
            $obj = $this->repository->delete($id);

            return $this->response(['result' => 'MESSAGES.DATA_REMOVED', 'data' => ['id' => $obj->id]],200);

        } catch (ValidatorException $e) {
            return $this->response($e->getMessageBag(),501);

        }
    }

    public function create(array $data)
    {
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        $data['code']       = (new SearchMetricsUrlIdGenerator())->generate($data['url']);
        try {
            $this->validator->passesOrFailCreate();
            $obj = $this->repository->create($data);
            // dd($post);
            return $this->response(['result' => 'MESSAGES.DATA_SAVED', 'data' => ['id' => $obj->id]],200);

        } catch (ValidatorException $e) {
            return $this->response($e->getMessageBag(),501);

        }
    }

    public function update(array $data, $id)
    {
        $data['updated_at'] = Carbon::now();
        try {
            $this->validator->with($data)->passesOrFailUpdate();
            // return $this->response($this->repository->update($data, $id),501);
            $obj = $this->repository->update($data, $id);
            // return $this->response('MESSAGES.DATA_SAVED',200);
            return $this->response(['result' => 'MESSAGES.DATA_SAVED', 'data' => ['id' => $obj->id]],200);
        } catch (ValidatorException $e) {
            return $this->response($e->getMessageBag(),501);
        }
    }

}