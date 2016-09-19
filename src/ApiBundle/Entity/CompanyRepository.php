<?php

namespace ApiBundle\Entity;

use ApiBundle\Util\GeoHelper;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;

/**
 * Репозиторий объектов Компания.
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class CompanyRepository extends EntityRepository
{
    /**
     * Найти все компании, находящиеся по указанному адресу.
     *
     * @param string $city
     * @param string $street
     * @param string $house
     * @param int    $limit
     * @param int    $offset
     *
     * @return Company[]|null
     */
    public function findByAddress(string $city, string $street, string $house, int $limit, int $offset)
    {
        return $this
            ->createQueryBuilder('c')
            ->addSelect('a')
            ->addSelect('f')
            ->addSelect('cs')
            ->join('c.address', 'a')
            ->join('a.features', 'f')
            ->join('c.contacts', 'cs')
            ->where('a.city = :city AND a.street = :street AND a.house = :house')
            ->setParameters(['city' => $city, 'street' => $street, 'house' => $house])
            ->getQuery()
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getResult();
    }

    /**
     * Найти все компании по заданной категории (включая все подкатегории).
     *
     * @param Category  $category
     * @param int       $findNested
     * @param int       $limit
     * @param int       $offset
     *
     * @return Company[]|null
     */
    public function findByCategory(Category $category, int $findNested, int $limit, int $offset)
    {
        $queryBuilder = $this
            ->createQueryBuilder('c')
            ->addSelect('a')
            ->addSelect('f')
            ->addSelect('cs')
            ->addSelect('cat')
            ->join('c.address', 'a')
            ->join('c.contacts', 'cs')
            ->join('a.features', 'f')
            ->join('c.categories', 'cat')
            ->orderBy('cat.lft', 'ASC');

        if ($findNested) {
            $queryBuilder
                ->where('cat.lft >= :lft AND cat.rgt <= :rgt')
                ->setParameters([
                    'lft' => $category->getLft(),
                    'rgt' => $category->getRgt()
                ]);
        } else {
            $queryBuilder->where('cat.id = :id')
                ->setParameter('id', $category->getId());
        }

        return $queryBuilder
            ->getQuery()
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getResult();
    }

    /**
     * Поиск компании по названию (по подстроке).
     *
     * @param string    $name
     * @param int       $limit
     * @param int       $offset
     *
     * @return array
     */
    public function findByName(string $name, int $limit, int $offset)
    {
        return $this
            ->createQueryBuilder('c')
            ->addSelect('a')
            ->addSelect('f')
            ->addSelect('cs')
            ->join('c.address', 'a')
            ->join('c.contacts', 'cs')
            ->join('a.features', 'f')
            ->where('c.name LIKE :name')
            ->setParameter('name', "%$name%")
            ->getQuery()
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getResult();
    }

    /**
     * Найти организации, находящиеся в заданном радиусе.
     *
     * @param $lat      float   Широта
     * @param $lon      float   Долгота
     * @param $radius   float   Расстояние в метрах
     *
     * @return Company[]|array
     */
    public function findNearby(float $lat, float $lon, float $radius)
    {
        $polygon = GeoHelper::createInscribedPolygon($lat, $lon, $radius);
        $geom    = GeoHelper::convertPolygon2WKT($polygon);

        return $this->findContained($geom);
    }

    /**
     * Найти организации, находящиеся в заданной границе.
     *
     * @param $lat1   float   Широта (координата 1)
     * @param $lon1   float   Долгота (координата 1)
     * @param $lat2   float   Широта (координата 2)
     * @param $lon2   float   Долгота (координата 2)
     *
     * @return Company[]|array
     */
    public function findWithinBound(float $lat1, float $lon1, float $lat2, float $lon2)
    {
        $geom = "POLYGON(($lat1 $lon1, $lat2 $lon1, $lat2 $lon2, $lat1 $lon2, $lat1 $lon1))";

        return $this->findContained($geom);
    }

    /**
     * Найти объекты, попадающие в заданную область.
     *
     * @param $geom string  Область (геметрический объект в формате WKT)
     *
     * @return Company[]|array
     */
    public function findContained(string $geom)
    {
        return $this
            ->createQueryBuilder('c')
            ->addSelect('a')
            ->addSelect('f')
            ->addSelect('cs')
            ->join('c.address', 'a')
            ->join('c.contacts', 'cs')
            ->join('a.features', 'f')
            ->where('st_contains(st_geomfromtext(:geom), f.geom) = 1')
            ->setParameters(['geom' => $geom])
            ->getQuery()
            ->getResult();
    }

    /**
     * Запросить все объекты. Используется вместо findAll() для исключения "Lazy Loading".
     *
     * @param int       $limit
     * @param int       $offset
     *
     * @return Company[]|array
     */
    public function findAllEx(int $limit, int $offset)
    {
        return $this
            ->createQueryBuilder('c')
            ->addSelect('a')
            ->addSelect('f')
            ->addSelect('cs')
            ->join('c.address', 'a')
            ->join('c.contacts', 'cs')
            ->join('a.features', 'f')
            ->getQuery()
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getResult();
    }
}
