<?php

    namespace App\Tests;

    use \Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

    class UserApiSmokeTest
    extends WebTestCase {

        /**
         * smoketesting endpoints
         *
         * @dataProvider endpoints
         */
        public function testEndpoints( string $method, string $url ) {

            $client = static::createClient();
            $client->request( $method, $url );

            $this->assertTrue( $client->getResponse()->isSuccessful() );
        }

        public function endpoints() {

            yield [ 'GET', '/api/v1/user' ];
            yield [ 'GET', '/api/v1/user/1' ];

            yield [ 'POST', '/api/v1/user' ];
            yield [ 'PATCH', '/api/v1/user/1' ];
            yield [ 'PUT', '/api/v1/user/1' ];

            yield [ 'DELETE', '/api/v1/user/1' ];
        }

    }
