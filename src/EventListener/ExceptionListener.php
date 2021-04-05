<?php

    declare( strict_types = 1);

    namespace App\EventListener;

    use \App\Domain\User\InvalidUserException;
    use \App\DtoValidationException;
    use \Symfony\Component\HttpFoundation\JsonResponse;
    use \Symfony\Component\HttpFoundation\Response;
    use \Symfony\Component\HttpKernel\Event\ExceptionEvent;
    use \Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
    use \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
    use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
    use \Symfony\Component\HttpKernel\KernelInterface;
    use \Symfony\Component\Serializer\Exception\NotEncodableValueException;
    use \Throwable;

    class ExceptionListener {

        private KernelInterface $kernel;

        /**
         * used to map Domainspecific Exceptions to HttpExceptions
         */
        const MAPPING = [
            InvalidUserException::class       => NotFoundHttpException::class,
            NotEncodableValueException::class => BadRequestHttpException::class,
            DtoValidationException::class => BadRequestHttpException::class,
        ];

        public function __construct( KernelInterface $kernel ) {
            $this->kernel = $kernel;
        }

        public function mapExceptions( Throwable $exception ): Throwable {

            if ( !isset( self::MAPPING[$exception::class] ) ) {
                return $exception;
            }

            $mappedException = self::MAPPING[$exception::class];

            return new $mappedException( $exception->getMessage() );
        }

        public function onKernelException( ExceptionEvent $event ): void {

            $originalException = $event->getThrowable();
            $mappedException = $this->mapExceptions( $originalException );

            $statusCode = $mappedException instanceof HttpExceptionInterface ? $mappedException->getStatusCode()
                    : Response::HTTP_INTERNAL_SERVER_ERROR;

            $payload = [
                'error' => [
                    'code'    => $mappedException->getCode(),
                    'message' => $mappedException->getMessage(),
                ],
            ];

            if ( $originalException instanceof DtoValidationException ) {
                $payload['error']['validationErrors'] = $originalException->getValidationErrors();
            } else {

                // print stracktrace only during dev
                if ( $this->kernel->getEnvironment() === 'dev' ) {
                    $payload['error']['trace'] = $originalException->getTrace();
                }
            }

            $response = new JsonResponse( $payload, $statusCode );
            $response->headers->set( 'Content-Type', 'application/problem+json' );

            $event->setResponse( $response );
        }

    }
