<?php

    declare( strict_types = 1 );

    namespace App\Dto;

    use \App\Entity\UserEntityInterface;

    interface UserResponseDtoTransformerInterface {

        /**
         * transoforms a single UserEntity to the UserResponseDTO
         * @param UserEntityInterface $user
         * @return UserResponseDto
         */
        public function transformFromObject( UserEntityInterface $user ): UserResponseDto;

        /**
         * transforms an array user UserEntities to UserResponseDTOs
         * @param UserEntityInterface $users
         * @return UserResponseDto[]
         */
        public function transformFromObjects( UserEntityInterface ... $users ): array;
    }
