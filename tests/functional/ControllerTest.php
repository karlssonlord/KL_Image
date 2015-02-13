<?php

class ControllerTest extends \Codeception\TestCase\Test
{
    /**
     * @var \FunctionalTester
     */
    protected $tester;

    public function test_the_controller_answers_200()
    {
        $this->tester->executeInGuzzle(function (\GuzzleHttp\Client $client) {
                $response = $client->get('/image/index/index/', ['query' => ['w' => '100']]);

                $this->assertEquals(200, $response->getStatusCode());
                $this->assertEquals('http://localhost:8081/image/index/index/?w=100', $response->getEffectiveUrl());

                $headers = $response->getHeaders();
                $this->assertEquals('text/html', $headers['Content-type'][0]);
            });

        $this->tester->executeInGuzzle(function (\GuzzleHttp\Client $client) {
                $response = $client->get('/image/index/index/', ['query' => ['w' => '100']]);

                $this->assertEquals(200, $response->getStatusCode());
                $this->assertEquals('http://localhost:8081/image/index/index/?w=100', $response->getEffectiveUrl());

                $headers = $response->getHeaders();
                $this->assertEquals('text/html', $headers['Content-type'][0]);
            });
    }

    public function test_the_controller_answers_404()
    {
        $this->tester->executeInGuzzle(function (\GuzzleHttp\Client $client) {
                try {
                    $client->get('/image/index/foobar/', ['query' => ['w' => '100']]);
                } catch (Exception $e) {
                    $this->assertEquals(404, $e->getCode());
                }
            });
    }

}