<?php

namespace Tests\ApiBundle;

use ApiBundle\Entity\FeatureRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Тест репозитория объектов.
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class FeatureRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var FeatureRepository
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
            ->getRepository('ApiBundle:FeaturePoint');
    }

    public function testFindAllEx()
    {
        $buildings = $this->repository->findAllEx(10, 0);

        $this->assertCount(10, $buildings);
    }


    public function testFindByStreet()
    {
        $buildings = $this->repository->findByStreet('ленина', 10, 0);

        $this->assertCount(10, $buildings);
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
