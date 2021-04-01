<?php

    declare( strict_types = 1 );

    namespace App\Infrastructure;

    use \App\Domain\User\UserResponseDto;

    interface UserDtoTransformerInterface {

        /**
         * transoforms a single UserEntity to the UserDTO
         * @param UserEntityInterface $user
         * @return UserResponseDto
         */
        public function transformFromObject( UserEntityInterface $user ): UserResponseDto;

        /**
         * transforms an array user UserEntities to UserDTOs
         * @param UserEntityInterface $users
         * @return UserResponseDto[]
         */
        public function transformFromObjects( UserEntityInterface ... $users ): array;
    }
