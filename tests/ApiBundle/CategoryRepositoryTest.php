<?php

namespace Tests\ApiBundle;

use ApiBundle\Entity\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Тест репозитория категория.
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class CategoryRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var CategoryRepository
     */
    private $repository;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->repository = $this->em
            ->getRepository('ApiBundle:Category');
    }

    public function testFindAllEx()
    {
        $categories = $this->repository->findAll();

        $this->assertCount(69, $categories);
    }


    public function testFindByName()
    {
        $categories = $this->repository->findByName('религ', 10, 0);

        $this->assertCount(1, $categories);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }
}
