<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Сущность контакта.
 **
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="contact_type", type="string")
 * @JMS\Discriminator(field="type", map={
 *     "phone": "ApiBundle\Entity\ContactPhone",
 *     "email": "ApiBundle\Entity\ContactEmail",
 *     "site":  "ApiBundle\Entity\ContactSite"
 * })
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
abstract class Contact
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
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Company", inversedBy="contacts")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var Company Фирма
     */
    protected $company;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string Метка
     */
    protected $label;

    /**
     * @ORM\Column(type="integer", options={"unsigned":true, "default":0})
     *
     * @var int Приоритет
     */
    protected $priority = 0;

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
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }

    /**
     * @param Company $company
     *
     * @return self
     */
    public function setCompany(Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return self
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     *
     * @return self
     */
    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }
}
