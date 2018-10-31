<?php
namespace ZealByte\Catalog
{
	use ZealByte\Catalog\Inventory\CatalogIndexInterface;
	use ZealByte\Catalog\Data\Source\DataSourceInterface;

	class CatalogDefinition implements CatalogDefinitionInterface
	{
		private $catalogIndex;

		private $mapper;

		public function __construct (CatalogIndexInterface $catalog_index, CatalogMapperInterface $mapper)
		{
			$this->catalogIndex = $catalog_index;

			$this->mapper = $mapper;
		}

		public function getDataSource () : DataSourceInterface
		{
			return $this->mapper->getDataSource();
		}

		public function getIdentifierField () : FieldInterface
		{
			return $this->mapper->getIdentifierField();
		}

		public function getLabelField () : FieldInterface
		{
			return $this->mapper->getLabelField();
		}

		public function getFields () : array
		{
			return $this->mapper->getFields();
		}

		public function hasDataSource () : bool
		{
			return $this->mapper->hasDataSource();
		}

		public function hasField (string $field) : bool
		{
			return $this->mapper->hasField($field);
		}

		public function hasFields () : bool
		{
			return $this->mapper->hasFields();
		}

		public function hasIdentifierField () : bool
		{
			return $this->mapper->hasIdentifierField();
		}

		public function hasLabelField () : bool
		{
			return $this->mapper->hasLabelField();
		}

		public function getCatalogIndex () : CatalogIndexInterface
		{
			return $this->catalogIndex;
		}
	}
}
