<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Сущность контакта (телефон)
 *
 * @ORM\Entity
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class ContactPhone extends Contact
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @JMS\Groups({"companies", "companies_bound_radius"})
     *
     * @var string Телефон
     */
    protected $phone;

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return self
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
