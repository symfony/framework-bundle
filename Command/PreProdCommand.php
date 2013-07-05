<?php 
/*
 * @Author Belgacem TLILI <belgacem034d@gmail.com>
 * 
 */
namespace Symfony\Bundle\FrameworkBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use  Symfony\Component\Console\Input\ArrayInput;

class PreProdCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('pre:prod')
            ->setDescription('Run both commands assets:install, Assetic:dump and cache:clear ')
            ->setHelp('This command allows you to easily launch assets:install, assetic:dump and cache: clear commands at once, there is an option --env ​to choose the environment')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      // assets install
    	$output->writeln("***** INSTALLING ASSETS *****");
    	$command = $this->getApplication()->find('assets:install');
    	$input = new ArrayInput(array('command' => 'assets:install'));
    	$returnCode = $command->run($input, $output);

    	// assetic:dump    	
        $output->writeln("***** ASSETIC DUMP *****");
        $command = $this->getApplication()->find('assetic:dump');
        $input = new ArrayInput(array('command' => 'assetic:dump'));
        $returnCode = $command->run($input, $output);

        // assetic:dump        
        $output->writeln("***** CLEARING CACHE *****");
        $command = $this->getApplication()->find('cache:clear');
        $input = new ArrayInput(array('command' => 'cache:clear'));
        $returnCode = $command->run($input, $output);
        
        $output->writeln("***** PROD ENV READY *****");
      
        
        
        
    }
}
