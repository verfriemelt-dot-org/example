<?php

    namespace App\Tests;

    use \App\Domain\User\UserRepositoryInterface;
    use \Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

    class UserApiSmokeTest
    extends WebTestCase {

        /**
         * smoketesting endpoints
         *
         * @dataProvider endpoints
         */
        public function testEndpoints( string $method, string $url, string $content = null ) {

            $client = static::createClient();
            $client->request( $method, $url, content: $content );

            $this->assertTrue( $client->getResponse()->isSuccessful() );
        }

        public function endpoints() {

            $user = '{"name":"manfred","lastname":"testmann"}';

            yield [ 'GET', '/api/v1/user', null ];
            yield [ 'GET', '/api/v1/user/1', null ];

            yield [ 'POST', '/api/v1/user', $user ];
            yield [ 'PATCH', '/api/v1/user/1', $user ];
            yield [ 'PUT', '/api/v1/user/1', $user ];

            yield [ 'DELETE', '/api/v1/user/1', null ];
        }

    }
