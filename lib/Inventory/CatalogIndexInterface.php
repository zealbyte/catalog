<?php
namespace ZealByte\Catalog\Inventory
{
	use ZealByte\Catalog\Data\Type\DataTypeInterface;
	use ZealByte\Catalog\Column\Type\ColumnTypeInterface;

	interface CatalogIndexInterface
	{
		/**
		 *
		 */
		public function addDataType (string $id, DataTypeInterface $data_type, ?array $attributes = []) : CatalogIndexInterface;

		/**
		 *
		 */
		public function addColumnType (string $id, ColumnTypeInterface $data_type, ?array $attributes = []) : CatalogIndexInterface;

		/**
		 *
		 */
		public function getDataType (string $id, ?array $options = []) : DataTypeInterface;

		/**
		 *
		 */
		public function getColumnType (string $id, ?array $options = []) : ColumnTypeInterface;
	}
}
