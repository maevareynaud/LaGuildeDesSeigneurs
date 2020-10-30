<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTest extends WebTestCase
{
    private $content;
    private static $identifier;
    private $client;

    public function setUp(){
        $this->client = static::createClient();
    }

    /*
    * Tests redirect index
    */

    public function getRedirectIndex()
    {
        $this->client->request('GET', '/character');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    /*
    * Test bad identifier
    */

    public function testBadIdentifier()
    {
        $this->client->request('GET', 'character/display/badIdentifier');
        $this->assertError404($this->client->getResponse()->getStatusCode());
    }

    /*
    * Test that Response returns 404
    */

    public function assertError404($statusCode)
    {
        $this->assertEquals(404, $statusCode);
    }


    /*
    * Tests create
    */

    public function testCreate(){
        $this->client->request('POST', '/character/create');
        $this->assertJsonResponse($this->client->getResponse());
        
        $this->assertIdentifier();
        $this->defineIdentifier();
    }


    /*
    * Test inexisting identifier
    */

    public function testInexistingIdentifier()
    {
        $this->client->request('GET', 'character/display/5816e16a9be0135693bea3c50312148b3eferror');
        $this->assertError404($this->client->getResponse()->getStatusCode());
    }

    /*
    * Tests index
    */

    public function testIndex() 
    {
        $this->client->request('GET', '/character/index');
        
        $this->assertJsonResponse($this->client->getResponse());
    }

    /*
    * Tests display 
    */

    public function testDisplay(){
        $this->client->request('GET', '/character/display/' . self::$identifier);
        $this->assertJsonResponse($this->client->getResponse());
        $this->assertIdentifier();
    }

    /*
    * Tests modify 
    */

    public function testModify(){
        $this->client->request('PUT', '/character/modify/' . self::$identifier);
        $this->assertJsonResponse($this->client->getResponse());
        $this->assertIdentifier();
    }

    /*
    * Tests delete 
    */

    public function testDelete(){

        $this->client->request('DELETE', '/character/delete/' . self::$identifier);

        $this->assertJsonResponse();
    }

    

    /*
    * Asserts that a response is in json
    */

    public function assertJsonResponse(){
        $response = $this->client->getResponse();
        $this->content = json_decode($response->getContent(), true, 50);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
    }

    /**
     * Defines identifier
     */

    public function defineIdentifier(){
        self::$identifier = $this->content['identifier'];
    }

    /**
     * Assert identifier
     */

   public function assertIdentifier(){
       $this->assertArrayHasKey('identifier', $this->content);
   }


}