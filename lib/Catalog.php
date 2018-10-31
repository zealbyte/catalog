<?php
namespace ZealByte\Catalog
{
	use ArrayAccess;
	use Iterator;
	use JsonSerializable;
	use RuntimeException;
	use InvalidArgumentException;
	use Symfony\Component\PropertyAccess\PropertyAccess;
	use ZealByte\Catalog\CatalogDefinitionInterface;
	use ZealByte\Catalog\Inventory\ListBuilderInterface;

	class Catalog implements ArrayAccess, Iterator, JsonSerializable
	{
		private $accessor;

		private $definition;

		private $catalogIndex;

		private $continue;

		private $source;

		private $count;

		private $total;

		private $pageStart;

		private $pageEnd;

		private $row;

		private $findBy;

		private $findIdentifiers = [];

		private $rowIdentifier = [];

		private $catalog = [];

		private $newCatalog = [];


		public function __construct (CatalogDefinitionInterface $definition, ?InventoryRequestInterface $inventory_request = null)
		{
			$this->accessor = PropertyAccess::createPropertyAccessor();
			$this->definition = $definition;
			$this->source = $this->definition->getDataSource();
			$this->catalogIndex = $definition->getCatalogIndex();

			$this->applyDefinitionFields();

			if ($inventory_request)
				$this->applyInventoryRequest($inventory_request);
		}

		/**
		 * {@inheritdoc}
		 */
		public function jsonSerialize ()
		{
			$this->statCatalog();

			return array_values($this->catalog);
		}

		/**
		 * {@inheritdoc}
		 */
		public function current ()
		{
			return $this->catalog[$this->key()];
		}

		/**
		 * {@inheritdoc}
		 */
		public function key ()
		{
			$this->statCatalog();

			return $this->rowIdentifier[$this->row];
		}

		/**
		 * {@inheritdoc}
		 */
		public function next ()
		{
			++$this->row;
		}

		/**
		 * {@inheritdoc}
		 */
		public function rewind ()
		{
			if (!$this->continue)
				$this->row = $this->getPageStart();
			else
				$this->row = 0;
		}

		/**
		 * {@inheritdoc}
		 */
		public function offsetExists ($offset)
		{
			$this->statCatalog();

			return array_key_exists($offset, $this->catalog);
		}

		/**
		 * {@inheritdoc}
		 */
		public function offsetGet ($offset)
		{
			$this->statCatalog();

			if (!array_key_exists($offset, $this->catalog))
				throw new RuntimeException("Catalog item $offset does not exist on the current page!");

			return $this->catalog[$offset];
		}

		/**
		 * {@inheritdoc}
		 */
		public function offsetSet ($offset, $value)
		{
			if (!($value instanceof CatalogItem))
				throw new InvalidArgumentException("Catalog items must be a ".CatalogItem::class." ".gettype($value)." given!");

			$this->catalog[$offset] = $value;
		}

		/**
		 * {@inheritdoc}
		 */
		public function offsetUnset ($offset)
		{
			$this->catalog[$offset] = null;
		}

		/**
		 * {@inheritdoc}
		 */
		public function valid ()
		{
			$start = $this->getPageStart();
			$end = $this->continue ? ($this->getCatalogTotal() - 1) : $this->getPageEnd();

			return ($start <= $this->row) && ($end >= $this->row);
		}

		/**
		 *
		 */
		public function continue (bool $continue = true) : Catalog
		{
			$this->continue = $continue;

			return $this;
		}

		/**
		 *
		 */
		public function getItem () : CatalogItem
		{
			return $this->current();
		}

		/**
		 *
		 */
		public function getCatalogTotal () : int
		{
			if (!$this->total)
				$this->total = $this->source->getTotal();

			return $this->total;
		}

		/**
		 *
		 */
		public function getTotal () : int
		{
			if (!$this->count)
				$this->count = $this->source->getCount();

			return $this->count;
		}

		/**
		 *
		 */
		public function getPage () : int
		{
			$page = $this->source->getPage();

			if (!($page > 0))
				throw new RuntimeException("Data source returned a zealbyte or below page number.");

			return $page;
		}

		/**
		 *
		 */
		public function getPageSize () : int
		{
			$pageSize = $this->source->getPageSize();

			if (!($pageSize > 0))
				throw new RuntimeException("Data source returned a zealbyte or below page size.");

			return $pageSize;
		}

		/**
		 *
		 */
		public function getPageStart () : int
		{
			if (!$this->pageStart)
				$this->calcPage();

			return $this->pageStart;
		}

		/**
		 *
		 */
		public function getPageEnd () : int
		{
			if (!$this->pageEnd)
				$this->calcPage();

			return $this->pageEnd;
		}

		/**
		 *
		 */
		public function setPage (int $page) : Catalog
		{
			if (!($page > 0))
				throw new RuntimeException("Attempted to set a zealbyte or below page number.");

			$this->source->setPage($page);
			$this->calcPage();

			$this->row = $this->getPageStart();

			return $this;
		}

		/**
		 *
		 */
		public function setPageSize (int $page_size) : Catalog
		{
			if (!($page_size > 0))
				throw new RuntimeException("Attempted to set a zealbyte or below page size.");

			$this->source->setPageSize($page_size);
			$this->calcPage();

			$this->row = $this->getPageStart();

			return $this;
		}

		public function new (?string $identifier = null, $data = null) : CatalogItemInterface
		{
			$item = new CatalogItem($this->definition, $identifier, $data, false);

			$this->newCatalog[] = $item;

			return $item;
		}

		public function save () : array
		{
			if (!$this->definition->hasIdentifierField())
				throw new \Exception("Cannot save catalog items without an identifier!");

			$updates = $this->saveUpdates();
			$additions = $this->saveAdditions();

			return array_merge($updates, $additions);
		}

		private function saveAdditions () : array
		{
			$additions = [];
			$errors = [];

			foreach ($this->newCatalog as $item) {
				if ($item->isDirty()) {
					$identifier = $item->hasIdentifier() ? $item->getIdentifier() : null;
					$data = $this->getWriteData($item);

					try {
						$data = $this->source->create($identifier, $data);
						$this->pushItem($data, 0, false, $item);
						$identifier = $item->hasIdentifier() ? $item->getIdentifier() : null;
						$additions[] = $identifier;
					}
					catch (\Exception $e) {
						//$errors[] = $e;
						throw $e;
					}
				}
			}

			return $additions;
		}

		private function saveUpdates () : array
		{
			$updates = [];
			$errors = [];

			foreach (array_keys($this->rowIdentifier) as $row) {
				if ($this->catalog[$this->rowIdentifier[$row]]->isDirty()) {
					$identifier = $this->rowIdentifier[$row];
					$item = $this->catalog[$identifier];
					$data = $this->getWriteData($item);

					try {
						$this->source->update($identifier, $data);
						$data = $this->source->findOne($identifier);
						$this->pushItem($data, $row, false, $item);
						$updates[] = $identifier;
					}
					catch (\Exception $e) {
						$errors[] = $e;
					}
				}
			}

			return $updates;
		}

		private function getWriteData (CatalogItem $item)
		{
			$data = $this->source->getWritableData();

			foreach ($this->definition->getFields() as $field) {
				$name = $field->getName();

				if ($item->isDirty($name)) {
					$path = $field->getWritablePropertyPath();
					$fieldDataOptions = $field->getOptions();
					$fieldDataType = $this->catalogIndex->getDataType($field->getDataType());
					$value = $fieldDataType->reverseConvert($item[$name], $fieldDataOptions);

					$this->accessor->setValue($data, $path, $value);
				}
			}

			return $data;
		}

		private function calcPage ()
		{
			$page = $this->getPage();
			$pageSize = $this->getPageSize();
			$total = $this->getTotal();
			$last = $page * $pageSize;

			$this->pageStart = ($last - $pageSize);
			$this->pageEnd = min(($last - 1), ($total - 1));
		}

		private function statCatalog () : void
		{
			if (!array_key_exists($this->row, $this->rowIdentifier)) {
				$pageSize = $this->getPageSize();
				$nextRow = $this->row + 1;
				$targetPage = ceil($nextRow / $pageSize);

				$this->dumpNonCurrent();
				$this->setPage($targetPage);

				if ($this->findBy)
					$this->findSingleItems($this->row);
				else
					$this->findPageItems($this->row);
			}
		}

		private function findSingleItems (int $target_row) : void
		{
			$hitTarget = false;
			$end = $this->getPageEnd();
			$row = $this->getPageStart();

			foreach ($this->findIdentifiers as $identifier => $idx) {
				if (!($row <= $idx && $end >= $idx))
					continue;

				$data = $this->source->findOne($identifier);
				$this->pushItem($data, $row, false);

				if ($end == $row)
					break;

				++$row;
			}
		}

		private function findPageItems (int $target_row) : void
		{
			$hitTarget = false;
			$end = $this->getPageEnd();
			$row = $this->getPageStart();

			foreach ($this->source->find() as $data) {
				$this->pushItem($data, $row, true);

				if ($target_row == $row)
					$hitTarget = true;

				if ($end == $row)
					break;

				++$row;
			}

			if ($row && !$hitTarget)
				throw new RuntimeException("Data source ended without hitting target row $target_row!");

			if ($row && !($row == $end))
				throw new RuntimeException("Data source ended with row $row, expecting to end with row $end!");
		}

		private function pushItem ($data, int $row, bool $is_catalog, ?CatalogItemInterface $hydrate = null) : void
		{
			$identifier = null;
			$rowIdentifier = $row;

			if ($this->definition->hasIdentifierField()) {
				$identifierField = $this->definition->getIdentifierField();
				$identifierPath = ($is_catalog) ? $identifierField->getCatalogPropertyPath() : $identifierField->getItemPropertyPath();

				if (!$this->accessor->isReadable($data, $identifierPath))
					throw new RuntimeException("Object identifier could not be read on iteration $row");

				$fieldDataOptions = $identifierField->getOptions();
				$fieldDataType = $this->catalogIndex->getDataType($identifierField->getDataType());

				$identifier = $fieldDataType->convert($this->accessor->getValue($data, $identifierPath), $fieldDataOptions);
				$rowIdentifier = $identifier;

				if (!is_scalar($identifier))
					throw new RuntimeException("Object identifier field must be of a scalar data type.");
			}

			if ($hydrate) {
				$hydrate->hydrate($this->definition, $identifier, $data, false);
			}
			else {
				$this->rowIdentifier[$row] = $rowIdentifier;
				$this->catalog[$rowIdentifier] = new CatalogItem($this->definition, $identifier, $data, $is_catalog);
			}
		}

		private function dumpNonCurrent () : void
		{
			$start = $this->getPageStart();
			$end = $this->getPageEnd();

			foreach (array_keys($this->rowIdentifier) as $row) {
				if (!($start <= $row) && ($end >= $row)) {
					if ($this->catalog[$this->rowIdentifier[$row]]->isDirty())
						throw new \Exception("Cannot advance catalog page iterator from data source with dirty items in current page iteration!");

					unset($this->catalog[$this->rowIdentifier[$row]], $this->rowIdentifier[$row]);
				}
			}
		}

		private function applyInventoryRequest (InventoryRequestInterface $inventory_request) : void
		{
			if ($inventory_request->hasIdentifierTerms())
				$this->applyInventoryIdentifiers($inventory_request);
			else
				$this->applyInventoryParams($inventory_request);
		}

		private function applyInventoryIdentifiers (InventoryRequestInterface $inventory_request) : void
		{
			$this->findBy = true;

			foreach ($inventory_request->getIdentifierTerms() as $identifier)
				$this->findIdentifiers[$identifier] = count($this->findIdentifiers);
		}

		private function applyInventoryParams (InventoryRequestInterface $inventory_request) : void
		{
				$this->applyParamsFilter($inventory_request);
				$this->applyParamsSearch($inventory_request);
				$this->applyParamsSort($inventory_request);
		}

		private function applyDefinitionFields () : void
		{
			foreach ($this->definition->getFields() as $field)
				$this->source->addField($field->getName());
		}

		private function applyParamsFilter (InventoryRequestInterface $inventory_request) : void
		{
			if (!$inventory_request->hasFilterTerms())
				return;

			foreach ($inventory_request->getFilterTerms() as $fieldName => $term)
				$this->source->addFilter($fieldName, $term);
		}

		private function applyParamsSort (InventoryRequestInterface $inventory_request) : void
		{
			if (!$inventory_request->hasSortTerms())
				return;

			foreach ($inventory_request->getSortTerms() as $fieldName => $direction)
				$this->source->addSort($fieldName, $direction);
		}

		private function applyParamsSearch (InventoryRequestInterface $inventory_request) : void
		{
			if (!($inventory_request->hasSearchFields() ?? $inventory_request->hasSearchTerms()))
				return;

			foreach ($inventory_request->getSearchFields() as $fieldName)
				$this->source->addSearchField($fieldName);

			foreach ($inventory_request->getSearchTerms() as $against)
				$this->source->addSearchAgainst($against, true);
		}

	}
}
