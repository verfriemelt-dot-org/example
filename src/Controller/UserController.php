<?php

    declare(strict_types = 1);

    namespace App\Controller;

    use \App\Controller\AbstractApiController;
    use \App\Domain\User\UserInputDto;
    use \App\Domain\User\UserResponseDto;
    use \App\Domain\User\UserRepositoryInterface;
    use \App\Infrastructure\UserDtoTransformerInterface;
    use \Symfony\Component\HttpFoundation\JsonResponse;
    use \Symfony\Component\HttpFoundation\Request;
    use \Symfony\Component\HttpFoundation\Response;
    use \Symfony\Component\Routing\Annotation\Route;
    use OpenApi\Annotations as OA;
    use Nelmio\ApiDocBundle\Annotation\Model;

    class UserController
    extends AbstractApiController {

        protected UserRepositoryInterface $repository;

        protected UserDtoTransformerInterface $transformer;

        public function __construct(
            UserRepositoryInterface $repository,
            UserDtoTransformerInterface $transformer,
        ) {
            $this->repository  = $repository;
            $this->transformer = $transformer;
        }

        /**
         * Lists all users
         *
         * @OA\Response(
         *     response=200,
         *     description="Returns all users",
         *     @OA\JsonContent( type="array", @OA\Items(ref=@Model(type=UserResponseDto::class)) )
         * )
         */
        #[ Route( '/api/v1/user', name: 'user-list', methods: [ 'get' ] ) ]
        public function userlist( Request $request ): JsonResponse {
            $dtos = $this->transformer->transformFromObjects( ... $this->repository->all() );
            return $this->emitJsonResponse( $dtos );
        }

        /**
         * Returns a User with the given id.
         *
         * @OA\Parameter(name="id", in="path", @OA\Schema(type="int"))
         * @OA\Response(
         *     response=200,
         *     description="Returns all users",
         *     @OA\JsonContent(
         *        ref=@Model(type=UserResponseDto::class)
         *     )
         * )
         */
        #[ Route( '/api/v1/user/{id}', name: 'user', methods: [ 'get' ] ) ]
        public function user( int $id, Request $request ): JsonResponse {

            $instance = $this->repository->findOneById( $id );
            $dto      = $this->transformer->transformFromObject( $instance );

            return $this->emitJsonResponse( $dto );
        }

        /**
         * Creates a new user
         *
         * @OA\RequestBody(
         *          description="user to create",
         *          required=true,
         *          @OA\JsonContent(ref=@Model(type=UserInputDto::class))
         * )
         * @OA\Response(
         *     response=200,
         *     description="returns the newly created user",
         *     @OA\JsonContent(
         *        ref=@Model(type=UserResponseDto::class)
         *     )
         * )
         */
        #[ Route( '/api/v1/user', name: 'user-create', methods: [ 'post' ] ) ]
        public function create( Request $request, UserInputDto $userInput ): JsonResponse {

            $userEntity = $this->repository->mapAndPersist( $userInput );
            $dto        = $this->transformer->transformFromObject( $userEntity );

            return $this->emitJsonResponse( $dto );
        }

        /**
         * Updates a User with the given id.
         *
         * @OA\Parameter(name="id", in="path", @OA\Schema(type="int"))
         * @OA\Response(
         *      response=200,
         *      description="deletion was successful",
         *     @OA\JsonContent(
         *        type="array",
         *        @OA\Items(ref=@Model(type=UserResponseDto::class))
         *     )
         * )
         * @OA\Response( response=404, description="user not found" )
         */
        #[ Route( '/api/v1/user/{id}', name: 'user-update', methods: [ 'put' ] ) ]
        public function update( int $id, Request $request,
            UserInputDto $userInput ): JsonResponse {

            $instance       = $this->repository->findOneById( $id );
            $updateInstance = $this->repository->mapAndPersist( $userInput,
                $instance );

            return $this->emitJsonResponse( $this->transformer->transformFromObject( $updateInstance ) );
        }

        /**
         * Deletes a User with the given id.
         *
         * @OA\Parameter(name="id", in="path", @OA\Schema(type="int"))
         * @OA\Response( response=204, description="deletion was successful" )
         * @OA\Response( response=404, description="user not found" )
         */
        #[ Route( '/api/v1/user/{id}', name: 'user-delete',
                methods: [ 'delete' ] ) ]
        public function delete( int $id, Request $request ): JsonResponse {

            $instance = $this->repository->findOneById( $id );
            $this->repository->delete( $instance );

            return new JsonResponse( [], Response::HTTP_NO_CONTENT );
        }

    }
