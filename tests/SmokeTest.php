<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokeTest extends WebTestCase
{
    /**
     * @dataProvider provideAllPagesUrls
     */
    public function testAllPagesAreSuccessful($url, $pageTitle)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);

        $this->assertResponseIsSuccessful();
    }

    public function provideAllPagesUrls()
    {
        //la liste de toutes les urls publiques de mon site
        return [
            ['/', 'Home'],
            ['/tmdb', 'Fetch datas'],
            //['/contact', 'testounet'],
        ];
    }
}
