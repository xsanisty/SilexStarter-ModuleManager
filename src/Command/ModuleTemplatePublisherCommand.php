<?php

namespace Xsanisty\ModuleManager\Command;

use SilexStarter\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ModuleTemplatePublisherCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('module:publish-template')
            ->setDescription('Publish module template into application template directory');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}
