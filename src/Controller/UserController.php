<?php

    declare(strict_types = 1);

    namespace App\Controller;

    use \App\Domain\User\UserInputDto;
    use \App\Domain\User\UserPersisterInterface;
    use \App\Domain\User\UserRepositoryInterface;
    use \App\Infrastructure\UserDtoTransformerInterface;
    use \Symfony\Component\HttpFoundation\JsonResponse;
    use \Symfony\Component\HttpFoundation\Request;
    use \Symfony\Component\HttpFoundation\Response;
    use \Symfony\Component\Routing\Annotation\Route;

    class UserController
    extends AbstractApiController {

        protected UserRepositoryInterface $repository;

        protected UserDtoTransformerInterface $transformer;

        protected UserPersisterInterface $persister;

        public function __construct(
            UserRepositoryInterface $repository,
            UserDtoTransformerInterface $transformer,
            UserPersisterInterface $persister
        ) {
            $this->repository  = $repository;
            $this->transformer = $transformer;
            $this->persister   = $persister;
        }

        #[ Route( '/api/v1/user', name: 'user-list', methods: [ 'get' ] ) ]
        public function userlist( Request $request ): JsonResponse {
            $dtos = $this->transformer->transformFromObjects( ... $this->repository->take( 10 ) );
            return $this->emitJsonResponse( $dtos );
        }

        #[ Route( '/api/v1/user/{id}', name: 'user', methods: [ 'get' ] ) ]
        public function user( int $id, Request $request ): JsonResponse {

            $instance = $this->repository->findOneById( $id );
            $dto      = $this->transformer->transformFromObject( $instance );

            return $this->emitJsonResponse( $dto );
        }

        #[ Route( '/api/v1/user', name: 'user-create', methods: [ 'post' ] ) ]
        public function create( Request $request, UserInputDto $userInput ): JsonResponse {

            $userResponseDto = $this->persister->mapAndPersist( $userInput );

            return $this->emitJsonResponse( $userResponseDto );
        }

        #[ Route( '/api/v1/user/{id}', name: 'user-update', methods: [ 'put', 'patch' ] ) ]
        public function update( Request $request ): JsonResponse {
            return new JsonResponse( [] );
        }

        #[ Route( '/api/v1/user/{id}', name: 'user-delete', methods: [ 'delete' ] ) ]
        public function delete( Request $request ): JsonResponse {
            return new JsonResponse( [], Response::HTTP_NO_CONTENT );
        }

    }
