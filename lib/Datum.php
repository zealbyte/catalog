<?php
namespace ZealByte\Catalog
{
	use InvalidArgumentException;
	use Symfony\Component\Form\FormTypeInterface;
	use ZealByte\Util;

	class Datum implements DatumInterface
	{
		private $name;

		private $columnType;

		private $filterType;

		private $formType;

		private $isDetail;

		private $field_names = [];

		private $columnOptions = [];

		private $filterOptions = [];

		private $formOptions = [];


		public function __construct (string $name = null, array $field_names = null)
		{
			if ($name)
				$this->setName($name);

			if ($field_names)
				foreach ($field_names as $field_name)
					$this->addFieldName($field_name);
		}

		/**
		 * {@inheritdoc}
		 */
		public function getFieldNames () : array
		{
			return $this->field_names;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getColumnOptions () : array
		{
			return $this->columnOptions;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getColumnType () : string
		{
			return $this->columnType;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getFilterType () : string
		{
			return $this->filterType;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getFilterOptions () : array
		{
			return $this->filterOptions;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getFormType () : string
		{
			return $this->formType;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getFormOptions () : array
		{
			return $this->formOptions;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getName () : string
		{
			return $this->name;
		}

		/**
		 * {@inheritdoc}
		 */
		public function hasColumnType () : bool
		{
			return ($this->columnType) ? true : false;
		}

		/**
		 * {@inheritdoc}
		 */
		public function hasFilterType () : bool
		{
			return ($this->filterType) ? true : false;
		}

		/**
		 * {@inheritdoc}
		 */
		public function hasFormType () : bool
		{
			return ($this->formType) ? true : false;
		}

		public function addFieldName (string $field_name) : self
		{
			$this->field_names[] = $field_name;

			return $this;
		}

		public function setColumnType (string $column_type, ?array $options = null) : self
		{
			$this->columnType = $column_type;

			if ($options)
				$this->columnOptions = $options;

			return $this;
		}

		public function setFilterType (string $filter_type, ?array $options = null) : self
		{
			$this->filterType = $filter_type;

			if ($options)
				$this->filterOptions = $options;

			return $this;
		}

		public function setFormType (string $form_type, ?array $options = null) : self
		{
			$this->formType = $form_type;

			if ($options)
				$this->formOptions = $options;

			return $this;
		}

		public function setName (string $name) : self
		{
			$this->name = $name;

			return $this;
		}

	}
}
