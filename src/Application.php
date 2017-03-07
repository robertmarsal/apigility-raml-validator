<?php
declare(strict_types = 1);

namespace ApigilityRamlValidator;

use League\CLImate\CLImate;
use Raml\Parser as RamlParser;

class Application
{
    private $spec;
    private $project;
    private $messages = [];

    public function __construct(string $spec, string $project)
    {
        $this->spec    = $spec;
        $this->project = $project;
    }

    public function run(CLImate $climate)
    {
        $ramlParser = new RamlParser();
        $parsedSpec = $ramlParser->parse($this->spec);

        // Run checks
        $this->checkAPIModuleExists($parsedSpec->getTitle());
        $this->checkCurrentVersionExists($parsedSpec->getTitle(), $parsedSpec->getVersion());

        if (count($this->messages)) {
            $climate->red("Apigility implementation doesn't match the RAML specification!");

            foreach ($this->messages as $message) {
                $climate->yellow($message);
            }
        } else {
            $climate->green('Congratulations! Your Apigility implementation matches the RAML specification!');
        }
    }

    public function checkApiModuleExists(string $title)
    {
        $modulePath = $this->project . '/module/' . $title;

        if (!is_dir($modulePath)) {
            $this->messages[] = "The $title module does not exist";
        }
    }

    public function checkCurrentVersionExists(string $title, string $version)
    {
        $versionPath = $this->project . '/module/' . $title . '/src/' . ucfirst($version);

        if (!is_dir($versionPath)) {
            $this->messages[] = "No service with version $version found for the $title module";
        }
    }
}
