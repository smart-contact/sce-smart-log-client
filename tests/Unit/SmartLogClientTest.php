<?php

namespace SmartContact\SmartLogClient\Tests\Unit;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Exception\ServerException;
use SmartContact\SmartLogClient\SmartLogClient;

class SmartLogClientTest extends TestCase
{
   protected $clientHttp;
   protected $httpResponseMocks;
   protected $httpTransactions;
   protected $fakeApplication;

   function setUp()
   {
      $this->fakeApplication = [
         'id' => 1,
         'name' => 'testing app', 
         'slug' => 'testing-app',
         'shortName' => 'TA'
      ];

      $this->clientHttp = $this->createClientHttp();
   }

   /**
    * Create a GuzzleHttpClient instance with mocked responses.
    *
    * @return Client
    */
   private function createClientHttp()
   {
      $this->httpTransactions = [];
      $history = Middleware::history($this->httpTransactions);

      $this->httpResponseMocks = new MockHandler([]);
      $handlerStack = HandlerStack::create($this->httpResponseMocks);
      $handlerStack->push($history);

      return new Client([
         'handler' => $handlerStack
      ]);
   }

   private function getFakeApplicationAsObject(){
      return (object) $this->fakeApplication;
   }

   /** @test */
   public function itShouldLoadAnApplicationData()
   {
      $this->httpResponseMocks->append(
         new Response(200, ['Content-Type' => 'application/json'], json_encode($this->fakeApplication))
      );

      $smartLogClient = new SmartLogClient($this->clientHttp, 'testing app');


      // check the request
      $this->assertCount(1, $this->httpTransactions);
      $requestURLQuery = $this->httpTransactions[0]['request']->getUri()->getQuery();
      $expectedURLQuery = 'name='. rawurlencode($this->fakeApplication['name']);
      $this->assertContains($expectedURLQuery, $requestURLQuery);

      //check the response
      $this->assertEquals($smartLogClient->getApplication(), $this->getFakeApplicationAsObject());
   }

   /** @test */
   public function itShouldCreateAnApplicationAndReturnItsData()
   {
      //First call, should a 404 code
      $this->httpResponseMocks->append(
         new Response(404, ['Content-Type' => 'application/json'], json_encode(['message' => 'Application not found.']))
      );

      //Secocon call, should return the created app.
      $this->httpResponseMocks->append(
         new Response(200, ['Content-Type' => 'application/json'], json_encode($this->fakeApplication))
      );

      $smartLogClient = new SmartLogClient($this->clientHttp, 'testing app');

      //check only post request (ensure that name was given)
      $this->assertCount(2, $this->httpTransactions);
      $this->assertEquals($this->httpTransactions[1]['request']->getMethod(), 'POST');
      $requestBody = json_decode($this->httpTransactions[1]['request']->getBody());
      $this->assertObjectHasAttribute('name', $requestBody);
      $this->assertEquals($requestBody->name, $this->getFakeApplicationAsObject()->name);


      $this->assertEquals($smartLogClient->getApplication(), (object) $this->fakeApplication);
   }

   /** @test */
   public function itThrowsAnExceptionIfCannotCreateOrGetApplicationInfos()
   {
      $this->expectException(ServerException::class);

      //First call, should a 404 code
      $this->httpResponseMocks->append(
         new Response(404, ['Content-Type' => 'application/json'], json_encode(['message' => 'Application not found.']))
      );

      //Secocon call, should return the created app.
      $this->httpResponseMocks->append(
         new Response(500, ['Content-Type' => 'application/json'], json_encode(['message' => 'Server Error']))
      );

      new SmartLogClient($this->clientHttp, 'testing app');
   }

   /** @test */
   public function shouldPassAsBodyALogInformations()
   {
      // GET api/v1/apps?name=testing app
      $this->httpResponseMocks->append(
         new Response(200, ['Content-Type' => 'application/json'], json_encode($this->fakeApplication))
      );

      // POST api/v1/apps/testing-app/logs
      $this->httpResponseMocks->append(
         new Response(201, ['Content-Type' => 'application/json'], json_encode(['message' => 'Log inserted']))
      );

      $fakeLog = [
         'message' => "I'm a nice log"
      ];

      $smartLogClient = new SmartLogClient($this->clientHttp, 'testing app');
      $smartLogClient->sendLog($fakeLog);

      $this->assertCount(2, $this->httpTransactions);

      $request = $this->httpTransactions[1]['request'];
      $this->assertEquals($request->getMethod(), 'POST');
      
      $requestBody = json_decode($request->getBody());
      $this->assertNotNull($requestBody->incident_code);

      //remove the incident code to test the rest of body
      unset($requestBody->incident_code);
      $this->assertEquals($requestBody, (object)$fakeLog);

   }
}