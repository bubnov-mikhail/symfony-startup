<?php

namespace App\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersControllerTest extends LoginFormBase
{
    public function testRegistrationExist()
    {
        $crawler = $this->client->request('GET', $this->container->get('router')->generate('_index'));
        $this->assertCount(
                1,
                $crawler->filter('a#registration'),
                'На главной странице, без авторизации ожидалась ссылка на регистрацию'
                );
    }

    public function testRegistrationDuplicate()
    {
        $form = $this->prepareRegistrationForm();
        $form['registrUser[username]'] = 'customer';
        $form['registrUser[name]'] = 'Duplicated Customer';
        $form['registrUser[email]'] = 'customer@kelnik.ru';
        $form['registrUser[plainPassword]'] = 'observer';

        $crawler = $this->client->submit($form);
        $this->assertCount(
                1,
                $crawler->filter('div.alert.alert-danger'),
                'Ожидалась ошибка регистрации'
                );
    }

    public function testRegistrationSuccess()
    {
        $form = $this->prepareRegistrationForm();
        $form['registrUser[username]'] = 'customerNew';
        $form['registrUser[name]'] = 'New Customer';
        $form['registrUser[email]'] = 'customer_new@kelnik.ru';
        $form['registrUser[plainPassword]'] = 'observer';

        $crawler = $this->client->submit($form);
        $this->assertCount(
                0,
                $crawler->filter('div.alert.alert-danger'),
                'Ожидалась успешная регистрация'
                );

        $this->assertGreaterThan(
                0,
                $crawler->filter('div.alert.alert-info')->count(),
                'Ожидалась нотификация об успешной регистрации'
                );

        $this->assertCount(
                1,
                $crawler->filter('a#cabinet'),
                'Ожидалась ссылка на кабинет'
                );

        return $crawler;
    }

    /**
     * @depends testRegistrationSuccess
     */
    public function testLogout(\Symfony\Component\DomCrawler\Crawler $crawler)
    {
        $link = $crawler->filter('a#logout')->eq(0)->link();
        $crawler = $this->client->click($link);

        $this->assertCount(
                1,
                $crawler->filter('a#login'),
                'При выходе из системы ожидалась кнопка входа'
                );

        return $crawler;
    }

    /**
     * @depends testLogout
     */
    public function testLoginFail(\Symfony\Component\DomCrawler\Crawler $crawler)
    {
        $crawler = $this->login('customer_incorrect', 'wrong_password');
        $this->assertCount(
                1,
                $crawler->filter('div.alert.alert-danger'),
                'Ожидалась ошибка авторизации'
                );

        return $crawler;
    }

    /**
     * @depends testLoginFail
     */
    public function testLoginSuccess(\Symfony\Component\DomCrawler\Crawler $crawler)
    {
        $crawler = $this->login();
        $this->assertCount(
                0,
                $crawler->filter('div.alert.alert-danger'),
                'Ожидалась успешная регистрация'
                );

        $this->assertCount(
                1,
                $crawler->filter('a#cabinet'),
                'Ожидалась ссылка на кабинет'
                );
    }

    /**
     * Подготавливает форму регистрации
     * @return form
     */
    private function prepareRegistrationForm()
    {
        $crawler = $this->client->request('GET', $this->container->get('router')->generate('_index'));

        $regLink = $crawler->filter('a#registration')->eq(0)->link();
        $crawler = $this->client->click($regLink);
        $this->assertCount(
                1,
                $crawler->filter('form input#registrUser_username'),
                'Ожидалась форма регистрации'
                );

        $form = $crawler->filter('form')->form();

        return $form;
    }
}
