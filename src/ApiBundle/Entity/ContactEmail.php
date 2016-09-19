<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Сущность контакта (e-mail)
 *
 * @ORM\Entity
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class ContactEmail extends Contact
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @JMS\Groups({"companies", "companies_bound_radius"})
     *
     * @var string E-mail
     */
    protected $email;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
