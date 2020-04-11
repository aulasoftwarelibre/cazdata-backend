<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use App\Entity\Animal;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Vich\UploaderBundle\Storage\StorageInterface;
use function is_a;
use function is_iterable;

class ResolveAnimalContentUrlSubscriber implements EventSubscriberInterface
{
    private StorageInterface $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function onPreSerialize(ViewEvent $event) : void
    {
        $controllerResult = $event->getControllerResult();
        $request          = $event->getRequest();

        if ($controllerResult instanceof Response || ! $request->attributes->getBoolean('_api_respond', true)) {
            return;
        }

        $attributes = RequestAttributesExtractor::extractAttributes($request);
        if (! $attributes || ! is_a($attributes['resource_class'], Animal::class, true)) {
            return;
        }

        $animals = $controllerResult;

        if (! is_iterable($animals)) {
            $animals = [$animals];
        }

        foreach ($animals as $animal) {
            if (! $animal instanceof Animal) {
                continue;
            }

            $animal->contentUrl = $this->storage->resolveUri($animal, 'imageFile');
        }
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents() : array
    {
        return [
            KernelEvents::VIEW => ['onPreSerialize', EventPriorities::PRE_SERIALIZE],
        ];
    }
}
