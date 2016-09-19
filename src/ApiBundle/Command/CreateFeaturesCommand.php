<?php

namespace ApiBundle\Command;

use ApiBundle\Entity\Address;
use ApiBundle\Entity\FeaturePoint;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Команда для сбора координат по адресам и создания картографических объектов.
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class CreateFeaturesCommand extends ContainerAwareCommand
{
    /** Количеств записей для инсерта */
    const BATCH_SIZE = 1000;

    protected function configure()
    {
        $this
            ->setName('features:create')
            ->setDescription('Сбор координат по адресам и создание картографических объектов');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityManager  = $this->getContainer()->get('doctrine.orm.entity_manager');
        $repository     = $entityManager->getRepository('ApiBundle:Address');

        /** @var Address[] $addresses */
        $addresses      = $repository->findAll();

        $count = 0;

        foreach ($addresses as $address) {
            if (($point = $this->getPointByAddress($address)) !== null) {
                $feature = new FeaturePoint();
                $feature
                    ->setLat($point['lat'])
                    ->setLon($point['lon'])
                    ->addAddress($address);

                $address->addFeature($feature);

                $entityManager->persist($feature);
                $count++;

                if ($count % self::BATCH_SIZE) {
                    $entityManager->flush();
                    $entityManager->clear();
                    $count = 0;
                }

                $output->writeln($point['lat'] . ' ' . $point['lon']);
            }
        }

        $entityManager->flush();

        return 0;
    }

    /**
     * @param Address $address
     *
     * @return array|null
     */
    protected function getPointByAddress(Address $address)
    {
        $geocode    = urlencode($address->getCity() . ', ' . $address->getStreet() . ', ' . $address->getHouse());
        $json       = file_get_contents("https://geocode-maps.yandex.ru/1.x/?format=json&geocode=$geocode");
        $response   = json_decode($json, true);
        $point      = @$response['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];

        if (empty($point)) {
            return null;
        } else {
            $coordinates = explode(" ", $point);

            return [
                'lat' => $coordinates[1],
                'lon' => $coordinates[0],
            ];
        }
    }
}
