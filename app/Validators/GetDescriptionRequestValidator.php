<?php

namespace App\Validators;

use Symfony\Component\HttpFoundation\Request;
use Rakit\Validation\Validation;
use Rakit\Validation\Validator;

class GetDescriptionRequestValidator
{
    private $validator;

    public function __construct()
    {
        $this->validator = Validation::createValidator();
    }

    public function validate(Request $request): ConstraintViolationListInterface
    {
        // Определите правила валидации

            $validator = new Validator;

            // make it
        $validation = $validator->make($request->all(), [
            'name'                  => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|min:6',
            'confirm_password'      => 'required|same:password',
            'avatar'                => 'required|uploaded_file:0,500K,png,jpeg',
            'skills'                => 'array',
            'skills.*.id'           => 'required|numeric',
            'skills.*.percentage'   => 'required|numeric'
        ]);

    // then validate
    $validation->validate();
    }
}