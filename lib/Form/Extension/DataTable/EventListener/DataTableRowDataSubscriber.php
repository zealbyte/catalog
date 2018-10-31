<?php
namespace ZealByte\Catalog\Form\Extension\DataTable\EventListener
{
	use ZealByte\Platform\ZealBytePlatform;
	use ZealByte\Catalog\EventListener\CatalogEventSubscriberAbstract;
	use ZealByte\Catalog\Inventory\CatalogFactoryInterface;
	use ZealByte\Catalog\Inventory\CatalogIndexInterface;
	use ZealByte\Catalog\Form\Extension\DataTable\Event\DataTableRowEvent;
	use ZealByte\Catalog\CatalogEvents;
	use ZealByte\Catalog\CatalogItem;
	use ZealByte\Catalog\ZealByteCatalog;

	class DataTableRowDataSubscriber extends CatalogEventSubscriberAbstract
	{
		const ON_DATATABLE_ROW_IDENTIFIER_PRIORITY = ZealBytePlatform::LOW_PRIORITY;

		const ON_DATATABLE_ROW_LABEL_PRIORITY = ZealBytePlatform::LOW_PRIORITY;

		const ON_DATATABLE_ROW_DATUM_PRIORITY = ZealBytePlatform::LOW_PRIORITY;

		/**
		 *
		 */
		public static function getSubscribedEvents ()
		{
			return [
				ZealByteCatalog::EVENT_DATATABLE_ROW => [
					['onDataTableRowIdentifier', self::ON_DATATABLE_ROW_IDENTIFIER_PRIORITY],
					['onDataTableRowLabel', self::ON_DATATABLE_ROW_LABEL_PRIORITY],
					['onDataTableRowDatum', self::ON_DATATABLE_ROW_DATUM_PRIORITY],
				],
			];
		}

		public function __construct (CatalogFactoryInterface $catalog_factory, CatalogIndexInterface $catalog_index)
		{
			$this->setCatalogFactory($catalog_factory);
			$this->setCatalogIndex($catalog_index);
		}

		/**
		 *
		 */
		public function onDataTableRowDatum (DataTableRowEvent $event)
		{
			$item = $event->getCatalogItem();

			if (!$item->isDirty())
				$datums = $this->processCatalogDataDatums($event, $item);
		}

		/**
		 *
		 */
		public function onDataTableRowIdentifier (DataTableRowEvent $event)
		{
			$item = $event->getCatalogItem();

			if ($item->hasIdentifier())
				$identifier = $item->getIdentifier();

			$event->setRowProperty('id', $identifier);
		}

		/**
		 *
		 */
		public function onDataTableRowLabel (DataTableRowEvent $event)
		{
			$item = $event->getCatalogItem();

			if ($item->hasLabel())
				$label = $item->getLabel();

			$event->setRowProperty('label', $label);
		}

		/**
		 *
		 */
		private function processCatalogDataDatums (DataTableRowEvent $event, CatalogItem $item)
		{
			if ($event->hasSpec()) {
				$datums = [];
				$builder = $this->getCatalogFactory()->getCatalogBuilder($event->getSpec());

				foreach ($builder->getDatums() as $datum) {
					if ($datum->hasColumnType()) {
						$fieldValues = [];
						$datumColumnOptions = $datum->getColumnOptions();
						$datumColumnType = $this->getCatalogIndex()->getColumnType($datum->getColumnType());

						foreach ($datum->getFieldNames() as $fieldName)
							$fieldValues[$fieldName] = $item[$fieldName];

						$datums[$datum->getName()] = $datumColumnType->format($fieldValues, $datumColumnOptions);
					}
				}

				$event->setRowProperty('datums', $datums);
			}
		}

	}
}
