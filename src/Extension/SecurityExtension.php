<?php
namespace Iterica\NavigationBundle\Extension;

use Iterica\Navigation\Extension\AbstractNavigationExtension;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\ExpressionLanguageProvider;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

class SecurityExtension extends AbstractNavigationExtension
{
    /** @var TokenStorageInterface */
    private TokenStorageInterface $tokenStorage;

    /** @var AuthorizationCheckerInterface */
    private AuthorizationCheckerInterface $authChecker;

    /** @var RoleHierarchyInterface */
    private RoleHierarchyInterface $roleHierarchy;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker,
        RoleHierarchyInterface $roleHierarchy
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authChecker = $authorizationChecker;
        $this->roleHierarchy = $roleHierarchy;
    }

    public function configureExpressionLanguage(ExpressionLanguage $expressionLanguage): void
    {
        $expressionLanguage->registerProvider(new ExpressionLanguageProvider());

        $expressionLanguage->register('is_granted', static function ($attributes, $object = 'null'): string {
            return sprintf('$token && $auth_checker->isGranted(%s, %s)', $attributes, $object);
        }, static function (array $variables, $attributes, $object = null): bool {
            return $variables['token'] && $variables['auth_checker']->isGranted($attributes, $object);
        });
    }

    public function getExpressionContext(): array
    {
        $token = $this->tokenStorage->getToken();

        if ($token !== null) {
            $roleNames = $this->roleHierarchy->getReachableRoleNames($token->getRoleNames());

            return [
                'token' => $token,
                'user' => $token->getUser(),
                'role_names' => $roleNames,
                'auth_checker' => $this->authChecker,
            ];
        }

        return [
            'token' => null
        ];
    }
}
