<?php

namespace ApiBundle\Entity;

use CrEOF\Spatial\PHP\Types\Geography\Point;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Сущность картографического объекта (точка)
 *
 * @ORM\Entity(repositoryClass="ApiBundle\Entity\FeatureRepository")
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class FeaturePoint extends Feature
{
    /**
     * @ORM\Column(type="float", name="lat")
     * @JMS\Groups({"buildings", "companies", "companies_bound_radius"})
     *
     * @var float Широта
     */
    protected $lat;

    /**
     * @ORM\Column(type="float", name="lon")
     * @JMS\Groups({"buildings", "companies", "companies_bound_radius"})
     *
     * @var float Долгота
     */
    protected $lon;

    /**
     * @ORM\Column(type="point", name="geom")
     *
     * @var Point
     */
    protected $geom;

    /**
     * @return float
     */
    public function getLat(): float
    {
        return $this->lat;
    }

    /**
     * @param float $lat
     *
     * @return self
     */
    public function setLat(float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * @return float
     */
    public function getLon(): float
    {
        return $this->lon;
    }

    /**
     * @param float $lon
     *
     * @return FeaturePoint
     */
    public function setLon(float $lon): self
    {
        $this->lon = $lon;

        return $this;
    }

    /**
     * @return Point
     */
    public function getGeom(): Point
    {
        return $this->geom;
    }
}
