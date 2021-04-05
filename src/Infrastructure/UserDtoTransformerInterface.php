<?php

    declare( strict_types = 1 );

    namespace App\Infrastructure;

    use \App\Domain\User\UserResponseDto;

    interface UserDtoTransformerInterface {

        /**
         * converts a single UserEntity to a UserResponseDto Instance
         * @param UserEntityInterface $user
         * @return UserResponseDto
         */
        public function transformFromObject( UserEntityInterface $user ): UserResponseDto;

        /**
         * converts an array UserEntities to an array of UserResponseDtos
         * @param UserEntityInterface $users
         * @return UserResponseDto[]
         */
        public function transformFromObjects( UserEntityInterface ... $users ): array;
    }
