<?php

namespace Xsanisty\ModuleManager\Command;

use SilexStarter\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ModuleInstallerCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('module:install')
            ->setDescription('Install module into SilexStarter application');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}
