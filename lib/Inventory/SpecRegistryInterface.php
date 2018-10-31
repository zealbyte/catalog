<?php
namespace ZealByte\Catalog\Inventory
{
	use ZealByte\Catalog\SpecInterface;

	interface SpecRegistryInterface
	{
		public function getSpec (string $alias, string $category) : SpecInterface;
	}
}
