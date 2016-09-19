<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * Репозиторий категорий.
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class CategoryRepository extends NestedTreeRepository
{
    /**
     * Найти категории по названию.
     *
     * @param string    $name   Название категории (строка поиска)
     * @param int       $limit
     * @param int       $offset
     *
     * @return Category[]|array
     */
    public function findByName($name, int $limit, int $offset)
    {
        return $this
            ->createQueryBuilder('c')
            ->where('c.name LIKE :name')
            ->setParameter('name', "%$name%")
            ->orderBy('c.lft', 'ASC')
            ->getQuery()
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getResult();
    }
}
