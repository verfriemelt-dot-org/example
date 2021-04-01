<?php

    declare(strict_types = 1);

    namespace App\Controller;

    use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use \Symfony\Component\HttpFoundation\JsonResponse;
    use \Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
    use \Throwable;

    class ExceptionController
    extends AbstractController {

        public function show( Throwable $exception, DebugLoggerInterface $logger = null ) {

            if ( $this->container->getParameter( 'kernel.environment' ) === 'dev' ) {
                throw $exception;
            }

            return new JsonResponse( [
                'error' => 'something failed',
            ] );
        }

    }
