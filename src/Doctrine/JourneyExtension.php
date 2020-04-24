<?php

declare(strict_types=1);

namespace App\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Hunter;
use App\Entity\Journey;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;
use function sprintf;

final class JourneyExtension implements QueryCollectionExtensionInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?string $operationName = null) : void
    {
        if ($resourceClass !== Journey::class) {
            return;
        }

        $user = $this->security->getUser();

        if (! $user instanceof Hunter) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s.hunter = :current_user', $rootAlias));
        $queryBuilder->setParameter('current_user', $user->getId());
    }
}
