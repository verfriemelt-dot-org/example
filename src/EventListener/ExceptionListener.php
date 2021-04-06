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

        const EXCEPTION_MAP = [
            InvalidUserException::class       => NotFoundHttpException::class,
            NotEncodableValueException::class => BadRequestHttpException::class,
            DtoValidationException::class     => BadRequestHttpException::class,
        ];

        public function __construct( KernelInterface $kernel ) {
            $this->kernel = $kernel;
        }

        /**
         * Maps out Domainspecific and Internal Exceptions to HttpExceptions
         *
         * @param Throwable $exception
         * @return Throwable
         */
        public function mapExceptions( Throwable $exception ): Throwable {

            if ( !isset( self::EXCEPTION_MAP[$exception::class] ) ) {
                return $exception;
            }

            $mappedException = self::EXCEPTION_MAP[$exception::class];

            return new $mappedException( $exception->getMessage() );
        }

        public function onKernelException( ExceptionEvent $event ): void {

            $originalException = $event->getThrowable();
            $mappedException   = $this->mapExceptions( $originalException );

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
