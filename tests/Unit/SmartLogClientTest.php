<?php

namespace SmartContact\SmartLogClient\Tests\Unit;

use SmartContact\SmartLogClient\Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use SmartContact\SmartLogClient\SmartLogClient;

class SmartLogClientTest extends TestCase
{
   protected $clientHttp;
   protected $clientHttpMocks;

   function setUp():void
   {
      $this->clientHttp = $this->createClientHttp();
   }

   /**
    * Create a GuzzleHttpClient instance with mocked responses.
    *
    * @return Client
    */
   private function createClientHttp():Client
   {
      $this->clientHttpMocks = new MockHandler([]);

      return new Client([
         'handler' => HandlerStack::create($this->clientHttpMocks)
      ]);
   }

   public function testItShouldLoadAnApplicationData()
   {
      $expected = [
         'id' => 1,
         'name' => 'testing app', 
         'slug' => 'testing-app',
         'shortName' => 'TA'
      ];

      $this->clientHttpMocks->append(
         new Response(200, ['Content-Type' => 'application/json'], json_encode($expected))
      );

      $smartLogClient = new SmartLogClient($this->clientHttp, 'testing app');
      $this->assertEquals($smartLogClient->getApplication(), (object) $expected);
   }

   public function testItShouldCreateAnApplicationAndReturnItsData()
   {
      $expected = [
         'id' => 1,
         'name' => 'testing app', 
         'slug' => 'testing-app',
         'shortName' => 'TA'
      ];

      //First call, should a 404 code
      $this->clientHttpMocks->append(
         new Response(404, ['Content-Type' => 'application/json'], json_encode(['message' => 'Application not found.']))
      );

      //Secocon call, should return the created app.
      $this->clientHttpMocks->append(
         new Response(200, ['Content-Type' => 'application/json'], json_encode($expected))
      );

      $smartLogClient = new SmartLogClient($this->clientHttp, 'testing app');
      $this->assertEquals($smartLogClient->getApplication(), (object) $expected);
   }

   
}