<?php

namespace ApiBundle\Command;

use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Tools\Console\Command\ImportCommand as DBALImportCommand;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Команда для загрузки дампа БД.
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class ImportCommand extends DBALImportCommand implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $db = $em->getConnection();

        $helperSet = $this->getHelperSet();
        $helperSet->set(new ConnectionHelper($db), 'db');
        $helperSet->set(new EntityManagerHelper($em), 'em');

        parent::execute($input, $output);
    }
}
