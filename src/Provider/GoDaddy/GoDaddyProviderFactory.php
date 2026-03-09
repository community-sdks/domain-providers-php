<?php
declare(strict_types=1);

namespace DomainProviders\Provider\GoDaddy;

use CommunitySDKs\GoDaddy\Client;
use CommunitySDKs\GoDaddy\Config;

final class GoDaddyProviderFactory
{
    public static function fromConfig(GoDaddyConfig $config): GoDaddyProvider
    {
        $client = new Client(new Config(
            apiKey: $config->apiKey,
            apiSecret: $config->apiSecret,
        ));

        return new GoDaddyProvider(new GoDaddyDomainsSdkAdapter($client), $config);
    }
}
