<?php

    declare( strict_types = 1 );

    namespace App\Infrastructure;

    use \App\Domain\User\UserDto;

    interface UserDtoTransformerInterface {

        /**
         * transoforms a single UserEntity to the UserDTO
         * @param UserEntityInterface $user
         * @return UserDto
         */
        public function transformFromObject( UserEntityInterface $user ): UserDto;

        /**
         * transforms an array user UserEntities to UserDTOs
         * @param UserEntityInterface $users
         * @return UserDto[]
         */
        public function transformFromObjects( UserEntityInterface ... $users ): array;
    }
