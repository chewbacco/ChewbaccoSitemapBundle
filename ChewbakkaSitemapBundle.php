<?php

namespace Chewbakka\SitemapBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Chewbakka\SitemapBundle\DependencyInjection\Compiler\SitemapGeneratorPass;
class ChewbaccaSitemapBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SitemapGeneratorPass());
    }
}
