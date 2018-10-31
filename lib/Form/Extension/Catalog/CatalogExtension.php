<?php
namespace ZealByte\Catalog\Form\Extension\Catalog
{
	use Symfony\Component\Form\AbstractExtension;
	use ZealByte\Catalog\Inventory\CatalogFactoryInterface;
	use ZealByte\Catalog\Inventory\SpecRegistryInterface;
	use ZealByte\Catalog\Form\Extension\Catalog\Type;

	class CatalogExtension extends AbstractExtension
	{
		private $factory;

		private $registry;

		public function __construct (?CatalogFactoryInterface $factory = null, ?SpecRegistryInterface $registry = null)
		{
			if ($factory)
				$this->setFactory($factory);

			if ($registry)
				$this->setRegistry($registry);
		}

		public function setFactory (CatalogFactoryInterface $factory) : self
		{
			$this->factory = $factory;

			return $this;
		}

		public function setRegistry (SpecRegistryInterface $registry) : self
		{
			$this->registry = $registry;

			return $this;
		}

		protected function loadTypes ()
		{
			return [
				new Type\CatalogItemFormType($this->factory, $this->registry),
			];
		}

	}
}
