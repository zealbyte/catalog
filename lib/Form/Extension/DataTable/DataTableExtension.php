<?php
namespace ZealByte\Catalog\Form\Extension\DataTable
{
	use Symfony\Component\Form\AbstractExtension;
	use Symfony\Component\Translation\TranslatorInterface;
	use ZealByte\Catalog\Inventory\CatalogFactoryInterface;
	use ZealByte\Catalog\Inventory\SpecRegistryInterface;
	use ZealByte\Catalog\Form\Extension\DataTable\Type;

	class DataTableExtension extends AbstractExtension
	{
		private $factory;

		private $registry;

		private $translator;


		public function __construct (?CatalogFactoryInterface $factory = null, ?SpecRegistryInterface $registry = null, ?TranslatorInterface $translator = null)
		{
			if ($factory)
				$this->setFactory($factory);

			if ($registry)
				$this->setRegistry($registry);

			if ($translator)
				$this->setTranslator($translator);
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

		public function setTranslator (TranslatorInterface $translator) : self
		{
			$this->translator = $translator;

			return $this;
		}

		protected function loadTypes ()
		{
			return [
				new Type\DataTableType($this->factory, $this->registry),
				new Type\DataTableColumnType($this->factory, $this->registry),
				(new Type\DataTableColumnCollectionType($this->factory, $this->registry))->setTranslator($this->translator),
				new Type\DataTableFilterType(),
				new Type\DataTableOrderType(),
				new Type\DataTableSearchType(),
			];
		}

	}
}
