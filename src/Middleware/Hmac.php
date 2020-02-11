<?php

declare (strict_types=1);

namespace Miinto\ApiClient\Middleware;

use \Psr\Http\Message\RequestInterface;

class Hmac implements MiddlewareInterface
{
    CONST AUTH_TYPE = 'MNT-HMAC-SHA256-1-0';
    CONST AUTH_CONTROL_VERSION = '1.0';
    CONST AUTH_CONTROL_FLAVOUR = 'Miinto-Generic';
    CONST AUTH_ALGORITHM = 'sha256';

    /** @var string */
    protected $channelId;

    /** @var string */
    protected $token;

    /**
     * Hmac constructor.
     *
     * @param string $channelId
     * @param string $token
     */
    public function __construct(string $channelId, string $token)
    {
        $this->channelId = $channelId;
        $this->token = $token;
    }

    /**
     * @param RequestInterface $request
     *
     * @return RequestInterface
     */
    public function process(RequestInterface $request): RequestInterface
    {
        $timestamp = $this->getTime();
        $seed = $this->getRandomSeed();

        // Generate request signature
        $signature = $this->generateSignature(
            $request,
            $this->channelId,
            $this->token,
            (string)$timestamp,
            (string)$seed
        );

        // Add Miinto's headers
        return $request
            ->withAddedHeader('Miinto-Api-Control-Flavour', static::AUTH_CONTROL_FLAVOUR)
            ->withAddedHeader('Miinto-Api-Control-Version', static::AUTH_CONTROL_VERSION)
            ->withAddedHeader('Miinto-Api-Auth-ID', $this->channelId)
            ->withAddedHeader('Miinto-Api-Auth-Signature', $signature)
            ->withAddedHeader('Miinto-Api-Auth-Timestamp', $timestamp)
            ->withAddedHeader('Miinto-Api-Auth-Seed', $seed)
            ->withAddedHeader('Miinto-Api-Auth-Type', static::AUTH_TYPE);
    }

    /**
     * @param RequestInterface $request
     *
     * @return string
     */
    protected function signResource(RequestInterface $request): string
    {
        return \hash(
            static::AUTH_ALGORITHM,
            \sprintf(
                "%s\n%s\n%s\n%s",
                $request->getMethod(),
                $request->getUri()->getHost(),
                $request->getUri()->getPath(),
                $request->getUri()->getQuery()
            )
        );
    }

    /**
     * @param string $channelId
     * @param string $timestamp
     * @param string $authSeed
     * @param string $authType
     *
     * @return string
     */
    protected function signHeader(string $channelId, string $timestamp, string $authSeed, string $authType): string
    {
        return \hash(
            static::AUTH_ALGORITHM,
            \sprintf(
                "%s\n%s\n%s\n%s",
                $channelId,
                $timestamp,
                $authSeed,
                $authType
            )
        );
    }

    /**
     * @param RequestInterface $request
     *
     * @return string
     */
    protected function signPayload(RequestInterface $request): string
    {
        return \hash(
            static::AUTH_ALGORITHM,
            $request->getBody()->getContents()
        );
    }

    /**
     * @param RequestInterface $request
     * @param string $channelId
     * @param string $token
     * @param string $timestamp
     * @param string $seed
     * @return string
     */
    public function generateSignature(
        RequestInterface $request,
        string $channelId,
        string $token,
        string $timestamp,
        string $seed
    ): string {
        return \hash_hmac(
            static::AUTH_ALGORITHM,
            \sprintf(
                "%s\n%s\n%s",
                $this->signResource($request),
                $this->signHeader($channelId, $timestamp, $seed, static::AUTH_TYPE),
                $this->signPayload($request)
            ),
            $token
        );
    }

    /**
     * @return int
     */
    protected function getRandomSeed(): int
    {
        return \rand(0, 100);
    }

    /**
     * @return int
     */
    protected function getTime(): int
    {
        return \time();
    }

}