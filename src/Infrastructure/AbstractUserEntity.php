<?php

    declare(strict_types = 1);

    namespace App\Infrastructure;


    abstract class AbstractUserEntity
    implements UserEntityInterface {

        protected int $id;

        protected string $name;

        protected string $lastname;

        protected ?string $password = null;

        public function getId(): int {
            return $this->id;
        }

        public function getName(): string {
            return $this->name;
        }

        public function getLastname(): string {
            return $this->lastname;
        }

        public function getPassword(): ?string {
            return $this->password;
        }

        public function setId( int $id ): static {
            $this->id = $id;
            return $this;
        }

        public function setName( string $name ): static {
            $this->name = $name;
            return $this;
        }

        public function setLastname( string $lastname ): static {
            $this->lastname = $lastname;
            return $this;
        }

        public function setPassword( ?string $password ): static {
            $this->password = $password;
            return $this;
        }

    }
