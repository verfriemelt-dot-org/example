<?php

    declare( strict_types = 1 );

    namespace App\Repository\User;

    use \App\Entity\UserEntity;
    use \App\Entity\UserEntityInterface;

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

        private static int $count = 1;

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

            $user = new UserEntity();

            [$name, $lastname] = $this->pickName();

            $user->setId( $id );
            $user->setName( $name );
            $user->setLastname( $lastname );

            return $user;
        }

        public function take( int $amount = 10, int $offset = 0 ): array {

            return array_map(
                fn( int $id ) => $this->findOneById( $id ),
                range( $offset + 1, $offset + $amount, 1 )
            );
        }

    }
