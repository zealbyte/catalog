<?php
namespace ZealByte\Catalog
{
	use InvalidArgumentException;
	use ZealByte\Catalog\Data\Type as DataType;
	use ZealByte\Util;

	class Field implements FieldInterface
	{
		private $name;

		private $catalogPropertyPath;

		private $itemPropertyPath;

		private $writablePropertyPath;

		private $dataType;

		private $options = [];


		public function __construct (string $name = null, ?string $property_path = null, ?string $data_type = null, ?array $options = null)
		{
			if ($name)
				$this->setName($name);

			if ($property_path)
				$this->setPropertyPath($property_path);

			if (!$data_type)
				$data_type = DataType\StringType::class;

			$this->setDataType($data_type, $options);
		}

		/**
		 * {@inheritdoc}
		 */
		public function getOptions () : array
		{
			return $this->options;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getDataType () : string
		{
			return $this->dataType;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getName () : string
		{
			return $this->name;
		}

		public function getCatalogPropertyPath () : string
		{
			return $this->catalogPropertyPath;
		}

		public function getItemPropertyPath () : string
		{
			return $this->itemPropertyPath;
		}

		public function getWritablePropertyPath () : string
		{
			return $this->writablePropertyPath;
		}

		/**
		 * {@inheritdoc}
		 */
		public function hasCatalogPropertyPath () : bool
		{
			return ($this->catalogPropertyPath) ? true : false;
		}

		/**
		 * {@inheritdoc}
		 */
		public function hasItemPropertyPath () : bool
		{
			return ($this->itemPropertyPath) ? true : false;
		}

		/**
		 * {@inheritdoc}
		 */
		public function hasWritablePropertyPath () : bool
		{
			return ($this->writablePropertyPath) ? true : false;
		}

		/**
		 * {@inheritdoc}
		 */
		public function hasDataType () : bool
		{
			return ($this->dataType) ? true : false;
		}

		public function setDataType (string $data_type, ?array $options = null) : self
		{
			$this->dataType = $data_type;

			if ($options)
				$this->options = $options;

			return $this;
		}

		public function setName (string $name) : self
		{
			$this->name = $name;

			return $this;
		}

		public function setPropertyPath (string $property_path) : self
		{
			$this->setCatalogPropertyPath($property_path);
			$this->setItemPropertyPath($property_path);
			$this->setWritablePropertyPath($property_path);

			return $this;
		}

		public function setCatalogPropertyPath (?string $property_path = null) : self
		{
			$this->catalogPropertyPath = $property_path;

			return $this;
		}

		public function setItemPropertyPath (?string $property_path = null) : self
		{
			$this->itemPropertyPath = $property_path;

			return $this;
		}

		public function setWritablePropertyPath (?string $property_path = null) : self
		{
			$this->writablePropertyPath = $property_path;

			return $this;
		}

	}
}
