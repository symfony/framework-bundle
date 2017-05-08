<?php 
/*
 * @Author Belgacem TLILI <belgacem034d@gmail.com>
 * 
 */
namespace Alceste\ToolboxBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use  Symfony\Component\Console\Input\ArrayInput;

class ResetDBCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('reset:db')
            ->setDescription('Reset Database Connection, updating schema and loading fixtures: database:drop -> database:create -> schema:update -> fixtures:load')
            ->setHelp('')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	 	// assets install
    	$output->writeln("**************************************************************************************");
    	$command1 = $this->getApplication()->find('doctrine:database:drop');
    	$input = new ArrayInput(array('command' => 'doctrine:database:drop','--force'=>'--force'));
    	$returnCode = $command1->run($input, $output);

    	    	
    	$output->writeln("**************************************************************************************");
    	$command2 = $this->getApplication()->find('doctrine:database:create');
        $input = new ArrayInput(array('command' => 'doctrine:database:create'));
        $returnCode = $command2->run($input, $output);

    	$output->writeln("**************************************************************************************");
        $command3 = $this->getApplication()->find('doctrine:schema:update');
        $arguments = array(
        		'command' => 'doctrine:schema:update',
        		'--force'  => true,
        );
        $input = new ArrayInput($arguments);
        $returnCode = $command3->run($input, $output);
             
    	$output->writeln("**************************************************************************************");
        $command4 = $this->getApplication()->find('doctrine:fixtures:load');
        $command4->addOption('--append');
        $input = new ArrayInput(array('command' => 'doctrine:fixtures:load','--append'=>'--append'));
        $returnCode = $command4->run($input, $output);
        
        
    	$output->writeln("**************************************************************************************");
    	$output->writeln("Database ready");
    	 
        
        
        
    }
}
