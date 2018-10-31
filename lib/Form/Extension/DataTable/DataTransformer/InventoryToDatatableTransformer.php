<?php
namespace ZealByte\Catalog\Form\Extension\DataTable\DataTransformer
{
	use Symfony\Component\Form\DataTransformerInterface;
	use Symfony\Component\Form\Exception\TransformationFailedException;
	use ZealByte\Catalog\Form\Extension\DataTable\Model;
	use ZealByte\Catalog\Inventory\CatalogFactory;
	use ZealByte\Catalog\InventoryRequestInterface;
	use ZealByte\Catalog\InventoryRequest;
	use ZealByte\Catalog\SpecInterface;

	class InventoryToDatatableTransformer implements DataTransformerInterface
	{
		private $catalogFactory;

		private $spec;

		public function __construct (CatalogFactory $catalog_factory, SpecInterface $spec)
		{
			$this->catalogFactory = $catalog_factory;
			$this->spec = $spec;
		}

		public function transform (/* InventoryRequest */ $inventory_request) /* DataTableModel */
		{
			if (null === $inventory_request)
				$inventory_request = new InventoryRequest();

			if (!$inventory_request instanceof InventoryRequestInterface)
				throw new TransformationFailedException("Expected ".InventoryRequestInterface::class);

			$catalogBuilder = $this->catalogFactory->getCatalogBuilder($this->spec);
			$datatable = new Model\DataTableModel();

			// Set search value
			if ($inventory_request->hasSearchTerms()) {
				$searchTerms = $inventory_request->getSearchTerms();
				$searchString = implode(' ', $searchTerms);

				$search = new Model\DataTableSearchModel();
				$search
					->setRegex(false)
					->setValue($searchString);

				$datatable->setSearch($search);
			}

			// Columns
			if ($catalogBuilder->hasDatums()) {
				foreach ($catalogBuilder->getDatums() as $idx => $datum) {
					if ($datum->hasColumnType()) {
						$columnOptions = $datum->getColumnOptions();
						$datatableColumn = (new Model\DataTableColumnModel())
							->setName($datum->getName())
							->setSearchable(true)
							->setOrderable(true);

						if (isset($columnOptions['searchable']))
							$datatableColumn->setSearchable(($columnOptions['searchable'] ? true : false));

						if (isset($columnOptions['orderable']))
							$datatableColumn->setOrderable(($columnOptions['orderable'] ? true : false));

						$datatable->addColumn($datatableColumn);

						// Set filter values
						if ($datum->hasFilterType() && $inventory_request->hasFilterTerms()) {
							foreach ($datum->getFieldNames() as $fieldName) {
								if ($inventory_request->hasFilterTerm($fieldName)) {
									$filter = new Model\DataTableSearchModel();
									$filter
										->setRegex(false)
										->setValue($inventory_request->getFilterTerm($fieldName));

									$datatableColumn->setSearch($filter);
								}
							}
						}

						// Set order by values
						if ($inventory_request->hasSortTerms()) {
							foreach ($datum->getFieldNames() as $fieldName) {
								if ($inventory_request->hasSortTerm($fieldName)) {
									$order = new Model\DataTableOrderModel();
									$order
										->setColumn($idx)
										->setDir($inventory_request->getSortTerm($fieldName));

									$datatable->addOrder($order);
								}
							}
						}

					}
				}
			}

			return $datatable;
		}

		public function reverseTransform (/* DataTableModel */ $value) /* InventoryRequest */
		{
			if (null === $value)
				$value = new DataTableModel();

			if (!$value instanceof Model\DataTableModel)
				throw new TransformationFailedException("Expected ".DataTableModel::class);

			$catalogBuilder = $this->catalogFactory->getCatalogBuilder($this->spec);
			$inventoryRequest = new InventoryRequest();

			// Add search terms
			$searchIsRegex = $value->getSearch()->getRegex();
			$searchValue = $value->getSearch()->getValue();
			$searchTerms = explode(' ', $searchValue);

			foreach ($searchTerms as $searchTerm) {
				$inventoryRequest->addSearchTerm($searchTerm);
			}

			foreach ($value->getColumns() as $idx => $column)	{
				$datumName = $column->getName();

				if ($catalogBuilder->hasDatum($datumName)) {
					$datum = $catalogBuilder->getDatum($datumName);
					$fieldNames = $datum->getFieldNames();

					// Add search fields
					if ($column->isSearchable())
						foreach ($fieldNames as $fieldName)
							$inventoryRequest->addSearchField($fieldName);

					// Add filter terms
					if ($datum->hasFilterType()) {
						foreach ($fieldNames as $fieldName) {
							$filterIsRegex = $column->getSearch()->getRegex();
							$filterTerm = $column->getSearch()->getValue();

							if (null !== $filterTerm) {
								$inventoryRequest->addFilterTerm($fieldName, $filterTerm);
							}
						}
					}

					// Add sort terms
					if ($column->isOrderable()) {
						foreach ($value->getOrder() as $order) {
							if ($order->getColumn() == $idx) {
								$dir = $order->getDir();

								foreach ($fieldNames as $fieldName) {
									$inventoryRequest->addSortTerm($fieldName, $dir);
								}
							}
						}
					}

				}
			}

			return $inventoryRequest;
		}

	}
}
