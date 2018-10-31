<?php
namespace ZealByte\Catalog\Form\Extension\DataTable\EventListener
{
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\Form\FormFactoryInterface;
	use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
	use ZealByte\Platform\ZealBytePlatform;
	use ZealByte\Catalog\EventListener\CatalogEventSubscriberAbstract;
	use ZealByte\Catalog\Inventory\CatalogFactoryInterface;
	use ZealByte\Catalog\Inventory\SpecRegistryInterface;
	use ZealByte\Catalog\InventoryRequestInterface;
	use ZealByte\Catalog\InventoryRequest;
	use ZealByte\Catalog\Form\Extension\DataTable\Type\DataTableType;
	use ZealByte\Catalog\Form\Extension\DataTable\Event\DataTableFormEvent;
	use ZealByte\Catalog\Form\Extension\DataTable\Event\DataTableProcessFormEvent;
	use ZealByte\Catalog\SpecInterface;
	use ZealByte\Catalog\ZealByteCatalog;

	class DataTableFormSubscriber extends CatalogEventSubscriberAbstract
	{
		const ON_DATATABLE_FORM_PRIORITY = ZealBytePlatform::LOW_PRIORITY;

		const ON_DATATABLE_PROCESS_FORM_PRIORITY = ZealBytePlatform::LOW_PRIORITY;

		private $formFactory;

		private $urlGenerator;

		/**
		 * {@inheritdoc}
		 */
		public static function getSubscribedEvents ()
		{
			return [
				ZealByteCatalog::EVENT_DATATABLE_FORM => [
					['onDataTableForm', self::ON_DATATABLE_FORM_PRIORITY],
				],
				ZealByteCatalog::EVENT_DATATABLE_PROCESS_FORM => [
					['onDataTableProcessForm', self::ON_DATATABLE_PROCESS_FORM_PRIORITY],
				],
			];
		}

		/**
		 *
		 */
		public function __construct (CatalogFactoryInterface $catalog_factory, FormFactoryInterface $form_factory, ?SpecRegistryInterface $spec_registry = null, ?RequestStack $request_stack = null, ?UrlGeneratorInterface $url_generator = null)
		{
			$this->setCatalogFactory($catalog_factory);
			$this->setFormFactory($form_factory);

			if ($spec_registry)
				$this->setSpecRegistry($spec_registry);

			if ($request_stack)
				$this->setRequestStack($request_stack);

			if ($url_generator)
				$this->setUrlGenerator($url_generator);
		}

		/**
		 *
		 */
		public function hasUrlGenerator () : bool
		{
			return ($this->urlGenerator) ? true : false;
		}

		/**
		 *
		 */
		public function onDataTableForm (DataTableFormEvent $event) : void
		{
			$this->discoverSpecRequestAliasCategory($event);

			$spec = ($event->hasSpec()) ? $event->getSpec() : null;
			$request = ($event->hasRequest()) ? $event->getRequest() : null;
			$alias = ($event->hasAlias()) ? $event->getAlias() : null;
			$category = ($event->hasCategory()) ? $event->getCategory() : null;

			// Create Form
			if ($spec) {
				$form = $this->createForm($spec, $request, $alias, $category);

				$event->setForm($form);
			}
		}

		/**
		 *
		 */
		public function onDataTableProcessForm (DataTableProcessFormEvent $event) : void
		{
			$this->discoverSpecRequestAliasCategory($event);

			$spec = ($event->hasSpec()) ? $event->getSpec() : null;
			$form = ($event->hasForm()) ? $event->getForm() : null;

			if ($form) {
				$draw = $form->has('draw') ? (int) $form->get('draw')->getData() : null;
				$pageSize = $form->has('length') ? (int) $form->get('length')->getData() : null;
				$pageStart = $form->has('start') ? (int) $form->get('start')->getData() : null;
				$targetPage = ($pageSize) ? ceil(($pageStart + 1) / $pageSize) : null;
				$inventoryRequest = $form->getData();

				if ($draw)
					$event->setDraw($draw);

				if ($targetPage)
					$event->setPage($targetPage);

				if ($pageSize)
					$event->setPageSize($pageSize);

				if ($inventoryRequest instanceof InventoryRequestInterface) {
					$event->setInventoryRequest($inventoryRequest);

					if ($spec && $this->hasCatalogFactory()) {
						$catalog = $this->getCatalogFactory()->createCatalog($spec, $inventoryRequest);

						$catalog
							->setPageSize($pageSize)
							->setPage($targetPage);

						$event->setCatalog($catalog);
					}
				}
			}
		}

		/**
		 *
		 */
		public function setFormFactory (FormFactoryInterface $form_factory) : self
		{
			$this->formFactory = $form_factory;

			return $this;
		}

		/**
		 *
		 */
		public function setUrlGenerator (UrlGeneratorInterface $url_generator) : self
		{
			$this->urlGenerator = $url_generator;

			return $this;
		}

		/**
		 *
		 */
		protected function getFormFactory () : FormFactoryInterface
		{
			return $this->formFactory;
		}

		/**
		 *
		 */
		protected function getUrlGenerator () : UrlGeneratorInterface
		{
			return $this->urlGenerator;
		}

		/**
		 *
		 */
		private function createForm (?SpecInterface $spec = null, ?Request $request = null, ?string $alias = null, ?string $category = null)
		{
			$action = $this->generateActionUrl($alias, $category);
			$inventoryRequest = $this->getInventoryRequest($request);

			return $this->getFormFactory()->create(DataTableType::class, $inventoryRequest, [
				'spec' => $spec,
				'action' => $action,
			]);
		}

		/**
		 *
		 */
		private function generateActionUrl (?string $alias = null, ?string $category = null) : ?string
		{
			if ($this->hasUrlGenerator()) {
				return $this->getUrlGenerator()->generate(ZealByteCatalog::ROUTE_INVENTORY, [
					'category' => $category,
					'alias' => $alias,
				]);
			}

			return null;
		}

		/**
		 *
		 */
		private function getInventoryRequest (?Request $request = null) : ?InventoryRequestInterface
		{
			$inventoryRequest = new InventoryRequest();

			return $inventoryRequest;
		}

	}
}
