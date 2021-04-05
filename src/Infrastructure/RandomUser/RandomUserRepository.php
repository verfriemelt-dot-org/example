<?php

    declare( strict_types = 1 );

    namespace App\Infrastructure\RandomUser;

    use \App\Domain\User\UserInputDto;
    use \App\Domain\User\UserRepositoryInterface;
    use \App\Infrastructure\UserEntityInterface;

    class RandomUserRepository
    implements UserRepositoryInterface {

        const NAMES = [
            "Winfred",
            "Liane",
            "Mitzi",
            "Marilou",
            "Kristan",
            "Demetrius",
            "Vickie",
            "Jolie",
            "Kylie",
            "Oda",
        ];

        const LASTNAMES = [
            "Guidry",
            "Pratico",
            "Felten",
            "Recio",
            "Brouillard",
            "Rardin",
            "Leaton",
            "Marksberry",
            "Kan",
            "Cantara",
        ];

        /**
         *
         * @return string[]
         */
        private function pickName(): array {
            return [
                static::NAMES[array_rand( static::NAMES )],
                static::LASTNAMES[array_rand( static::LASTNAMES )]
            ];
        }

        public function findOneById( int $id ): UserEntityInterface {

            $user = new RandomUserEntity();

            [$name, $lastname] = $this->pickName();

            $user->setId( $id );
            $user->setName( $name );
            $user->setLastname( $lastname );

            return $user;
        }

        public function all( int $amount = 10, int $offset = 0 ): array {

            return array_map(
                fn( int $id ) => $this->findOneById( $id ),
                range( $offset + 1, $offset + $amount, 1 )
            );
        }

        public function mapAndPersist( UserInputDto $userDto,
            UserEntityInterface $user = null ): UserEntityInterface {

            $user = new RandomUserEntity();
            $user->setLastname( $userDto->getLastname() );
            $user->setName( $userDto->getName() );
            $user->setId( (int) ceil( rand() * 1000 ) );

            return $user;
        }

        public function persist( UserEntityInterface $user ): UserEntityInterface {
            return $user;
        }

        public function delete( UserEntityInterface $user ): bool {
            return false;
        }

        public function flush(): static {
            return $this;
        }

    }
