<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testJSAjaxCall()
    {
        $client = static::createClient();

        $client->xmlHttpRequest('GET', '/api/1/movie/search', ['kw' => 'bat']);

        $response = $client->getResponse();
        $content = $response->getContent();

        $this->assertIsString($content);
        $datas = json_decode($content, true);
        //dd($datas);

        $this->assertArrayHasKey("results", $datas);
    }
}
