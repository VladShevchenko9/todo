<?php

namespace App\Validation;

use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;

final class Validator
{
    private Factory $factory;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @return array
     * @throws ValidationException
     */
    public function validate(
        array $data,
        array $rules,
        array $messages = []
    ): array
    {
        $validator = $this->factory->make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
