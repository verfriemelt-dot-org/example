<?php

    declare( strict_types = 1);

    namespace App\EventListener;

    use \App\Domain\User\InvalidUserException;
    use \App\DtoValidationException;
    use \Symfony\Component\HttpFoundation\JsonResponse;
    use \Symfony\Component\HttpFoundation\Response;
    use \Symfony\Component\HttpKernel\Event\ExceptionEvent;
    use \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
    use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
    use \Symfony\Component\HttpKernel\KernelInterface;

    class ExceptionListener {

        private KernelInterface $kernel;

        /**
         * used to map Domainspecific Exceptions to HttpExceptions
         */
        const MAPPING = [
            InvalidUserException::class => NotFoundHttpException::class
        ];

        public function __construct( KernelInterface $kernel ) {
            $this->kernel = $kernel;
        }

        public function mapExceptions( \Throwable $exception ): \Throwable {

            if ( !isset( self::MAPPING[$exception::class] ) ) {
                return $exception;
            }

            $mappedException = self::MAPPING[$exception::class];

            return new $mappedException( $exception->getMessage() );
        }

        public function onKernelException( ExceptionEvent $event ): void {

            $exception = $event->getThrowable();
            $exception = $this->mapExceptions( $exception );

            $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode()
                    : Response::HTTP_INTERNAL_SERVER_ERROR;

            $payload = [
                'error' => [
                    'code'    => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ],
            ];

            if ( $exception instanceof DtoValidationException ) {
                $payload['error']['validationErrors'] = $exception->getValidationErrors();
            } else {

                // print stracktrace only during dev
                if ( $this->kernel->getEnvironment() === 'dev' ) {
                    $payload['error']['trace'] = $exception->getTrace();
                }
            }

            $response = new JsonResponse( $payload, $statusCode );
            $response->headers->set( 'Content-Type', 'application/problem+json' );

            $event->setResponse( $response );
        }

    }
