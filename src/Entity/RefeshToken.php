<?php

namespace App\Entity;

use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken as BaseRefreshToken;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table("refresh_tokens")]
class RefeshToken extends BaseRefreshToken
{
}
