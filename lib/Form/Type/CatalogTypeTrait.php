<?php
namespace ZealByte\Catalog\Form\Type
{
	use Symfony\Component\Form\FormTypeInterface;
	use ZealByte\Catalog\Inventory\CatalogFactory;
	use ZealByte\Catalog\Inventory\SpecRegistry;

	trait CatalogTypeTrait
	{
		private $catalogFactory;

		private $specRegistry;

		public function __construct (?CatalogFactory $catalog_factory = null, ?SpecRegistry $spec_registry = null)
		{
			if ($catalog_factory)
				$this->setCatalogFactory($catalog_factory);

			if ($spec_registry)
				$this->setSpecRegistry($spec_registry);
		}

		public function setCatalogFactory (CatalogFactory $catalog_factory) : FormTypeInterface
		{
			$this->catalogFactory = $catalog_factory;

			return $this;
		}

		public function setSpecRegistry (SpecRegistry $spec_registry) : FormTypeInterface
		{
			$this->specRegistry = $spec_registry;

			return $this;
		}

		protected function getCatalogFactory () : CatalogFactory
		{
			return $this->catalogFactory;
		}

		protected function getSpecRegistry () : SpecRegistry
		{
			return $this->specRegistry;
		}

		protected function hasCatalogFactory () : bool
		{
			return $this->catalogFactory ? true : false;
		}

		protected function hasSpecRegistry () : bool
		{
			return $this->specRegistry ? true : false;
		}

	}
}
