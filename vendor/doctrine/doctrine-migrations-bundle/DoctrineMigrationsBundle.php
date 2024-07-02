<?php

declare(strict_types=1);

namespace Doctrine\Bundle\MigrationsBundle;

use Doctrine\Bundle\MigrationsBundle\DependencyInjection\CompilerPass\ConfigureDependencyFactoryPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Bundle.
 */
class DoctrineMigrationsBundle extends Bundle
{
    /** @return void */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigureDependencyFactoryPass());
    }
}
