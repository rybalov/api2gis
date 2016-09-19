<?php

namespace ApiBundle\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Исключение для обработки ошибок на стороне сервера.
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class APIInternalErrorException extends HttpException implements APIExceptionInterface
{
    /**
     * @inheritdoc
     */
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct(Response::HTTP_INTERNAL_SERVER_ERROR, $message, $previous, array(), $code);
    }
}
