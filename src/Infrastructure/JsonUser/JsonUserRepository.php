<?php

    declare( strict_types = 1 );

    namespace App\Infrastructure\JsonUser;

    use \App\Domain\User\InvalidUserException;
    use \App\Domain\User\UserInputDto;
    use \App\Domain\User\UserRepositoryInterface;
    use \App\Infrastructure\UserEntityInterface;
    use \RuntimeException;
    use \Symfony\Component\HttpKernel\KernelInterface;
    use \Symfony\Component\Serializer\Encoder\JsonEncoder;
    use \Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
    use \Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
    use \Symfony\Component\Serializer\Serializer;

    class JsonUserRepository
    implements UserRepositoryInterface {

        CONST DEFAULT_STORAGE_PATH = '/data/user.json';

        private string $storagePath;

        private KernelInterface $kernel;

        private Serializer $serializer;

        /** @var JsonUserEntity[] */
        private array $collection = [];

        public function __construct(
            KernelInterface $appKernel,
            string $storagePath = self::DEFAULT_STORAGE_PATH
        ) {

            $this->kernel      = $appKernel;
            $this->storagePath = $storagePath;

            $this->serializer = new Serializer( [ new GetSetMethodNormalizer(), new ArrayDenormalizer() ],
                [ new JsonEncoder() ] );

            $this->loadFromDisk();
        }

        private function loadFromDisk(): void {

            if ( !file_exists( $this->getStoragePath() ) ) {
                return;
            }

            $users = $this->serializer->deserialize(
                file_get_contents( $this->getStoragePath() ),
                'App\Infrastructure\JsonUser\JsonUserEntity[]', 'json'
            );

            // map out userids as key for the collection
            foreach ( $users as $user ) {
                $this->collection[$user->getId()] = $user;
            }
        }

        public function getStoragePath(): string {
            return $this->kernel->getProjectDir() . $this->storagePath;
        }

        public function findOneById( int $id ): UserEntityInterface {

            $user = array_filter( $this->collection,
                fn( JsonUserEntity $u ) => $u->getId() === $id );

            if ( count( $user ) === 0 ) {
                throw new InvalidUserException( 'User Not Found' );
            }

            return current( $user );
        }

        public function all(): array {
            return $this->collection;
        }

        protected function validateInstance( UserEntityInterface $user ): JsonUserEntity {

            if ( !$user instanceof JsonUserEntity ) {
                throw new RuntimeException( sprintf( 'class %s not compatible with JsonUserEntity',
                            $user::class ) );
            }

            return $user;
        }

        public function mapAndPersist( UserInputDto $userDto,
            UserEntityInterface $user = null ): UserEntityInterface {

            if ( $user === null ) {
                $user = new JsonUserEntity();
            } else {
                $this->validateInstance( $user );
            }

            $user->setName( $userDto->getName() );
            $user->setLastname( $userDto->getLastname() );

            $this->persist( $user );

            return $user;
        }

        public function flush(): static {

            file_put_contents(
                $this->getStoragePath(),
                $this->serializer->serialize(
                    $this->collection, 'json'
                )
            );

            return $this;
        }

        public function persist( UserEntityInterface $user ): UserEntityInterface {

            $user = $this->validateInstance( $user );
            $user->getId() === null ? $this->addUser( $user ) : $this->updateUser( $user );

            $this->flush();

            return $user;
        }

        public function getNextUserId(): int {

            if ( count( $this->collection ) === 0 ) {
                return 1;
            }

            return (int) max( array_keys( $this->collection ) ) + 1;
        }

        protected function addUser( JsonUserEntity $user ): JsonUserEntity {
            $user->setId( $this->getNextUserId() );
            $this->collection[$user->getId()] = $user;

            return $user;
        }

        protected function updateUser( JsonUserEntity $user ): JsonUserEntity {

            $this->collection[$user->getId()] = $user;
            return $user;
        }

        public function delete( UserEntityInterface $user ): bool {

            $user = $this->validateInstance( $user );

            if ( $user->getId() === null ) {
                throw new InvalidUserException( 'user not in collection' );
            }

            unset( $this->collection[$user->getId()] );

            $this->flush();

            return true;
        }

    }
