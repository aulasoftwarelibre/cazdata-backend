<?php

declare(strict_types=1);

namespace App\Tests\Behat\Services;

use Coduo\PHPMatcher\Factory\MatcherFactory;
use Coduo\PHPMatcher\Matcher;
use InvalidArgumentException;
use jblond\Diff;
use jblond\Diff\Renderer\Text\Unified;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;
use function explode;
use function sprintf;
use const PHP_EOL;

final class JsonResponseAsserter
{
    private Matcher $matcher;

    public function __construct()
    {
        $this->matcher = (new MatcherFactory())->createMatcher();
    }

    public function assertResponse(Response $response, int $code, string $expectedContent) : void
    {
        $this->assertHeader($response);
        $this->assertResponseCode($response, $code);
        $this->assertResponseContent($response, $expectedContent);
    }

    public function assertHeader(Response $response) : void
    {
        $responseHeaderBag = $response->headers;

        Assert::contains(
            $contentType = $responseHeaderBag->get('content-type'),
            'application/json',
            sprintf(
                'Expected response content-type header was \'application/json\'. Got: %s',
                $contentType
            )
        );
    }

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function assertResponseCode(Response $response, int $code) : void
    {
        $responseCode = $response->getStatusCode();

        Assert::same($code, $responseCode, sprintf(
            'Expected code %s number("%s"), but %s("%s") received',
            $code,
            Response::$statusTexts[$code],
            $responseCode,
            Response::$statusTexts[$responseCode]
        ));
    }

    public function assertResponseContent(Response $response, string $expectedContent) : void
    {
        $result = $this->matcher->match($response->getContent(), $expectedContent);

        if (! $result) {
            $diff = new Diff(explode(PHP_EOL, $expectedContent), explode(PHP_EOL, $response->getContent()), []);

            throw new InvalidArgumentException($diff->render(new Unified()));
        }
    }
}
