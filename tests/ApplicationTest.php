<?php
declare(strict_types = 1);

use ApigilityRamlValidator\Application;
use League\CLImate\CLImate;
use League\CLImate\Argument\Manager as ArgumentManager;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    public function testConstructStoresTheDependencies()
    {
        $spec    = '/path/test.raml';
        $project = '/path/project';

        $application = new Application($spec, $project);

        $this->assertAttributeEquals($spec, 'spec', $application);
        $this->assertAttributeEquals($project, 'project', $application);
    }

    public function testRunWillExecuteAllTheChecks()
    {
        $spec    = __DIR__ . '/_fixtures/test.raml';
        $project = '/path/project';

        $checks = [
            'checkApiModuleExists',
            'checkCurrentVersionExists',
            'checkEndpoints',
        ];

        $climateMock = $this->createMock(CLImate::class);
        $climateMock->arguments = $this->createMock(ArgumentManager::class);

        $applicationMock =
            $this->getMockBuilder(Application::class)
                 ->setConstructorArgs([$spec, $project])
                 ->setMethods($checks)
                 ->getMock();

        $applicationMock->expects($this->once())
                        ->method('checkApiModuleExists')
                        ->with('Test');

        $applicationMock->expects($this->once())
                        ->method('checkCurrentVersionExists')
                        ->with('Test', 1);

        $applicationMock->expects($this->once())
                        ->method('checkEndpoints')
                        ->with('Test', []);

        $applicationMock->run($climateMock);
    }
}
