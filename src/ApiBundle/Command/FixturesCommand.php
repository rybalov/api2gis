<?php

namespace ApiBundle\Command;

use ApiBundle\Entity\Address;
use ApiBundle\Entity\Category;
use ApiBundle\Entity\Company;
use ApiBundle\Entity\ContactPhone;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Команда для загрузки тестового набора данных.
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class FixturesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fixtures:test');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $address = new Address();
        $address
            ->setStreet('ул. Карла Ильмера')
            ->setHouse('10/3');
        $em->persist($address);
        $em->flush();

        $address = $em->getRepository('ApiBundle:Address')->find(1);
        $company = new Company();
        $company
            ->setName('Никита Юнайтед Бой')
            ->setAddress($address);
        $em->persist($company);
        $em->flush();

        $company = $em->getRepository('ApiBundle:Company')->find(1);
        $contact = new ContactPhone();
        $contact
            ->setPhone('+79234010002')
            ->setLabel('Домашний')
            ->setCompany($company);
        $em->persist($contact);
        $em->flush();

        $cat = new Category();

        $cat1 = new Category();
        $cat1
            ->setName('Полуфабрикаты оптом')
            ->setParentCategory($cat);
        $em->persist($cat1);

        $cat2 = new Category();
        $cat2
            ->setName('Мясная продукция')
            ->setParentCategory($cat);
        $em->persist($cat2);

        $cat
            ->setName('Еда')
            ->setCategories([$cat1, $cat2]);
        $em->persist($cat);
        $em->flush();

        $company    = $em->getRepository('ApiBundle:Company')->find(1);
        $category1  = $em->getRepository('ApiBundle:Category')->find(1);
        $category1->setCompanies([$company]);
        $category2  = $em->getRepository('ApiBundle:Category')->find(3);
        $category2->setCompanies([$company]);
        $company->setCategories([$category1, $category2]);
        $em->merge($company);
        $em->flush();

        return 0;
    }
}
