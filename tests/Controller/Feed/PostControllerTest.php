<?php

namespace App\Tests\Controller\Feed;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class PostControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/feed/post');

        self::assertResponseIsSuccessful();
    }
}
