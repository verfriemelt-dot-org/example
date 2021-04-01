<?php

    declare( strict_types = 1 );

    namespace App\Dto;

    use \App\Dto\UserResponseDto;
    use \App\Dto\UserResponseDtoTransformerInterface;
    use \App\Entity\UserEntity;
    use \App\Entity\UserEntityInterface;

    class UserResponseDtoTransformer
    implements UserResponseDtoTransformerInterface {

        public function transformFromObject( UserEntityInterface $user ): UserResponseDto {

            return new UserResponseDto(
                $user->getId(),
                $user->getName(),
                $user->getLastname(),
            );
        }

        /**
         *
         * @param UserEntityInterface $users
         * @return UserResponseDto[]
         */
        public function transformFromObjects( UserEntityInterface ...$users ): array {
            return array_map( fn( UserEntity $user ) => $this->transformFromObject( $user ), $users );
        }

    }
