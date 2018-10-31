<?php
namespace ZealByte\Catalog
{
	use ZealByte\Catalog\Data\Source\DataSourceInterface;

	interface CatalogMapperInterface
	{
		public function addField (FieldInterface $field) : CatalogMapperInterface;

		public function setDataSource (DataSourceInterface $data_source) : CatalogMapperInterface;

		public function setIdentifierField (FieldInterface $field) : CatalogMapperInterface;

		public function setLabelField (FieldInterface $field) : CatalogMapperInterface;

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
