<?php

namespace ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Сущность адреса объекта.
 *
 * @ORM\Entity()
 * @ORM\Table(indexes={
 *      @ORM\Index(name="street_idx", columns={"street"})
 * })
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class Address
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
     * @ORM\Column(type="string", length=255, nullable=true)
     * @JMS\Groups({"buildings", "companies", "companies_bound_radius"})
     *
     * @var string Город
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @JMS\Groups({"buildings", "companies", "companies_bound_radius"})
     *
     * @var string Улица
     */
    protected $street;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @JMS\Groups({"buildings", "companies", "companies_bound_radius"})
     *
     * @var string Здание
     */
    protected $house;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     * @JMS\Groups({"buildings", "companies", "companies_bound_radius"})
     *
     * @var string Почтовый индекс
     */
    protected $postcode;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Company", mappedBy="address")
     * @ORM\OrderBy({"name" = "ASC"})
     *
     * @var Company[] Компании
     */
    protected $companies;

    /**
     * @ORM\ManyToMany(targetEntity="ApiBundle\Entity\Feature", inversedBy="addresses")
     * @ORM\JoinTable(
     *     name="feature_address",
     *     joinColumns={@ORM\JoinColumn(name="address_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="feature_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     * @JMS\MaxDepth(1)
     * @JMS\Groups({"companies", "companies_bound_radius"})
     *
     * @var Feature[] Привязанные картографические объекты
     */
    protected $features;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string Комментарий
     */
    protected $comment;

    /**
     * Address constructor.
     */
    public function __construct()
    {
        $this->companies    = new ArrayCollection();
        $this->features     = new ArrayCollection();
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
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     *
     * @return self
     */
    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return string
     */
    public function getPostcode(): string
    {
        return $this->postcode;
    }

    /**
     * @param string $postcode
     *
     * @return self
     */
    public function setPostcode(string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * @return Feature[]
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * @param Feature[] $features
     *
     * @return self
     */
    public function setFeatures(array $features): self
    {
        $this->features = $features;

        return $this;
    }

    /**
     * @param Feature $feature
     *
     * @return self
     */
    public function addFeature($feature): self
    {
        if ($this->features) {
            $this->features[] = $feature;
        } else {
            $this->features = [$feature];
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street
     *
     * @return Address
     */
    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return string
     */
    public function getHouse(): string
    {
        return $this->house;
    }

    /**
     * @param string $house
     *
     * @return self
     */
    public function setHouse(string $house): self
    {
        $this->house = $house;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return self
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Company[]|null
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     * @param Company[] $companies
     *
     * @return self
     */
    public function setCompanies(array $companies): self
    {
        $this->companies = $companies;

        return $this;
    }
}
