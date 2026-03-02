<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\Test;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Tester\ConsoleAssertionsTrait;
use Symfony\Component\Console\Tester\ExecutionResult;

trait ConsoleCommandAssertionsTrait
{
    use ConsoleAssertionsTrait;

    /**
     * Runs a console command and returns the execution result.
     *
     * @param array                           $input             An array of command arguments and options
     * @param string[]                        $interactiveInputs An array of strings representing each input passed to the command input stream
     * @param array<\Closure(string): string> $normalizers
     */
    public static function runCommand(string $name, array $input = [], array $interactiveInputs = [], ?bool $interactive = null, ?bool $decorated = null, ?int $verbosity = null, array $normalizers = []): ExecutionResult
    {
        $application = new Application(static::getContainer()->get('kernel'));
        $command = $application->find($name);
        $commandTester = new CommandTester($command);

        return $commandTester->run($input, $interactiveInputs, $interactive, $decorated, $verbosity, $normalizers);
    }
}
