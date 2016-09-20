<?php

namespace ApiBundle\Traits;

use ApiBundle\Entity\Company;
use ApiBundle\Entity\FeaturePoint;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * APIResponsible Trait.
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
trait APIResponsible
{
    /**
     * Подготовка ответа с результатами поиска.
     *
     * @param Company[]|FeaturePoint[] $result  Массив объектов, найденных в БД.
     * @param SerializerInterface $serializer   Сериализатор.
     * @param string $serializationContext      Контекст сериализации (иное представление объекта).
     *
     * @return Response
     */
    protected function prepareResponse($result, SerializerInterface $serializer, string $serializationContext = null)
    {
        $response = [];

        $response['result'] = $result;
        $response['response_code'] = Response::HTTP_OK;

        $context        = $serializationContext? SerializationContext::create()->setGroups($serializationContext): null;
        $responseData   = $serializer->serialize($response, 'json', $context);

        return new Response($responseData, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
