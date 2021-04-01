<?php

    namespace App\Tests;

    use \App\DtoValidationException;
    use \Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

    class UserApiEndointTest
    extends WebTestCase {


        public function testInvalidRequest() {

            $client = static::createClient();
            $uri    = $client->getContainer()->get( 'router' )->generate( 'user-create' );
            $client->request( 'post', $uri, content: null );

            $this->assertFalse( $client->getResponse()->isSuccessful() );
        }

    }
