<?php
namespace ZealByte\Catalog\Inventory
{
	use ZealByte\Catalog\SpecInterface;
	use ZealByte\Catalog\InventoryRequestInterface;
	use ZealByte\Catalog\Catalog;

	interface CatalogFactoryInterface
	{
		public function createCatalog (SpecInterface $spec, ?InventoryRequestInterface $inventory_request = null) : Catalog;
	}
}
