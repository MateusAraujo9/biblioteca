<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class AssuntoVinculadoException extends HttpException
{
    public function __construct(string $message = 'O assunto está vinculado a um livro.', int $code = 400)
    {
        parent::__construct($code, $message);
    }
}