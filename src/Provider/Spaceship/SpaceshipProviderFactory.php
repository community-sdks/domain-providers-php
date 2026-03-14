<?php
declare(strict_types=1);

namespace DomainProviders\Provider\Spaceship;

use CommunitySDKs\Spaceship\Client;
use CommunitySDKs\Spaceship\Config\Config;
use CommunitySDKs\Spaceship\Config\Environment;

final class SpaceshipProviderFactory
{
    public static function fromConfig(SpaceshipConfig $config): SpaceshipProvider
    {
        $spaceshipConfig = strtolower($config->environment) === 'sandbox'
            ? Config::sandbox($config->apiKey, $config->apiSecret)
            : Config::production($config->apiKey, $config->apiSecret);

        return new SpaceshipProvider(new Client($spaceshipConfig), $config);
    }
}
