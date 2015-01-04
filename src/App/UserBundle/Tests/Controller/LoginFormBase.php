<?php

namespace App\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginFormBase extends WebTestCase
{
    protected $client = null;
    protected $container = null;
    public static $defaultLogin = 'customer';
    public static $defaultPassword = 'observer';

    public function setUp()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
        $this->container = $this->client->getContainer();
    }

    /**
     * Авторизация
     * Не передавайте параметров для авторизации в тестовом аккаунте по умолчанию
     * @return form
     */
    protected function login($username = null, $password = null)
    {
        $crawler = $this->client->request('GET', $this->container->get('router')->generate('_index'));
        $this->assertCount(
                1,
                $crawler->filter('a#login'),
                'На главной странице, без авторизации ожидалась кнопка входа'
                );
        $username = is_null($username) ? self::$defaultLogin : $username;
        $password = is_null($password) ? self::$defaultPassword : $password;
        $form = $this->prepareLoginForm($crawler);
        $form['_username'] = $username;
        $form['_password'] = $password;

        $crawler = $this->client->submit($form);

        return $crawler;
    }

    /**
     * Подготавливает форму авторизации
     * @return form
     */
    protected function prepareLoginForm($crawler)
    {
        $regLink = $crawler->filter('a#login')->eq(0)->link();
        $crawler = $this->client->click($regLink);
        $this->assertCount(
                1,
                $crawler->filter('form input#username'),
                'Ожидалась форма авторизации'
                );

        $form = $crawler->filter('form')->form();

        return $form;
    }
}
