<?php

    namespace App\Infrastructure\RandomUser;

    use \App\Domain\User\UserInputDto;
    use \App\Domain\User\UserPersisterInterface;
    use \App\Domain\User\UserResponseDto;
    use \App\Infrastructure\UserEntityInterface;

    class NullUserPersister
    implements UserPersisterInterface {

        public function mapAndPersist( UserInputDto $userInputDto, UserEntityInterface $userEntity = null ): UserResponseDto {
            return new UserResponseDto( 1, $userInputDto->getName(), $userInputDto->getLastname() );
        }

    }
