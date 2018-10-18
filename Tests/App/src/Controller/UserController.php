<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repositories\UserRepository;
use Doctrine\StaticWebsiteGenerator\Controller\Response;
use Symfony\Component\HttpFoundation\Request;

class UserController
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request) : Response
    {
        return new Response([
            'controllerData' => 'This data came from the controller',
            'request' => $request,
        ]);
    }

    public function user(string $username) : Response
    {
        $user = $this->userRepository->findOneByUsername($username);

        return new Response(['user' => $user], '/user.html.twig');
    }
}
