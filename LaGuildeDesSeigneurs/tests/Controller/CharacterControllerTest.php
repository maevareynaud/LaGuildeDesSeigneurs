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
        $this->client->request(
            'POST', 
            '/character/create',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'), //server
            '{"kind":"Dame","name":"Eldalótë","surname":"Fleur elfique","caste":"Elfe","knowledge":"Arts","intelligence":120,"life":12,"image":"/images/eldalote.jpg"}');
        $this->assertJsonResponse();
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
    * Tests display by intelligence
    */

    public function testDisplayBadIntelligence(){
        $this->client->request('GET', '/character/display/intelligence/4234');
        $this->assertError404($this->client->getResponse()->getStatusCode());
    }

    /*
    * Tests display by intelligence
    */

    public function testDisplayByIntelligence(){
        $this->client->request('GET', '/character/display/intelligence/42');
        $this->assertJsonResponse($this->client->getResponse());
    }

    /*
    * Tests modify 
    */

    public function testModify(){
        //Test with partial data array
        $this->client->request(
            'PUT', 
            '/character/modify/' . self::$identifier,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"kind":"Dame","name":"Eldalóta"}'
        );
        $this->assertJsonResponse($this->client->getResponse());
        $this->assertIdentifier();

         //Test with whole content
         $this->client->request(
            'PUT', 
            '/character/modify/' . self::$identifier,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"kind":"Seigneur","name":"Gorthol","surname":"Heaume de terreur","caste":"Chevalier","knowledge":"Diplomatie","intelligence":200,"life":9,"image":"/images/test.jpg"}'
        );
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