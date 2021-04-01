<?php

    declare( strict_types = 1 );

    namespace App\Domain\User;

    interface UserPersisterInterface {

        public function mapAndPersist( UserDto $user ): bool;
    }
