<?php
use \Silex\WebTestCase;

class WebTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap.php';
        $app['debug'] = true;
        $app['exception_handler']->disable();

        return $app;
    }

    public function testInitialPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue(
            $client->getResponse()->isOK(),
            "The web page response is wrong"
        );
    }

    public function testTrainingPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/training');

        $this->assertTrue(
            $client->getResponse()->isOK(),
            "The web page response is wrong"
        );
        $this->assertRegexp('/Training/', $crawler->filterXpath('//title')->text());
    }

    public function testNewsPage()
    {
        $client = $this->createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/news');

        $this->assertTrue(
            $client->getResponse()->isOK(),
            "The web page response is wrong"
        );
        $this->assertRegexp('/News Archive/', $crawler->filterXpath('//title')->text());
    }

    public function testSingleNewsPage()
    {
        $client = $this->createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/news/20140119-leinster-senior-xc');

        $this->assertTrue(
            $client->getResponse()->isOK(),
            "The web page response is wrong"
        );
        $this->assertRegexp('/Leinster Senior XC/', $crawler->filterXpath('//title')->text());
    }
}
