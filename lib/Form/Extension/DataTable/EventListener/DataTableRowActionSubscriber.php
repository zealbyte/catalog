<?php
namespace ZealByte\Catalog\Form\Extension\DataTable\EventListener
{
	use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
	use Symfony\Component\HttpFoundation\Request;
	use ZealByte\Platform\ZealBytePlatform;
	use ZealByte\Catalog\EventListener\CatalogEventSubscriberAbstract;
	use ZealByte\Catalog\ZealByteCatalog;
	use ZealByte\Catalog\CatalogItem;
	use ZealByte\Catalog\Form\Extension\DataTable\Event\DataTableRowEvent;

	class DataTableRowActionSubscriber extends CatalogEventSubscriberAbstract
	{
		const ON_DATATABLE_ROW_PRIORITY = ZealBytePlatform::REGULAR_PRIORITY;

		private $urlGenerator;

		public static function getSubscribedEvents ()
		{
			return [
				ZealByteCatalog::EVENT_DATATABLE_ROW => [
					['onDataTableRow', self::ON_DATATABLE_ROW_PRIORITY],
				],
			];
		}

		public function __construct (?UrlGeneratorInterface $url_generator = null)
		{
			if ($url_generator)
				$this->setUrlGenerator($url_generator);
		}

		public function onDataTableRow (DataTableRowEvent $event)
		{
			$this->discoverSpecRequestAliasCategory($event);

			$spec = ($event->hasSpec()) ? $event->getSpec() : null;
			$request = ($event->hasRequest()) ? $event->getRequest() : null;
			$alias = ($event->hasAlias()) ? $event->getAlias() : null;
			$category = ($event->hasCategory()) ? $event->getCategory() : null;
			$item = ($event->hasCatalogItem()) ? $event->getCatalogItem() : null;

			$actions = $this->discoverRowActions($item, $request, $alias, $category);

			if ($actions)
				$event->setRowProperty('actions', $actions);
		}

		public function getUrlGenerator () : UrlGeneratorInterface
		{
			if (!$this->hasUrlGenerator())
				throw new \Exception("No URL Generator has been set!");

			return $this->urlGenerator;
		}

		public function hasUrlGenerator () : bool
		{
			return ($this->urlGenerator) ? true : false;
		}

		public function setUrlGenerator (UrlGeneratorInterface $url_generator) : self
		{
			$this->urlGenerator = $url_generator;

			return $this;
		}

		private function discoverRowActions (CatalogItem $item, ?Request $request = null, ?string $alias = null, ?string $category = null) : array
		{
			$viewUrl = $this->getUrlGenerator()->generate(ZealByteCatalog::ROUTE_INVENTORY, [
				'identifier' => $item->getIdentifier(),
				'category' => $category,
				'alias' => $alias,
			]);

			$editUrl = $this->getUrlGenerator()->generate(ZealByteCatalog::ROUTE_INVENTORY, [
				'identifier' => $item->getIdentifier(),
				'category' => $category,
				'alias' => $alias,
			]);

			return [
				'view' => $viewUrl,
				'edit' => $editUrl,
			];
		}

	}
}
