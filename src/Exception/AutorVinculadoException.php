<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class AutorVinculadoException extends HttpException
{
    public function __construct(string $message = 'O autor está vinculado a um livro.', int $code = 400)
    {
        parent::__construct($code, $message);
    }
}