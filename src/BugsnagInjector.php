<?php


namespace BugsnagInjector;


use Bugsnag\Client as BugsnagClient;
use ReflectionClass;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Core\Container\InjectorInterface;
use Spiral\Core\Container\SingletonInterface;

class BugsnagInjector implements InjectorInterface, SingletonInterface
{

	public const CONFIG_SECTION = 'bugsnag';

	/** @var BugsnagClient $client */
	private $client;

	/**
	 * MongoDBManager constructor.
	 * @param ConfiguratorInterface $config
	 */
	public function __construct(ConfiguratorInterface $config)
	{
		$bugsnagConfig = $config->getConfig(self::CONFIG_SECTION);
		$this->makeBugsnagClient($bugsnagConfig);
	}

	protected function makeBugsnagClient(array $config)
	{
		$this->client = BugsnagClient::make($config['apiKey'], $config['endpoint']);
		$this->client->setReleaseStage($config['releaseStage']);
		$this->client->setNotifyReleaseStages($config['notifyReleaseStages']);
		$this->client->setErrorReportingLevel($config['errorReportingLevel']);
		$this->client->setSendCode($config['sendCode']);
		$this->client->setBatchSending($config['batchSending']);
		if (isset($config['projectRoot']))
			$this->client->setProjectRoot($config['projectRoot']);
		if (isset($config['metaData']))
			$this->client->setMetaData($config['metaData']);
	}

	public function createInjection(ReflectionClass $class, string $context = null)
	{
		return $this->client;
	}

}
