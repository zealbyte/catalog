<?php
namespace ZealByte\Catalog\Inventory
{
	use Symfony\Component\EventDispatcher\EventDispatcherInterface;
	use ZealByte\Catalog\SpecInterface;
	use ZealByte\Catalog\CatalogBuilderInterface;
	use ZealByte\Catalog\CatalogBuilder;
	use ZealByte\Catalog\FilterInterface;
	use ZealByte\Catalog\Filter;
	use ZealByte\Catalog\CatalogMapperInterface;
	use ZealByte\Catalog\CatalogMapper;
	use ZealByte\Catalog\CatalogDefinitionInterface;
	use ZealByte\Catalog\CatalogDefinition;
	use ZealByte\Catalog\Catalog;
	use ZealByte\Catalog\InventoryRequestInterface;

	class CatalogFactory implements CatalogFactoryInterface
	{
		private $catalogIndex;

		private $eventDispatcher;

		private $builders = [];

		private $definitions = [];

		private $filters = [];


		public function __construct (CatalogIndexInterface $catalog_index, ?EventDispatcherInterface $event_dispatcher = null)
		{
			$this->catalogIndex = $catalog_index;

			if ($event_dispatcher)
				$this->setEventDispatcher($event_dispatcher);
		}

		/**
		 *
		 */
		public function setEventDispatcher (EventDispatcherInterface $event_dispatcher) : self
		{
			$this->eventDispatcher = $event_dispatcher;

			return $this;
		}

		/**
		 *
		 */
		public function createCatalog (SpecInterface $spec, ?InventoryRequestInterface $inventory_request = null) : Catalog
		{
			return new Catalog($this->getDefinition($spec), $inventory_request);
		}

		/**
		 *
		 */
		public function getDefinition (SpecInterface $spec) : CatalogDefinitionInterface
		{
			$class = get_class($spec);

			if (!array_key_exists($class, $this->definitions))
				$this->definitions[$class] = $this->discoverDefinition($spec);

			return $this->definitions[$class];
		}

		/**
		 *
		 */
		public function getFilter (SpecInterface $spec)
		{
			$class = get_class($spec);

			if (!array_key_exists($class, $this->filters))
				$this->filters[$class] = $this->discoverFilter($spec);

			return $this->filters[$class];
		}

		/**
		 *
		 */
		public function getCatalogBuilder (SpecInterface $spec) : CatalogBuilderInterface
		{
			$class = get_class($spec);

			if (!array_key_exists($class, $this->builders))
				$this->builders[$class] = $this->discoverCatalogBuilder($spec);

			return $this->builders[$class];
		}

		/**
		 *
		 */
		private function discoverCatalogBuilder (SpecInterface $spec) : CatalogBuilderInterface
		{
			$builder = new CatalogBuilder();

			$spec->buildCatalogView($builder);

			return $builder;
		}

		/**
		 *
		 */
		private function discoverDefinition (SpecInterface $spec) : CatalogDefinitionInterface
		{
			$mapper = new CatalogMapper();

			$spec->buildCatalogMap($mapper);

			return new CatalogDefinition($this->catalogIndex, $mapper);
		}

		/**
		 *
		 */
		private function discoverFilter (SpecInterface $spec) : FilterInterface
		{
			$builder = $this->getCatalogBuilder($spec);

			$filter = new Filter($builder);

			return $filter;
		}

	}
}
