<?php

    declare( strict_types = 1 );

    namespace App\Infrastructure;

    interface UserEntityInterface {

        public function getId(): int;

        public function getName(): string;

        public function getLastname(): string;

        public function getPassword(): ?string;

        public function setId( int $id ): static;

        public function setName( string $name ): static;

        public function setLastname( string $lastname ): static;

        public function setPassword( ?string $password ): static;
    }
