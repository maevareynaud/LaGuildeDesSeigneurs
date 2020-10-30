<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTest extends WebTestCase
{

    /*
    * Tests redirect index
    */

    public function getRedirectIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/character');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    
    /*
    * Tests index
    */

    public function testIndex() 
    {
        $client = static::createClient();
        $client->request('GET', '/character');
        
        $this->assertJsonResponse($client->getResponse());
    }

    /*
    * Tests display 
    */

    public function testDisplay(){
        $client = static::createClient();
        $client->request('GET', '/character/display/5816e16a9be0135693bea3c50312148b3efca02a');
        $this->assertJsonResponse($client->getResponse());
    }

    /*
    * Tests create
    */

    public function testCreate(){
        $client = static::createClient();
        $client->request('POST', '/character/create');
        $this->assertJsonResponse($client->getResponse());
    }

    /*
    * Asserts that a response is in json
    */

    public function assertJsonResponse($response){
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
    }

    

   
}