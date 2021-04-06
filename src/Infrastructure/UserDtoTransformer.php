<?php

    declare( strict_types = 1 );

    namespace App\Infrastructure;

    use \App\Domain\User\UserResponseDto;

    class UserDtoTransformer
    implements UserDtoTransformerInterface {

        /**
         * {@inheritdoc}
         */
        public function transformFromObject( UserEntityInterface $user ): UserResponseDto {

            return new UserResponseDto(
                $user->getId(), $user->getName(), $user->getLastname(),
            );
        }

        /**
         * {@inheritdoc}
         */
        public function transformFromObjects( UserEntityInterface ...$users ): array {

            return array_map(
                fn( UserEntityInterface $user ) => $this->transformFromObject( $user ),
                $users
            );
        }

    }
