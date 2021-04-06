<?php

    declare ( strict_types = 1 );

    namespace App\Controller;

    use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use \Symfony\Component\HttpFoundation\JsonResponse;
    use \Symfony\Component\Serializer\Encoder\JsonEncoder;
    use \Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
    use \Symfony\Component\Serializer\Serializer;
    use \Symfony\Component\HttpFoundation\Response;

    abstract class AbstractApiController
    extends AbstractController {

        /**
         * Emits a JsonReponse serialized with a GetSetMethodNormalizer
         *
         * @param mixed $data
         * @param int $statusCode
         * @param JsonResponse $response
         * @return JsonResponse
         */
        protected function emitJsonResponse(
            mixed $data,
            int $statusCode = Response::HTTP_OK,
            JsonResponse $response = null
        ): JsonResponse {

            if ( $response === null ) {
                $response = new JsonResponse();
            }

            $serializer = new Serializer( [ new GetSetMethodNormalizer() ], [ new JsonEncoder() ] );

            $response->setJson( $serializer->serialize( $data, 'json' ) );
            $response->setStatusCode( $statusCode );

            return $response;
        }

    }
