<?php

namespace ApiBundle;

use ApiBundle\DependencyInjection\ApiExtension;
use ApiBundle\Command\CreateFeaturesCommand;
use ApiBundle\Command\FixturesCommand;
use ApiBundle\Command\ImportCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * ApiBundle.
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class ApiBundle extends Bundle
{
    /**
     * @inheritdoc
     */
    public function registerCommands(Application $application)
    {
        $application->add(new CreateFeaturesCommand());
        $application->add(new FixturesCommand());
        $application->add(new ImportCommand());
    }

    /**
     * @inheritdoc
     */
    public function getContainerExtension()
    {
        return new ApiExtension();
    }
}
