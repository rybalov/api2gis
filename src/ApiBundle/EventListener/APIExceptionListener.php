<?php

namespace ApiBundle\EventListener;

use ApiBundle\Exception\APIExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Listener для обработки исключений и формирования ответа.
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class APIExceptionListener
{
    /**
     * APIExceptionListener constructor.
     */
    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $response   = [];
        $exception  = $event->getException();

        if ($exception instanceof APIExceptionInterface) {
            $response['response_code']  = $exception->getStatusCode();
            $response['error_message']  = $exception->getMessage();
            $response['error_code']     = $exception->getCode();

            $event->setResponse(new JsonResponse($response));
        } else {
            if ($this->kernel->getEnvironment() == 'prod') {
                $response['response_code'] = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;

                $event->setResponse(new JsonResponse($response));
            }
        }
    }
}
