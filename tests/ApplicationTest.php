<?php
declare(strict_types = 1);

use ApigilityRamlValidator\Application;
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
}
