<?php

namespace ApiBundle\Entity;

use CrEOF\Spatial\PHP\Types\Geometry\GeometryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Сущность картографического объекта
 *
 * @ORM\Entity(repositoryClass="ApiBundle\Entity\FeatureRepository")
 * @ORM\Table(name="feature", indexes={
 *      @ORM\Index(name="spatial_idx", columns={"geom"})
 * })
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="feature_type", type="string")
 * @JMS\Discriminator(field="type", map={
 *     "point":     "ApiBundle\Entity\FeaturePoint",
 *     "polygon":   "ApiBundle\Entity\FeaturePolygon",
 *     "polyline":  "ApiBundle\Entity\FeaturePolyline"
 * })
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
abstract class Feature
{
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="ApiBundle\Entity\Address", mappedBy="features")
     * @JMS\MaxDepth(1)
     * @JMS\Groups({"buildings", "street", "address"})
     *
     * @var Address[] Связанные адреса (в теории у одного здания может быть несколько адресов)
     */
    protected $addresses;

    /**
     * @ORM\Column(type="geometry", name="geom")
     *
     * @var GeometryInterface
     */
    protected $geom;

    /**
     * Feature constructor.
     */
    public function __construct()
    {
        $this->addresses = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Address[]
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @param Address[] $addresses
     *
     * @return Feature
     */
    public function setAddresses(array $addresses): self
    {
        $this->addresses = $addresses;

        return $this;
    }

    /**
     * @param Address $address
     *
     * @return self
     */
    public function addAddress(Address $address): self
    {
        $this->addresses->add($address);

        return $this;
    }

    /**
     * @param GeometryInterface $geom
     *
     * @return self
     */
    public function setGeom(GeometryInterface $geom): self
    {
        $this->geom = $geom;

        return $this;
    }
}
