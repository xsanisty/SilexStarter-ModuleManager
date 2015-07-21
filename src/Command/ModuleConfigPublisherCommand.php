<?php

namespace Xsanisty\ModuleManager\Command;

use Exception;
use SilexStarter\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class ModuleConfigPublisherCommand extends Command
{
    protected function configure()
    {
        $this->setName('module:publish-config')
             ->setDescription('Publish module config into application config directory');

        $this->addOption(
            'module',
            null,
            InputOption::VALUE_OPTIONAL,
            'If set, the command will publish configs of specific module'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app            = $this->getSilexStarter();
        $moduleManager  = $app['module'];
        $registeredMod  = $moduleManager->getRegisteredModules();
        $moduleId       = $input->getOption('module');

        $publishedMod   = [];

        if ($moduleId) {
            $publishedMod[] = $moduleId;
        } else {
            foreach ($registeredMod as $mod) {
                $publishedMod[] = $mod->getModuleIdentifier();
            }
        }

        foreach ($publishedMod as $mod) {
            if (isset($registeredMod[$mod])) {
                if ($registeredMod[$mod]->getResources()->config) {
                    try {
                        $output->writeLn('<info>Publishing config of '.$mod.' to '.$app['path.app'].'config/'.$mod.'</info>');
                        $moduleManager->publishConfig($mod);
                    } catch (Exception $e) {
                        $output->writeLn('<error>Error occured while publishing config of "'.$mod.'" module</error>');
                        $output->writeLn('<error>'.$e->getMessage().'</error>');
                    }

                } else {
                    $output->writeLn('<comment>Module "'.$mod.'" has no defined config</comment>');
                }
            } else {
                $output->writeLn('<error>Module "'.$mod.'" is not registered</error>');
            }
        }
    }
}
