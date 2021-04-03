<?php

    declare( strict_types = 1 );

    namespace App;

    use \RuntimeException;
    use \Symfony\Component\Validator\ConstraintViolationListInterface;

    class DtoValidationException
    extends RuntimeException {

        /** @phpstan-ignore-next-line */
        private ConstraintViolationListInterface $validationErrors;

        /** @phpstan-ignore-next-line */
        public function setValidationErrors( ConstraintViolationListInterface $errors ): static {
            $this->validationErrors = $errors;
            return $this;
        }

        /** @phpstan-ignore-next-line */
        public function getValidationErrors(): array {

            $errors = [];
            foreach ( $this->validationErrors as $paramName => $violation ) {
                $errors[] = (string) $violation->getMessage();
            }

            return $errors;
        }

    }
