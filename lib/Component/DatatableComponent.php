<?php
namespace ZealByte\Catalog\Component
{
	use Symfony\Component\Form\FormInterface;
	use ZealByte\Catalog\Catalog;
	use ZealByte\Catalog\SpecInterface;
	use ZealByte\Catalog\Form\Extension\DataTable\Event\DataTableFormEvent;
	use ZealByte\Catalog\Form\Extension\DataTable\Event\DataTableProcessFormEvent;
	use ZealByte\Catalog\Form\Extension\DataTable\Event\DataTableRowEvent;
	use ZealByte\Catalog\Form\Extension\DataTable\DataTableResponse;
	use ZealByte\Catalog\ZealByteCatalog;

	class DatatableComponent extends CatalogComponent
	{
		const BLOCK_NAME = 'datatable_component';

		const VIEW = '@Catalog/components.html.twig';

		/**
		 *
		 */
		public function getTable () : DataTableResponse
		{
			return $this->getParameter('table');
		}

		/**
		 *
		 */
		public function hasTable () : bool
		{
			return $this->hasParameter('table');
		}

		/**
		 *
		 */
		public function setTable (DataTableResponse $data) : self
		{
			$this->setParameter('table', $data);

			return $this;
		}

		/**
		 *
		 */
		protected function stat () : void
		{
			if (!$this->hasForm())
				$this->createDataTableForm();
		}

		/**
		 *
		 */
		protected function createDataTableForm () : void
		{
			$form = null;

			$event = new DataTableFormEvent();

			$this->addEventProps($event);
			$this->getEventDispatcher()
				->dispatch(ZealByteCatalog::EVENT_DATATABLE_FORM, $event);

			if ($event->hasForm()) {
				$form = $event->getForm();

				if ($event->hasRequest()) {
					$request = $event->getRequest();
					$form->handleRequest($request);

					if ($form->isSubmitted()) {
						if ($form->isValid()) {
							$this->processFormData($form);
						}
						else {
							$this->doFormError($form);
						}
					}
				}

				$this->setForm($form->createView());
			}
		}

		/**
		 *
		 */
		protected function doFormError (?FormInterface $form = null) : void
		{
			$draw = 0;
			$message = "Not enough data to generate form!";

			if ($form) {
				$message = (string) $form->getErrors(true, false);

				if ($form->has('draw'))
					$draw = (int) $form->get('draw')->getData();
			}

			$this->setTable((new DataTableResponse())
				->setDraw($draw)
				->setMessage($message));
		}

		/**
		 *
		 */
		protected function processFormData (FormInterface $form) : void
		{
			$catalog = null;
			$draw = 0;
			$data = [];

			$event = (new DataTableProcessFormEvent())
				->setForm($form);

			$this->addEventProps($event);
			$this->getEventDispatcher()
				->dispatch(ZealByteCatalog::EVENT_DATATABLE_PROCESS_FORM, $event);

			$draw = ($event->hasDraw()) ? $event->getDraw() : $draw;
			$spec = ($event->hasSpec()) ? $event->getSpec() : null;
			$catalog = ($event->hasCatalog()) ? $event->getCatalog() : null;

			if ($catalog)
				$data = $this->processCatalogData($catalog, $spec);

			$this->setTable((new DataTableResponse())
				->setData($data)
				->setDraw($draw)
				->setRecordsTotal($catalog ? $catalog->getCatalogTotal() : 0)
				->setRecordsFiltered($catalog ? $catalog->getTotal() : 0)
				->setPage($catalog ? $catalog->getPage() : 0)
				->setLength($catalog ? $catalog->getPageSize() : 0)
				->setStart($catalog ? $catalog->getPageStart() : 0)
				->setEnd($catalog ? $catalog->getPageEnd() : 0));
		}

		/**
		 *
		 */
		protected function processCatalogData (Catalog $catalog, ?SpecInterface $spec = null) : array
		{
			$data = [];

			if ($spec) {
				foreach ($catalog as $identifier => $item) {
					$event = (new DataTableRowEvent())
						->setSpec($spec)
						->setCatalogItem($item);

					$this->addEventProps($event);
					$this->getEventDispatcher()
					->dispatch(ZealByteCatalog::EVENT_DATATABLE_ROW, $event);

				if ($event->hasRow())
					array_push($data, $event->getRow());
				}
			}
			else {
				foreach ($catalog as $identifier => $item) {
					array_push($data, $item);
				}
			}

			return $data;
		}

	}
}
