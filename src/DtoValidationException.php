<?php

    declare( strict_types = 1 );

    namespace App;

    use \RuntimeException;
    use \Symfony\Component\HttpFoundation\Response;
    use \Throwable;

    class DtoValidationException
    extends RuntimeException {

        public int $status;

        public function __construct( string $message = "", int $code = 0, Throwable $previous = null ) {

            $this->status = Response::HTTP_BAD_REQUEST;
            return parent::__construct( $message, $code, $previous );
        }

    }
