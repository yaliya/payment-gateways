<?php

namespace App\Request;

use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractRequest
{
    public function __construct(protected ValidatorInterface $validator)
    {}

    public function validate(): void
    {
        $errors = $this->validator->validate($this);

        if ($errors->count()) {
            throw new ValidationFailedException('Validation failed', $errors);
        }
    }
}