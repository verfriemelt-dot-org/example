<?php

    declare( strict_types = 1 );

    namespace App\Domain\User;

    use \App\Infrastructure\UserEntityInterface;

    interface UserRepositoryInterface {

        /**
         * maps the given UserInputDto onto the UserEntity and persists it do disk
         * @param UserInputDto $userDto
         * @param UserEntityInterface $user
         * @return UserEntityInterface
         */
        public function mapAndPersist( UserInputDto $userDto, UserEntityInterface $user = null ): UserEntityInterface;

        /**
         * persists UserEntity into the Repository
         *
         * @param UserEntityInterface $user
         * @return UserEntityInterface
         */
        public function persist( UserEntityInterface $user ): UserEntityInterface;

        /**
         * fetches a UserEntity by Id
         *
         * @param int $int
         * @return UserEntityInterface
         */
        public function findOneById( int $int ): UserEntityInterface;

        /**
         * returns all stored users
         *
         * @return UserEntityInterface[]
         */
        public function all(): array;

        /**
         * removes the given UserEntity from the collection
         *
         * @param UserEntityInterface $user
         * @return bool
         */
        public function delete( UserEntityInterface $user ): bool;

        /**
         * ensures persistance onto disk
         *
         * @return static
         */
        public function flush(): static;
    }
