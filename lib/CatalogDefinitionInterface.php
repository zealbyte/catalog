<?php
namespace ZealByte\Catalog
{
	use ZealByte\Catalog\Data\Source\DataSourceInterface;

	interface CatalogDefinitionInterface
	{
		public function getDataSource () : DataSourceInterface;

		public function getIdentifierField () : FieldInterface;

		public function getLabelField () : FieldInterface;

		public function getFields () : array;

		public function hasDataSource () : bool;

		public function hasField (string $field) : bool;

		public function hasFields () : bool;

		public function hasIdentifierField () : bool;

		public function hasLabelField () : bool;
	}
}
