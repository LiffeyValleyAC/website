<?php
use \Silex\WebTestCase;

class WebTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap.php';
        $app['debug'] = true;
        $app['exception_handler']->disable();
        $app['session.test'] = true;
        $app['db'] = new \PDO(
            'sqlite:' . APP_ROOT . 'LVAC.sqlite'
        );
        $app['db']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

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

    public function testHistoryPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/history');

        $this->assertTrue(
            $client->getResponse()->isOK(),
            "The web page response is wrong"
        );
        $this->assertRegexp('/History/', $crawler->filterXpath('//title')->text());
    }

    public function testRecordsPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/records');

        $this->assertTrue(
            $client->getResponse()->isOK(),
            "The web page response is wrong"
        );
        $this->assertRegexp('/Records/', $crawler->filterXpath('//title')->text());
    }

    public function testFunnyOldGamePage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/funny_old_game');

        $this->assertTrue(
            $client->getResponse()->isOK(),
            "The web page response is wrong"
        );
        $this->assertRegexp('/Funny Old Game/', $crawler->filterXpath('//title')->text());
    }

    public function testContactPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/contact');

        $this->assertTrue(
            $client->getResponse()->isOK(),
            "The web page response is wrong"
        );
        $this->assertRegexp('/Contact/', $crawler->filterXpath('//title')->text());
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

    public function testLoginPage()
    {
        $client = $this->createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/login');

        $this->assertTrue(
            $client->getResponse()->isOK(),
            "The web page response is wrong"
        );
        $this->assertRegexp('/Login/', $crawler->filterXpath('//title')->text());
    }

    public function testLoginPageFormWithNothingFilledIn()
    {
        $client = $this->createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Log In')->form();
        $crawler = $client->submit($form);

        $this->assertTrue(
            $client->getResponse()->isOK(),
            "The web page response is wrong"
        );
        $this->assertRegexp('/Login/', $crawler->filterXpath('//title')->text());
        $this->assertRegexp('/Error: /', $crawler->filterXpath("//div[@class and contains(concat(' ',normalize-space(@class),' '), ' alert ')]")->text());
    }

    public function testLoginPageFormWithAnIncorrectUsernameAndPassword()
    {
        $client = $this->createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Log In')->form();
        $form['email'] = 'fake@wrong.fake';
        $form['password'] = 'this is wrong';
        $crawler = $client->submit($form);

        $this->assertTrue(
            $client->getResponse()->isOK(),
            "The web page response is wrong"
        );
        $this->assertRegexp('/Login/', $crawler->filterXpath('//title')->text());
        $this->assertRegexp('/Error: /', $crawler->filterXpath("//div[@class and contains(concat(' ',normalize-space(@class),' '), ' alert ')]")->text());
    }

    public function testLoginPageFormWithGoodCredentials()
    {
        $client = $this->createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Log In')->form();
        $form['email'] = 'fortest@test.fake';
        $form['password'] = '';
        $crawler = $client->submit($form);

        $this->assertTrue(
            $client->getResponse()->isOK(),
            "The web page response is wrong"
        );
        $this->assertRegexp('/Members/', $crawler->filterXpath('//title')->text());
        $this->assertNotRegexp('/Error: /', $crawler->filterXpath("//div")->text());
    }
}
