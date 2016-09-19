<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Сущность контакта (сайт)
 *
 * @ORM\Entity
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class ContactSite extends Contact
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @JMS\Groups({"companies", "companies_bound_radius"})
     *
     * @var string Сайт
     */
    protected $site;

    /**
     * @return string
     */
    public function getSite(): string
    {
        return $this->site;
    }

    /**
     * @param string $site
     *
     * @return self
     */
    public function setSite(string $site): self
    {
        $this->site = $site;

        return $this;
    }
}
