<?php

namespace App\Tests;

use App\Tmdb\TmdbCaller;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TmdbCallerTest extends KernelTestCase
{
    public function testGetTrailerYoutubeIdWithExistingMovie()
    {
        $this->bootKernel();
        $tmdbCaller = self::$container->get('App\Tmdb\TmdbCaller');

        $result = $tmdbCaller->getTrailer(11);
        $this->assertIsString($result);
        $this->assertEquals("vZ734NWnAHA", $result);
    }

    public function testNoIdWithNonExistingMovie()
    {
        $this->bootKernel();
        $tmdbCaller = self::$container->get('App\Tmdb\TmdbCaller');

        $result = $tmdbCaller->getTrailer(2347947823894792379472389487298748237489237);
        $this->assertEquals(null, $result);
    }
}
