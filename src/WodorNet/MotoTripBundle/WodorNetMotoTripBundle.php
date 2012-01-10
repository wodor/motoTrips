<?php

namespace WodorNet\MotoTripBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use WodorNet\MotoTripBundle\DependencyInjection\Compiler\MotoTripConfigurationPass;


class WodorNetMotoTripBundle extends Bundle
{


    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new MotoTripConfigurationPass());
    }
}



