<?php

namespace ApiBundle\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Исключение для обработки некорректно отправленных данных запроса.
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class APIIncorrectRequestException extends BadRequestHttpException implements APIExceptionInterface
{
    protected $code = 'incorrectRequest';
}
