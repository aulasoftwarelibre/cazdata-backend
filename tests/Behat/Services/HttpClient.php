<?php

declare(strict_types=1);

namespace App\Tests\Behat\Services;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;
use function json_decode;
use function json_encode;

final class HttpClient
{
    private const CONTENT_TYPE = 'application/ld+json';
    private const ACCEPT       = 'application/json';

    private KernelBrowser $client;
    /** @var array<string,string> */
    private array $headers;

    public function __construct(KernelBrowser $client)
    {
        $this->client  = $client;
        $this->headers = [
            'CONTENT_TYPE' => self::CONTENT_TYPE,
            'HTTP_ACCEPT' => self::ACCEPT,
        ];
    }

    public function addHeader(string $key, string $value) : void
    {
        $this->headers[$key] = $value;
    }

    /**
     * @param array<string> $parameters
     */
    public function delete(string $url, array $parameters = []) : void
    {
        $this->client->restart();
        $this->client->request(Request::METHOD_DELETE, $url, $parameters, [], $this->headers);
    }

    /**
     * @param array<string> $parameters
     */
    public function get(string $url, array $parameters = []) : void
    {
        $this->client->restart();
        $this->client->request(Request::METHOD_GET, $url, $parameters, [], $this->headers);
    }

    /**
     * @param array<string> $parameters
     * @param array<string> $content
     */
    public function post(string $url, array $parameters = [], array $content = []) : void
    {
        $this->client->restart();
        $this->client->request(Request::METHOD_POST, $url, $parameters, [], $this->headers, json_encode($content));
    }

    /**
     * @param array<string> $parameters
     * @param array<string> $content
     */
    public function put(string $url, array $parameters = [], array $content = []) : void
    {
        $this->client->restart();
        $this->client->request(Request::METHOD_PUT, $url, $parameters, [], $this->headers, json_encode($content));
    }

    public function response() : Response
    {
        $response = $this->client->getResponse();

        Assert::isInstanceOf($response, Response::class);

        return $response;
    }

    /**
     * @return mixed
     */
    public function content()
    {
        return json_decode($this->response()->getContent());
    }
}
