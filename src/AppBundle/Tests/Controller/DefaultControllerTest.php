<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @param $link
     * @dataProvider linkList
     */
    public function testControllers($link)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $link);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains(
            '/images/Ukraine.png',
            $crawler->filter('img')->extract(array('src'))
        );
    }

    /**
     * @return array
     */
    public function linkList()
    {
        return [
            ['/'],
            ['/team/Ukraine'],
            ['/country/Ukraine'],
            ['/team/Ukraine/player/1'],
        ];
    }

}
