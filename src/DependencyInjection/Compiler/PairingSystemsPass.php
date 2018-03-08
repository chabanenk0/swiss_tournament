<?php

namespace App\DependencyInjection\Compiler;

use App\Services\PairingSystemProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class PairingSystemsPass implements CompilerPassInterface
{

    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(PairingSystemProvider::class)) {
            return;
        }

        $pairingSystemProviderDefinition = $container->findDefinition(PairingSystemProvider::class);
        $pairingSystemProviderDefinition->setPublic(true);

        $taggedServices = $container->findTaggedServiceIds('app.pairing_system');

        foreach ($taggedServices as $id => $tags) {
            $pairingSystemProviderDefinition->addMethodCall('addPairingSystem', array(new Reference($id)));
        }
    }
}
