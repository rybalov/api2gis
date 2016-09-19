<?php

namespace ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Сущность компании.
 *
 * @ORM\Entity(repositoryClass="ApiBundle\Entity\CompanyRepository")
 * @ORM\Table(name="company", indexes={
 *      @ORM\Index(name="name_idx", columns={"name"})
 * })
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class Company
{
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Groups({"companies", "companies_bound_radius"})
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @JMS\Groups({"companies", "companies_bound_radius"})
     *
     * @var string Название
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string Комментарий
     */
    protected $comment;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Address", inversedBy="companies")
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     * @JMS\MaxDepth(1)
     * @JMS\Groups({"companies", "companies_bound_radius"})
     *
     * @var Address Адрес
     */
    protected $address;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Contact", mappedBy="company")
     * @ORM\OrderBy({"priority" = "ASC"})
     * @JMS\Groups({"companies", "companies_bound_radius"})
     *
     * @var Contact[] Контакты
     */
    protected $contacts;

    /**
     * @ORM\ManyToMany(targetEntity="ApiBundle\Entity\Category", mappedBy="companies")
     *
     * @var Category[] Категории
     */
    protected $categories;

    /**
     * Company constructor.
     */
    public function __construct()
    {
        $this->address      = new ArrayCollection();
        $this->contacts     = new ArrayCollection();
        $this->categories   = new ArrayCollection();
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @param Address $address
     *
     * @return self
     */
    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Contact[]
     */
    public function getContacts(): array
    {
        return $this->contacts;
    }

    /**
     * @param Contact[] $contacts
     *
     * @return self
     */
    public function setContacts(array $contacts): self
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * @return Category[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param Category[] $categories
     *
     * @return self
     */
    public function setCategories(array $categories): self
    {
        $this->categories = $categories;

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
     * @return Company
     */
    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
