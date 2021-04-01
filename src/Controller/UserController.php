<?php

    declare(strict_types = 1);

    namespace App\Controller;

    use \App\Dto\UserResponseDtoTransformerInterface;
    use \App\Repository\User\UserRepositoryInterface;
    use \Symfony\Component\HttpFoundation\JsonResponse;
    use \Symfony\Component\HttpFoundation\Request;
    use \Symfony\Component\HttpFoundation\Response;
    use \Symfony\Component\Routing\Annotation\Route;

    class UserController
    extends AbstractApiController {

        private UserRepositoryInterface $repo;

        private UserResponseDtoTransformerInterface $transformer;

        public function __construct(
            UserRepositoryInterface $repo,
            UserResponseDtoTransformerInterface $transformer,
        ) {
            $this->repo        = $repo;
            $this->transformer = $transformer;
        }

        #[ Route( '/api/v1/user', name: 'user-list', methods: [ 'get' ] ) ]
        public function userlist( Request $request ): JsonResponse {
            $dtos = $this->transformer->transformFromObjects( ... $this->repo->take( 10 ) );
            return $this->emitJsonResponse( $dtos );
        }

        #[ Route( '/api/v1/user/{id}', name: 'user', methods: [ 'get' ] ) ]
        public function user( int $id, Request $request ): JsonResponse {

            $instance = $this->repo->findOneById( $id );
            $dto      = $this->transformer->transformFromObject( $instance );

            return $this->emitJsonResponse( $dto );
        }

        #[ Route( '/api/v1/user', name: 'user-create', methods: [ 'post' ] ) ]
        public function create( Request $request ): JsonResponse {
            return new JsonResponse( [] );
        }

        #[ Route( '/api/v1/user/{id}', name: 'user-update', methods: [ 'put', 'patch' ] ) ]
        public function update( Request $request ): JsonResponse {
            return new JsonResponse( [] );
        }

        #[ Route( '/api/v1/user/{id}', name: 'user-delete', methods: [ 'delete' ] ) ]
        public function delete( Request $request ): JsonResponse {
            return new JsonResponse( [] );
        }

    }
