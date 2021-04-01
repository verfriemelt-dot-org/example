<?php

    declare( strict_types = 1 );

    namespace App\Domain\User;

    use \App\Infrastructure\UserEntityInterface;

    interface UserPersisterInterface {

        public function mapAndPersist( UserInputDto $userInputDto, UserEntityInterface $userEntity = null ): UserResponseDto;
    }
