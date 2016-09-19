<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Репозиторий картографических объектов.
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class FeatureRepository extends EntityRepository
{
    /**
     * Найти объекты по названию улицы.
     *
     * @param string    $street Улица (строка поиска)
     * @param int       $limit
     * @param int       $offset
     *
     * @return FeaturePoint[]|FeaturePolygon|FeaturePolyline[]|array
     */
    public function findByStreet($street, int $limit, int $offset)
    {
        return $this
            ->createQueryBuilder('f')
            ->addSelect('a')
            ->distinct()
            ->join('f.addresses', 'a')
            ->where('a.street LIKE :street')
            ->setParameter('street', "%$street%")
            ->getQuery()
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getResult();
    }

    /**
     * Запросить все объекты. Используется вместо findAll() для исключения "Lazy Loading".
     *
     * @param int       $limit
     * @param int       $offset
     *
     * @return FeaturePoint[]|FeaturePolygon|FeaturePolyline[]|array
     */
    public function findAllEx(int $limit, int $offset)
    {
        return $this
            ->createQueryBuilder('f')
            ->addSelect('a')
            ->join('f.addresses', 'a')
            ->getQuery()
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getResult();
    }
}
