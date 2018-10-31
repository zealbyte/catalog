<?php
namespace ZealByte\Catalog\Inventory
{
	use ZealByte\Catalog\Data\Type\DataTypeInterface;
	use ZealByte\Catalog\Column\Type\ColumnTypeInterface;
	use ZealByte\Util;

	class CatalogIndex implements CatalogIndexInterface
	{
		/**
		 * @var array<DataTypeInterface>
		 */
		private $dataTypes = [];

		/**
		 * @var array<ColumnTypeInterface>
		 */
		private $columnTypes = [];

		public function addDataType (string $id, DataTypeInterface $data_type, ?array $attributes = []) : CatalogIndexInterface
		{
			$id = Util\Canonical::name($id);

			$this->dataTypes[$id] = $data_type;

			return $this;
		}

		public function addColumnType (string $id, ColumnTypeInterface $data_type, ?array $attributes = []) : CatalogIndexInterface
		{
			$id = Util\Canonical::name($id);

			$this->columnTypes[$id] = $data_type;

			return $this;
		}

		public function getDataType (string $id, ?array $options = []) : DataTypeInterface
		{
			$class = $id;
			$id = Util\Canonical::name($id);

			if (!(array_key_exists($id, $this->dataTypes)))
				$this->newDataType($id, $class, $options);

			return $this->dataTypes[$id];
		}

		public function getColumnType (string $id, ?array $options = []) : ColumnTypeInterface
		{
			$class = $id;
			$id = Util\Canonical::name($id);

			if (!(array_key_exists($id, $this->columnTypes)))
				$this->newColumnType($id, $class, $options);

			return $this->columnTypes[$id];
		}

		private function newDataType (string $id, string $class, array $options) : void
		{
			$type = new $class();

			if (!($type instanceof DataTypeInterface))
				throw new \Exception("Catalog data types must implement the ".DataTypeInterface::class." interface.");

			$this->dataTypes[$id] = new $class();
		}

		private function newColumnType (string $id, string $class, array $options) : void
		{
			$type = new $class();

			if (!($type instanceof ColumnTypeInterface))
				throw new \Exception("Catalog column types must implement the ".ColumnTypeInterface::class." interface.");

			$this->columnTypes[$id] = $type;
		}
	}
}
