<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context\Api;

use App\Tests\Behat\Services\HttpClient;
use App\Tests\Behat\Services\JsonResponseAsserter;
use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Response;
use function sprintf;

final class AnimalContext implements Context
{
    private HttpClient $client;
    private JsonResponseAsserter $asserter;

    public function __construct(HttpClient $client, JsonResponseAsserter $asserter)
    {
        $this->client   = $client;
        $this->asserter = $asserter;
    }

    /**
     * @When /^I browse the animals$/
     */
    public function iBrowseTheAnimals() : void
    {
        $this->client->get('/api/animals');
    }

    /**
     * @Then /^I should see (\d+) animals in the list$/
     */
    public function iShouldSeeAnimalsInTheList(int $count) : void
    {
        $this->asserter->assertResponse(
            $this->client->response(),
            Response::HTTP_OK,
            sprintf('"@array@.count(%d)"', $count)
        );
    }

    /**
     * @Given /^the animal "([^"]*)" should be in the list$/
     */
    public function theAnimalShouldBeInTheList(string $name) : void
    {
        $expectedContent = sprintf('[@...@,{"name":"%s","@*@":"@*@"},@...@]', $name);
        $this->asserter->assertResponse(
            $this->client->response(),
            Response::HTTP_OK,
            $expectedContent
        );
    }
}
