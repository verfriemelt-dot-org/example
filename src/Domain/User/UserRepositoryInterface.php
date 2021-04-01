<?php

    declare( strict_types = 1 );

    namespace App\Domain\User;

    use \App\Infrastructure\UserEntityInterface;

    interface UserRepositoryInterface {

        public function findOneById( int $int ): UserEntityInterface;

        /**
         *
         * @param int $amount
         * @param int $offset
         * @return UserEntityInterface[]
         */
        public function take( int $amount = 10, int $offset = 0 ): array;
    }
