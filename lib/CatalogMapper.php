<?php
namespace ZealByte\Catalog
{
	use RuntimeException;
	use ZealByte\Catalog\Data\Source\DataSourceInterface;

	class CatalogMapper implements CatalogMapperInterface
	{
		private $dataSource;

		private $identifierField;

		private $labelField;

		private $fields = [];

		public function addField (FieldInterface $field) : CatalogMapperInterface
		{
			$this->fields[$field->getName()] = $field;

			return $this;
		}

		public function setDataSource (DataSourceInterface $data_source) : CatalogMapperInterface
		{
			$this->dataSource = $data_source;

			return $this;
		}

		public function setIdentifierField (FieldInterface $field) : CatalogMapperInterface
		{
			$this->addField($field);
			$this->identifierField = $field;

			return $this;
		}

		public function setLabelField (FieldInterface $field) : CatalogMapperInterface
		{
			$this->addField($field);
			$this->labelField = $field;

			return $this;
		}

		public function getFields () : array
		{
			if (!$this->hasFields())
				throw new RuntimeException("No fields have been set!");

			return $this->fields;
		}

		public function getIdentifierField () : FieldInterface
		{
			if (!$this->hasIdentifierField())
				throw new RuntimeException("No identifier field has been set!");

			return $this->identifierField;
		}

		public function getLabelField () : FieldInterface
		{
			if (!$this->hasLabelField())
				throw new RuntimeException("No label field has been defined!");

			return $this->labelField;
		}

		public function getDataSource () : DataSourceInterface
		{
			if (!$this->hasDataSource())
				throw new RuntimeException("The data source has not been defined!");

			return $this->dataSource;
		}

		public function hasDataSource () : bool
		{
			return ($this->dataSource) ? true : false;
		}

		public function hasField (string $field) : bool
		{
			return array_key_exists($field, $this->fields);
		}

		public function hasFields () : bool
		{
			return !(array() === $this->fields);
		}

		public function hasIdentifierField () : bool
		{
			return ($this->identifierField) ? true : false;
		}

		public function hasLabelField () : bool
		{
			return ($this->labelField) ? true : false;
		}

	}
}
