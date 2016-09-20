<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;

/**
 * Категория (рубрика).
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="ApiBundle\Entity\CategoryRepository")
 * @ORM\Table(indexes={
 *      @ORM\Index(name="name_idx", columns={"name"})
 * })
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Groups({"categories"})
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @JMS\Groups({"categories"})
     *
     * @var string
     */
    protected $name;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(type="integer")
     * @JMS\Groups({"categories"})
     *
     * @var int
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     *
     * @var int
     */
    private $root;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Category", inversedBy="categories")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * @Gedmo\TreeParent
     *
     * @var Category Родительская категория
     */
    protected $parentCategory;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Category", mappedBy="parentCategory")
     * @ORM\OrderBy({"lft" = "ASC"})
     *
     * @var Category[] Дочерние категории
     */
    protected $categories;

    /**
     * @ORM\ManyToMany(targetEntity="ApiBundle\Entity\Company", inversedBy="categories")
     * @ORM\JoinTable(
     *     name="company_category",
     *     joinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="company_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     *
     * @var Company[] Связанные компании
     */
    protected $companies;

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
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Category
     */
    public function getParentCategory()
    {
        return $this->parentCategory;
    }

    /**
     * @param Category $parentCategory
     *
     * @return self
     */
    public function setParentCategory(Category $parentCategory): self
    {
        $this->parentCategory = $parentCategory;

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
     * @return Company[]
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

    /**
     * @param null|Category $category
     *
     * @return Company[]|array
     */
    public function getNestedCompanies($category = null)
    {
        $category   = $category? : $this;
        $nested     = $category->companies? : [];

        foreach ($category->categories as $category) {
            $companies = $this->getNestedCompanies($category);
            foreach ($companies as $company) {
                $nested[] = $company;
            }
        }

        return $nested;
    }

    /**
     * @return mixed
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * @param mixed $lft
     *
     * @return self
     */
    public function setLft(int $lft): self
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * @param mixed $lvl
     *
     * @return self
     */
    public function setLvl(int $lvl): self
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * @param mixed $rgt
     *
     * @return self
     */
    public function setRgt(int $rgt): self
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param mixed $root
     * @return self
     */
    public function setRoot(int $root): self
    {
        $this->root = $root;

        return $this;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\Groups({"categories"})
     *
     * @return int|null
     */
    public function getParentId()
    {
        if ($this->getParentCategory()) {
            return $this->getParentCategory()->getId();
        }

        return null;
    }
}
