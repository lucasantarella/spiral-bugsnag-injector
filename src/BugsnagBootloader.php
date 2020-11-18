<?php
declare(strict_types=1);


namespace BugsnagInjector;

use Bugsnag\Client as BugsnagClient;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Core\Container;
use Spiral\Core\Container\SingletonInterface;

final class BugsnagBootloader extends Bootloader implements SingletonInterface
{

	/**
	 * @var ConfiguratorInterface $config
	 */
	private $config;


	/**
	 * @param ConfiguratorInterface $config
	 */
	public function __construct(ConfiguratorInterface $config)
	{
		$this->config = $config;
	}

	/**
	 * Init database config.
	 * @param Container $container
	 */
	public function boot(Container $container): void
	{
		$this->config->setDefaults(
			BugsnagInjector::CONFIG_SECTION,
			[
				'apiKey' => '',
				'endpoint' => 'https://notify.bugsnag.com',
				'releaseStage' => 'development',
				'notifyReleaseStages' => ['production', 'staging'],
				'errorReportingLevel' => E_ALL & ~E_NOTICE,
				'sendCode' => true,
				'projectRoot' => null,
				'metaData' => [],
				'batchSending' => true,
			]
		);

		$container->bindInjector(BugsnagClient::class, BugsnagInjector::class);
	}

}
