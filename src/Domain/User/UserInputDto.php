<?php

    declare( strict_types = 1 );

    namespace App\Domain\User;

    use \App\DtoInterface;
    use \Symfony\Component\Validator\Constraints\Length;
    use \Symfony\Component\Validator\Constraints\NotBlank;

    class UserInputDto
    implements DtoInterface {

        #[ NotBlank( message: 'name must not be empty' ) ]
        #[ Length( min: 1, max: 50 ) ]
        private ?string $name;

        #[ NotBlank( message: 'lastname must not be empty') ]
        #[ Length( min: 1, max: 50 ) ]
        private ?string $lastname;

        public function __construct( string $name = null, string $lastname = null) {

            $this->name     = $name;
            $this->lastname = $lastname;
        }

        public function getName(): ?string {
            return $this->name;
        }

        public function getLastname(): ?string {
            return $this->lastname;
        }

    }
