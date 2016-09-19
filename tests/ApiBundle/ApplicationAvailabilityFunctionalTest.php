<?php

namespace Tests\ApiBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        return [
            ['/api/search/buildings?type=all'],
            ['/api/search/buildings?type=all&page=1&pagesize=100'],
            ['/api/search/buildings?type=street&street=ленин'],
            ['/api/search/buildings?type=street&street=ленин&page=1&pagesize=100'],
            ['/api/search/categories?type=all'],
            ['/api/search/categories?type=all&page=1&pagesize=100'],
            ['/api/search/categories?type=name&name=религ'],
            ['/api/search/categories?type=name&name=религ&page=1&pagesize=100'],
            ['/api/search/companies?type=all'],
            ['/api/search/companies?type=all&page=1&pagesize=100'],
            ['/api/search/companies?type=category&category=3'],
            ['/api/search/companies?type=category&category=3&page=1&pagesize=100'],
            ['/api/search/companies?type=category&category=1&nested=1'],
            ['/api/search/companies?type=category&category=1&nested=1&page=1&pagesize=100'],
            ['/api/search/companies?type=address&city=Томск&street=Ленина%20проспект&house=100'],
            ['/api/search/companies?type=address&city=Томск&street=Ленина%20проспект&house=100&page=1&pagesize=100'],
            ['/api/search/companies?type=id&id=1'],
            ['/api/search/companies?type=id&id=1&page=1&pagesize=100'],
            ['/api/search/companies?type=radius&radius=500&lat=56.478230653487905&lon=84.98508453369142'],
            ['/api/search/companies?type=radius&radius=500&lat=56.478230653487905&lon=84.98508453369142&page=1&pagesize=100'],
            ['/api/search/companies?type=bound&bound%5Blat1%5D=56.48206405096847&bound%5Blon1%5D=84.97695450181179&bound%5Blat2%5D=56.474018052484574&bound%5Blon2%5D=84.98626227980445'],
            ['/api/search/companies?type=bound&bound%5Blat1%5D=56.48206405096847&bound%5Blon1%5D=84.97695450181179&bound%5Blat2%5D=56.474018052484574&bound%5Blon2%5D=84.98626227980445&page=1&pagesize=100'],
        ];
    }
}
