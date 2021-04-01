<?php

    namespace App\Infrastructure\RandomUser;

    use \App\Domain\User\UserDto;
    use \App\Domain\User\UserPersisterInterface;

    class RandomUserPersister
    implements UserPersisterInterface {

        public function mapAndPersist( UserDto $user ): bool {
            return false;
        }

    }
