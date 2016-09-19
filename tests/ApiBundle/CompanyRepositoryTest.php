<?php

namespace Tests\ApiBundle;

use ApiBundle\Entity\CompanyRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Тест репозитория компаний.
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class CompanyRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var CompanyRepository
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
            ->getRepository('ApiBundle:Company');
    }

    public function testFindAllEx()
    {
        $companies = $this->repository->findAllEx(10, 0);

        $this->assertCount(10, $companies);
    }

    public function testFindByAddress()
    {
        $companies = $this->repository->findByAddress('Томск', 'Ленина проспект', '99', 2, 0);

        $this->assertCount(2, $companies);
    }

    public function testFindByCategory()
    {
        $category   = $this->em->getRepository('ApiBundle:Category')->find(3);
        $companies  = $this->repository->findByCategory($category, 1, 10, 0);

        $this->assertCount(10, $companies);
    }

    public function testFindByName()
    {
        $companies  = $this->repository->findByName('баня', 10, 0);

        $this->assertCount(10, $companies);
    }

    public function testFindNearby()
    {
        $companies  = $this->repository->findNearby(56.478230653487905, 84.98508453369142, 500);

        $this->assertCount(227, $companies);
    }

    public function testFindWithinBound()
    {
        $companies  = $this->repository->findWithinBound(56.48206405096847, 84.97695450181179, 56.474018052484574, 84.98626227980445);

        $this->assertCount(135, $companies);
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
