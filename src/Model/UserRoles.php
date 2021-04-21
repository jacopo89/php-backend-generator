<?php


namespace BackendGenerator\Bundle\BackendGeneratorBundle\Model;


use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;

class UserRoles
{
    const ADMIN = 'ROLE_ADMIN';
    const USER = 'ROLE_USER';
    const GUEST = AuthenticatedVoter::IS_AUTHENTICATED_ANONYMOUSLY;
    const AUTHENTICATED = AuthenticatedVoter::IS_AUTHENTICATED_FULLY;
    const API = 'ROLE_API';

}