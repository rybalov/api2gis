<?php

namespace ApiBundle\Entity;

use CrEOF\Spatial\PHP\Types\Geography\Polygon;
use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность картографического объекта (полигон)
 *
 * @ORM\Entity(repositoryClass="ApiBundle\Entity\FeatureRepository")
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class FeaturePolygon extends Feature
{
    /**
     * @ORM\Column(type="polygon", name="geom")
     *
     * @var Polygon
     */
    protected $geom;

    /**
     * @return Polygon
     */
    public function getGeom(): Polygon
    {
        return $this->geom;
    }
}
