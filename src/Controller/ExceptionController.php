<?php

    declare(strict_types = 1);

    namespace App\Controller;

    use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use \Symfony\Component\HttpFoundation\JsonResponse;
    use \Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
    use \Throwable;

    class ExceptionController
    extends AbstractController {

        public function show( Throwable $exception, DebugLoggerInterface $logger = null ): JsonResponse {

            $statusCode = $exception->status ?? 500;

            $payload = [
                'error' => [
                    'code'    => $exception->getCode(),
                    'message' => $exception->getMessage(),
                    'trace'   => $exception->getTrace()
                ],
            ];

            $response = new JsonResponse( $payload, $statusCode );
            $response->headers->set( 'Content-Type', 'application/problem+json' );

            return $response;
        }

    }
