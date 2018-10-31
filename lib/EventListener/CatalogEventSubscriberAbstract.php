<?php
namespace ZealByte\Catalog\EventListener
{
	use Symfony\Component\HttpFoundation\RequestStack;
	use ZealByte\Catalog\Inventory\CatalogFactoryInterface;
	use ZealByte\Catalog\Inventory\CatalogIndexInterface;
	use ZealByte\Catalog\Inventory\SpecRegistryInterface;
	use ZealByte\Catalog\Event\CatalogRequestEventInterface;
	use ZealByte\Catalog\Event\CatalogEventInterface;

	use ZealByte\Catalog\ZealByteCatalog;

	abstract class CatalogEventSubscriberAbstract implements CatalogSubscriberInterface
	{
		private $catalogFactory;

		private $catalogIndex;

		private $specRegistry;

		private $requestStack;

		/**
		 *
		 */
		public function hasCatalogFactory () : bool
		{
			return ($this->catalogFactory) ? true : false;
		}

		/**
		 *
		 */
		public function hasCatalogIndex () : bool
		{
			return ($this->catalogIndex) ? true : false;
		}

		/**
		 *
		 */
		public function hasSpecRegistry () : bool
		{
			return ($this->specRegistry) ? true : false;
		}

		/**
		 *
		 */
		public function hasRequestStack () : bool
		{
			return ($this->requestStack) ? true : false;
		}

		/**
		 *
		 */
		public function setCatalogFactory (CatalogFactoryInterface $catalog_factory) : CatalogSubscriberInterface
		{
			$this->catalogFactory = $catalog_factory;

			return $this;
		}

		/**
		 *
		 */
		public function setCatalogIndex (CatalogIndexInterface $catalog_index) : CatalogSubscriberInterface
		{
			$this->catalogIndex = $catalog_index;

			return $this;
		}

		/**
		 *
		 */
		public function setSpecRegistry (SpecRegistryInterface $spec_registry) : CatalogSubscriberInterface
		{
			$this->specRegistry = $spec_registry;

			return $this;
		}

		/**
		 *
		 */
		public function setRequestStack (RequestStack $request_stack) : CatalogSubscriberInterface
		{
			$this->requestStack = $request_stack;

			return $this;
		}

		/**
		 *
		 */
		protected function getCatalogFactory () : CatalogFactoryInterface
		{
			return $this->catalogFactory;
		}

		/**
		 *
		 */
		protected function getCatalogIndex () : CatalogIndexInterface
		{
			return $this->catalogIndex;
		}

		/**
		 *
		 */
		protected function getSpecRegistry () : SpecRegistryInterface
		{
			return $this->specRegistry;
		}

		/**
		 *
		 */
		protected function getRequestStack () : RequestStack
		{
			return $this->requestStack;
		}

		/**
		 *
		 */
		protected function discoverSpecRequestAliasCategory (CatalogEventInterface $event) : void
		{
			$request = null;
			$spec = ($event->hasSpec()) ? $event->getSpec() : null;
			$alias = ($event->hasAlias()) ? $event->getAlias() : null;
			$category = ($event->hasCategory()) ? $event->getCategory() : null;

			// Check Request
			if ($event instanceof CatalogRequestEventInterface) {
				$request = ($event->hasRequest()) ? $event->getRequest() : null;

				if (!$request && $this->hasRequestStack()) {
					$request = $this->getRequestStack()->getCurrentRequest();

					if ($request)
						$event->setRequest($request);
				}
			}

			// Check Alias and Category
			if (!$alias && $request) {
				if ($request->has('alias')) {
					$alias = $request->get('alias');
					$event->setAlias($alias);
				}

				if ($request->has('category')) {
					$category = $request->get('category');
					$event->setCategory($category);
				}
			}

			// Check Spec
			if (!$spec && $alias && $this->hasSpecRegistry()) {
				if ($this->getSpecRegistry()->hasSpec($alias, $category)) {
					$spec = $this->getSpecRegistry()->getSpec($alias, $category);
					$event->setSpec($spec);
				}
			}
		}

	}
}
