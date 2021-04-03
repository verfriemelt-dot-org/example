<?php

    namespace App\Tests;

    use \Symfony\Bundle\FrameworkBundle\KernelBrowser;
    use \Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

    class UserApiEndointTest
    extends WebTestCase {

        protected function makeRequest( string $endpoint, array $data = null ): KernelBrowser {

            if ( $data !== null ) {
                $content = json_encode( $data );
            } else {
                $content = null;
            }

            $client = static::createClient();

            $uri = $client->getContainer()->get( 'router' )->generate( $endpoint );
            $client->request( 'post', $uri, content: $content );

            return $client;
        }

        public function testPostCompletePayload() {

            $user = [ "name" => 'test', "lastname" => 'lastname' ];

            $client = $this->makeRequest(
                'user-create',
                $user,
            );

            $this->assertTrue( $client->getResponse()->isSuccessful() );
        }

        /**
         *
         * @dataProvider invalidInputProvider
         */
        public function testPostInvalidPayload( $payload, $expectedMessages ) {

            $client = $this->makeRequest(
                'user-create',
                $payload,
            );

            $this->assertFalse( $client->getResponse()->isSuccessful() );
            $this->assertStringContainsString( $expectedMessages, json_decode( $client->getResponse()->getContent() )->error->message );
        }

        public function invalidInputProvider() {
            yield [ null, 'request payload empty' ];
            yield [ [ 'lastname' => 'test' ], 'name must not be empty' ];
            yield [ [ 'name' => 'test' ], 'lastname must not be empty' ];
            yield [ [ 'name' => 'test', "lastname" => null ], 'lastname must not be empty' ];
            yield [ [ 'name' => 'test', "lastname" => "" ], 'lastname must not be empty' ];
            yield [ [ 'name' => 'test', "lastname" => str_repeat( 'a', 100 ) ], '50 characters or less' ];
            yield [ [ 'name' => str_repeat( 'a', 100 ), "lastname" => 'test' ], '50 characters or less' ];
        }

    }
