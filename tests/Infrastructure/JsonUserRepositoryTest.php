<?php

    declare ( strict_types = 1 );

    namespace App\Test\Infrastructure;

    use \App\Domain\User\InvalidUserException;
    use \App\Infrastructure\JsonUser\JsonUserEntity;
    use \App\Infrastructure\JsonUser\JsonUserRepository;
    use \Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
    use \Symfony\Component\HttpKernel\KernelInterface;
    use \Symfony\Component\Serializer\Encoder\JsonEncoder;
    use \Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
    use \Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
    use \Symfony\Component\Serializer\Serializer;

    class JsonUserRepositoryTest
    extends KernelTestCase {

        const STORAGE_PATH = '/data/user.test.json';

        private function fetchStoragePath(): string {
            return self::$container->get( KernelInterface::class )->getProjectDir() . self::STORAGE_PATH;
        }

        public function setUp(): void {

            self::bootKernel();

            $users = [
                (new JsonUserEntity() )->setId( 1 )->setName( 'manfred' )->setLastname( 'testman' ),
                (new JsonUserEntity() )->setId( 2 )->setName( 'ulli' )->setLastname( 'testwoman' ),
            ];

            $serializer = new Serializer( [ new GetSetMethodNormalizer(), new ArrayDenormalizer() ], [ new JsonEncoder() ] );

            $json = $serializer->serialize( $users, 'json' );

            // setup empty storage
            file_put_contents(
                $this->fetchStoragePath(),
                $json
            );

            parent::setUp();
        }

        public function tearDown(): void {

            // remove testdata
            unlink( $this->fetchStoragePath() );

            parent::tearDown();
        }

        public function fetchRepository(): JsonUserRepository {

            return new JsonUserRepository(
                self::$container->get( KernelInterface::class ),
                self::STORAGE_PATH
            );
        }

        public function testInvalidUserExpection() {
            $this->expectExceptionObject( new InvalidUserException( 'User Not Found' ) );
            $this->fetchRepository()->findOneById( 0 );
        }

        public function testRetrieveUser() {

            $this->assertEquals( 1, $this->fetchRepository()->findOneById( 1 )?->getId(), 'first user id should be 1' );
            $this->assertEquals( 2, count( $this->fetchRepository()->all() ), 'two users should be known' );
        }

        public function testCreatingUser() {

            $user = new JsonUserEntity();
            $user->setName( 'firstname' );
            $user->setLastname( 'lastname' );

            $this->fetchRepository()->persist( $user );

            $users = json_decode( file_get_contents( $this->fetchStoragePath() ), associative: true );

            $this->assertEquals( 3, count( $users ), 'we should have 3 users on disk' );

            $this->assertNotNull( $this->fetchRepository()->findOneById( 3 ), 'validate that new user is readable' );
        }

        public function testDeletingUser() {

            $repository = $this->fetchRepository();
            $user       = $repository->findOneById( 1 );

            $repository->delete( $user );

            $users = json_decode( file_get_contents( $this->fetchStoragePath() ), associative: true );

            $this->assertEquals( 1, count( $users ), 'we should have 1 user on disk' );

            $this->expectExceptionObject( new InvalidUserException( 'User Not Found' ) );
            $this->assertNotNull( $this->fetchRepository()->findOneById( 1 ), 'validate that new user is deleted' );
        }

    }
