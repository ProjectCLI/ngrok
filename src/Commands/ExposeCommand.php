<?php

/**
 * Plugin for ProjectCLI. More info at
 * https://github.com/chriha/project-cli
 */

namespace ProjectCLI\Ngrok\Commands;

use Chriha\ProjectCLI\Commands\Command;
use Chriha\ProjectCLI\Helpers;
use ProjectCLI\Ngrok\Services\Ngrok;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;

class ExposeCommand extends Command
{

    /** @var string */
    protected static $defaultName = 'expose';

    /** @var string */
    protected $description = 'Expose your project to the internet';


    /**
     * Configure the command by adding a description, arguments and options
     *
     * @return void
     */
    public function configure() : void
    {
        $this->addDynamicArguments()->addDynamicOptions();
    }

    /**
     * Execute the console command.
     *
     * @param Ngrok $ngrok
     * @return void
     */
    public function handle(Ngrok $ngrok) : void
    {
        if ( ! $ngrok->isInstalled()) {
            $this->abort(
                'Ngrok is not installed. Please check ngrok.com for further instructions.'
            );
        }

        $config = Helpers::projectPath('ngrok.yml');

        if (file_exists($config)) {
            // TODO: add ability to specify tunnel from argument
            $params = ['start', "--config={$config}", '--all'];
        } else {
            $port   = $_ENV['APP_SSL_PORT'] ?? ($_ENV['APP_PORT'] ?? 0);
            $params = ['http', $port, '--region=eu'];
        }

        //dd(array_merge(['ngrok'], $params));
        (new Process(array_merge(['ngrok'], $params)))
            ->setTimeout(null)
            ->setTty(true)
            ->run(
                function ($type, $buffer)
                {
                    $this->output->write($buffer);
                }
            );
    }

    /**
     * Make command only available if inside the project
     */
    public static function isActive() : bool
    {
        return PROJECT_IS_INSIDE;
    }

}
