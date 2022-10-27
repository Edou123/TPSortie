<?php
namespace App\EventListener;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\User\UserInterface;

#[AsEventListener(
    event: 'lexik_jwt_authentication.handler.authentication_success',
    method: 'onAuthenticationSuccessResponse',
    priority: 10)]
class AuthenticationSuccessListener
{
    private $repo;

    public function __construct( UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event, )
    {
        $data = $event->getData();
        $user = $event->getUser();
        if (!$user instanceof UserInterface) {
            return;
        }
        $username = $user->getUserIdentifier();
        $data['data'] = array(
            'id' => $this->repo->findOneBy(["email" => $username])->getId(),
        );
        $event->setData($data);
    }

}