<?php

    declare( strict_types = 1 );

    namespace App\Domain\User;

    use \App\DtoInterface;
    use \Symfony\Component\Validator\Constraints\NotBlank;

    class UserResponseDto
    implements DtoInterface {

        /**
         * the userid if set
         *
         * @var int|null
         */
        private ?int $id = null;

        /**
         * name of the user
         *
         * @var string
         */
        #[ NotBlank ]
        private string $name;

        /**
         * lastname of the user
         *
         * @var string
         */
        #[ NotBlank ]
        private string $lastname;

        public function __construct( ?int $id, string $name, string $lastname ) {

            $this->id       = $id;
            $this->name     = $name;
            $this->lastname = $lastname;
        }

        public function getId(): ?int {
            return $this->id;
        }

        public function getName(): string {
            return $this->name;
        }

        public function getLastname(): string {
            return $this->lastname;
        }

    }
