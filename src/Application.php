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
        $this->checkEndpoints($parsedSpec->getTitle(), $parsedSpec->getResources());

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

    public function checkCurrentVersionExists(string $title, $version)
    {
        $versionPath = $this->project . '/module/' . $title . '/src/' . ucfirst($version);

        if (!is_dir($versionPath)) {
            $this->messages[] = "No service with version $version found for the $title module";
        }
    }

    public function checkEndpoints(string $title, array $resources)
    {
        $moduleConfig = include $this->project . '/module/' . $title . '/config/module.config.php';
        $routes = $moduleConfig['router']['routes'];

        foreach ($resources as $resource) {

            $found = false;
            // Check if the resource exists
            foreach ($routes as $route) {
                if (preg_match('/^' . preg_quote($resource->getUri(), '/') . '/', $route['options']['route'])) {
                    $found = true;

                    // Check all methods defined for the endpoint are implemented
                    $this->checkEndpointsMethods($route, $moduleConfig, $resource, true);
                }

                //@TODO nested resources
            }

            if (!$found) {
                $this->messages[] = 'Endpoint ' . $resource->getDisplayName() . ' not found';
            }
        }
    }

    public function checkEndpointsMethods(array $route, array $moduleConfig, $resource, $topResource = true)
    {
        // Extract controller from the route
        $controller = $route['options']['defaults']['controller'];

        // We assume the top resource is a collection method and
        // sub resources will be entity.
        $httpMethodsKey = $topResource
            ? 'collection_http_methods'
            : 'entity_http_methods';

        // Check specification methods match the implementation
        $definedMethods = array_values(
            $moduleConfig['zf-rest'][$controller][$httpMethodsKey]
        );

        $specifiedMethods = array_keys($resource->getMethods());

        $difference = array_diff($definedMethods, $specifiedMethods);

        if ($difference) {
            $this->messages[] = 'Missing methods for ' . $resource->getDisplayName(). ' resource!';
            $this->messages[] = '  Expected ' . json_encode($specifiedMethods);
            $this->messages[] = '  Implemented ' . json_encode($definedMethods);
        }
    }
}
