<?php
namespace ZealByte\Catalog\Inventory
{
	use InvalidArgumentException;
	use ZealByte\Catalog\SpecInterface;

	class SpecRegistry implements SpecRegistryInterface
	{
		private $catalogIndex;

		private $inventory = [];

		private $specs = [];

		public function __construct (CatalogIndexInterface $catalog_index)
		{
			$this->catalogIndex = $catalog_index;
		}

		public function addSpec (SpecInterface $spec, string $alias, string $category, array $attributes = []) : self
		{
			if ($this->hasSpec($alias, $category))
				throw new InvalidArgumentException("Catalog spec $alias already defined in category $category.");

			$this->addSpecToRegistry($spec, $alias, $category, $attributes);

			return $this;
		}

		public function getCategories () : array
		{
			return array_keys[$this->inventory];
		}

		public function hasCategory (string $category) : bool
		{
			return array_key_exists($category, $this->inventory);
		}

		public function getSpecAliases (?string $category = null) : array
		{
			if ($category && !$this->hasCategory($category))
				throw new InvalidArgumentException("Catalog category $category has not been defined.");

			if ($category)
				return array_keys($this->inventory[$category]);
		}

		public function getSpec (string $alias, string $category) : SpecInterface
		{
			if (!$this->hasSpec($alias, $category))
				throw new InvalidArgumentException("Catalog spec $alias was not found in category $category.");

			$specId = $this->inventory[$category][$alias]['spec_id'];

			return $this->specs[$specId];
		}

		public function getSpecAttributes (string $alias, string $category)
		{
			if (!$this->hasSpec($alias, $category))
				throw new InvalidArgumentException("Catalog spec $alias was not found in category $category.");

			return $this->inventory[$category][$alias];
		}

		public function hasSpec (string $alias, string $category) : bool
		{
			if (array_key_exists($category, $this->inventory))
				if (array_key_exists($alias, $this->inventory[$category]))
					return true;

			return false;
		}

		public function getSpecs () : array
		{
			return array_values($this->specs);
		}

		private function addSpecToRegistry (SpecInterface $spec, string $alias, string $category, array $attributes) : void
		{
			$specId = "$category.$alias";

			if (!array_key_exists($category, $this->inventory))
				$this->inventory[$category] = [];

			$this->inventory[$category][$alias] = array_merge([
				'spec_id' => $specId,
			], $attributes);

			$this->specs[$specId] = $spec;
		}

	}
}
