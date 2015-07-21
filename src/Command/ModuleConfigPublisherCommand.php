<?php

namespace Xsanisty\ModuleManager\Command;

use SilexStarter\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ModuleConfigPublisherCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('module:publish-config')
            ->setDescription('Publish module config into application config directory');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}
