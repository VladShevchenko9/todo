<?php

namespace App\Controllers;

use App\Models\AbstractModel;
use App\Repositories\AbstractRepository;
use App\Validation\Validator;

abstract class AbstractController
{
    /** @var AbstractRepository */
    protected AbstractRepository $repo;

    /** @var Validator */
    protected Validator $validator;

    public function __construct(AbstractRepository $repo, Validator $validator)
    {
        $this->repo = $repo;
        $this->validator = $validator;
    }

    /**
     * @return array
     */
    protected function jsonData(): array
    {
        $inputRaw = file_get_contents('php://input');
        return json_decode($inputRaw, true) ?? [];
    }

    /**
     * @param array $data
     * @param int $status
     */
    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @param array $data
     * @return AbstractModel
     */
    protected function makeModel(array $data): AbstractModel
    {
        /** @var AbstractModel $modelClass */
        $modelClass = $this->repo->getModelClass();
        return $modelClass::fromArray($data);
    }
}
