<?php

declare(strict_types=1);

namespace App\Controller\Backoffice;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class UserController extends EasyAdminController
{
    /**
     * @inheritDoc
     */
    protected function removeEntity($entity) : void
    {
        if (! $entity instanceof User || ! $this->getUser() instanceof User) {
            return;
        }

        if ($entity->getUsername() === $this->getUser()->getUsername()) {
            $this->addFlash('danger', 'No puedes borrar tu propio usuario');

            return;
        }

        parent::removeEntity($entity);
    }
}
