<?php

namespace ApiBundle\Entity;

use CrEOF\Spatial\PHP\Types\Geography\LineString;
use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность картографического объекта (полилиния)
 *
 * @ORM\Entity(repositoryClass="ApiBundle\Entity\FeatureRepository")
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class FeaturePolyline extends Feature
{
    /**
     * @ORM\Column(type="linestring", name="geom")
     *
     * @var LineString
     */
    protected $geom;

    /**
     * @return LineString
     */
    public function getGeom(): LineString
    {
        return $this->geom;
    }
}
