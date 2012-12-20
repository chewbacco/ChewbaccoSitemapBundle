<?php
namespace Chewbakka\SitemapBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class SitemapGeneratorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('chewbakka_sitemap.generator_chain')) {
            return;
        }

        $definition = $container->getDefinition('chewbakka_sitemap.generator_chain');

        foreach ($container->findTaggedServiceIds('chewbakka.sitemap_generator') as $id => $attributes) {
            $definition->addMethodCall('addGenerator', array(new Reference($id)));
        }
    }
}
