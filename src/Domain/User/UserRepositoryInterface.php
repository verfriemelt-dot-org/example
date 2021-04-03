<?php

    declare( strict_types = 1 );

    namespace App\Domain\User;

    use \App\Infrastructure\UserEntityInterface;

    interface UserRepositoryInterface {

        public function mapAndPersist( UserInputDto $userDto, UserEntityInterface $user = null ): UserEntityInterface;

        public function persist( UserEntityInterface $user ): UserEntityInterface;

        public function findOneById( int $int ): UserEntityInterface;

        /**
         * returns all stored users
         * @return UserEntityInterface[]
         */
        public function all(): array;

        public function delete( UserEntityInterface $user ): bool;

        public function flush(): static;
    }
