<?php

    declare( strict_types = 1);

    namespace App\EventListener;

    use \Symfony\Component\HttpFoundation\JsonResponse;
    use \Symfony\Component\HttpFoundation\Response;
    use \Symfony\Component\HttpKernel\Event\ExceptionEvent;
    use \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
    use \Symfony\Component\HttpKernel\KernelInterface;

    class ExceptionListener {

        private KernelInterface $kernel;

        public function __construct( KernelInterface $kernel ) {
            $this->kernel = $kernel;
        }

        public function onKernelException( ExceptionEvent $event ) {

            $exception = $event->getThrowable();

            $statusCode = $exception instanceof HttpExceptionInterface
                ? $exception->getStatusCode()
                : Response::HTTP_INTERNAL_SERVER_ERROR;

            $payload = [
                'error' => [
                    'code'    => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ],
            ];

            // print stracktrace only during dev
            if ( $this->kernel->getEnvironment() === 'dev' ) {
                $payload['error']['trace'] = $exception->getTrace();
            }

            $response = new JsonResponse( $payload, $statusCode );
            $response->headers->set( 'Content-Type', 'application/problem+json' );

            $event->setResponse( $response );
        }

    }
