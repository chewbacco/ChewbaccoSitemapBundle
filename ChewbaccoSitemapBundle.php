<?php

namespace Chewbacco\SitemapBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Chewbacco\SitemapBundle\DependencyInjection\Compiler\SitemapGeneratorPass;
class ChewbaccoSitemapBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SitemapGeneratorPass());
    }
}
