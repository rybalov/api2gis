<?php

namespace ApiBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Исключение для обработки ошибок на стороне сервера.
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class APIInternalErrorException extends HttpException implements APIExceptionInterface
{
    /**
     * Constructor.
     *
     * @param string     $message  The internal exception message
     * @param \Exception $previous The previous exception
     * @param int        $code     The internal exception code
     */
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct(500, $message, $previous, array(), $code);
    }
}
