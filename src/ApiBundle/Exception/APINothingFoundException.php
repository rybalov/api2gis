<?php

namespace ApiBundle\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Исключение "данные не были найдены".
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class APINothingFoundException extends NotFoundHttpException implements APIExceptionInterface
{
    protected $code = 'withoutResult';
}
