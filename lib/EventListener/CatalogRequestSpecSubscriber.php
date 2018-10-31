<?php
namespace ZealByte\Catalog\EventListener
{
	use Symfony\Component\HttpFoundation\RequestStack;
	use ZealByte\Platform\ZealBytePlatform;
	use ZealByte\Catalog\Inventory\SpecRegistryInterface;
	use ZealByte\Catalog\ZealByteCatalog;

	class CatalogRequestSpecSubscriber extends CatalogEventSubscriberAbstract
	{
		const ON_GET_CATALOG_SPEC_PRIORITY = ZealBytePlatform::LOW_PRIORITY;

		/**
		 * {@inheritdoc}
		 */
		public static function getSubscribedEvents ()
		{
			return [
				ZealByteCatalog::EVENT_CATALOG_REQUEST_SPEC => [
					['onGetCatalogSpec', self::ON_GET_CATALOG_SPEC_PRIORITY],
				],
			];
		}

		public function __construct (?SpecRegistryInterface $spec_registry = null, ?RequestStack $request_stack = null)
		{
			if ($spec_registry)
				$this->setSpecRegistry($spec_registry);

			if ($request_stack)
				$this->setRequestStack($request_stack);
		}

		/**
		 *
		 */
		public function onGetCatalogSpec (CatalogRequestEventInterface $event) : void
		{
			$this->discoverSpecRequestAliasCategory($event);
		}

	}
}
