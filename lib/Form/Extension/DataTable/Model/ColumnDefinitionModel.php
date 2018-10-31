<?php
namespace ZealByte\Catalog\Form\Extension\DataTable\Model
{
	use JsonSerializable;

	class ColumnDefinitionModel implements JsonSerializable
	{
		private $cellType;

		private $className;

		private $contentPadding;

		private $createdCell;

		private $data;

		private $defaultContent;

		private $name;

		private $orderable;

		private $orderData;

		private $orderDataType;

		private $render;

		private $searchable;

		private $title;

		private $type;

		private $visible;

		private $width;

		/**
		 * {@inheritdoc}
		 */
		public function jsonSerialize ()
		{
			$json = [];

			foreach (get_class_vars(self::class) as $prop)
				if (null !== $this->$prop)
					$json[$prop] = $this->$prop;

			return $json;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.cellType
		 */
		public function getCellType () : ?string
		{
			return $this->cellType;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.className
		 */
		public function getClassName () : ?string
		{
			return $this->className;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.contentPadding
		 */
		public function getContentPadding () : ?string
		{
			return $this->contentPadding;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.createdCell
		 */
		public function getCreatedCell () : ?string
		{
			return $this->createdCell;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.data
		 */
		public function getData () : ?string
		{
			return $this->data;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.defaultContent
		 */
		public function getDefaultContent () : ?string
		{
			return $this->defaultContent;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.name
		 */
		public function getName () : string
		{
			return $this->name;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.orderable
		 */
		public function getOrderable () : bool
		{
			return ($this->orderable) ? true : false;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.orderData
		 */
		public function getOrderData () : ?string
		{
			return $this->orderData;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.orderDataType
		 */
		public function getOrderDataType () : ?string
		{
			return $this->dataType;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.render
		 */
		public function getRender () : ?string
		{
			return $this->render;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.searchable
		 */
		public function getSearchable () : bool
		{
			return ($this->searchable) ? true : false;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.title
		 */
		public function getTitle () : ?string
		{
			return $this->title;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.type
		 */
		public function getType () : ?string
		{
			return $this->type;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.visible
		 */
		public function getVisible () : bool
		{
			return ($this->visible) ? true : false;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.width
		 */
		public function getWidth () : ?string
		{
			return $this->width;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.cellType
		 */
		public function setCellType (string $cell_type) : ColumnDefinitionInterface
		{
			$this->cellType = $cell_type;

			return $this;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.className
		 */
		public function setClassName (string $class_name) : ColumnDefinitionInterface
		{
			$this->className = $class_name;

			return $this;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.contentPadding
		 */
		public function setContentPadding (string $content_padding) : ColumnDefinitionInterface
		{
			$this->contentPaddin = $content_padding;

			return $this;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.createdCell
		 */
		public function setCreatedCell (JavascriptMethodInterface $created_cell) : ColumnDefinitionInterface
		{
			$this->createdCell = $created_cell;

			return $this;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.data
		 */
		public function setData (ColumnDataInterface $data) : ColumnDefinitionInterface
		{
			$this->data = $data;

			return $this;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.defaultContent
		 */
		public function setDefaultContent (string $default_content) : ColumnDefinitionInterface
		{
			$this->default_content = $default_content;

			return $this;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.name
		 */
		public function setName (string $name) : ColumnDefinitionInterface
		{
			$this->name = $name;

			return $this;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.name
		 */
		public function setOrderable (bool $orderable) : ColumnDefinitionInterface
		{
			$this->orderable = $orderable;

			return $this;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.name
		 */
		public function setOrderData (ColumnOrderDataInterface $order_data) : ColumnDefinitionInterface
		{
			$this->orderData = $order_data;

			return $this;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.orderDataType
		 */
		public function setOrderDataType (string $order_data_type) : ColumnDefinitionInterface
		{
			$this->orderDataType = $order_data_type;

			return $this;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.render
		 */
		public function setRender (ColumnRendererInterface $render) : ColumnDefinitionInterface
		{
			$this->render = $render;

			return $this;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.searchable
		 */
		public function setSearchable (bool $searchable) : ColumnDefinitionInterface
		{
			$this->searchable = $searchable;

			return $this;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.title
		 */
		public function setTitle (string $title) : ColumnDefinitionInterface
		{
			$this->title = $title;

			return $this;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.type
		 */
		public function setType (string $type) : ColumnDefinitionInterface
		{
			$this->type = $type;

			return $this;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.visible
		 */
		public function setVisible (bool $visible) : ColumnDefinitionInterface
		{
			$this->visible = $visible;

			return $this;
		}

		/**
		 * @link https://datatables.net/reference/option/columns.width
		 */
		public function setWidth (string $width) : ColumnDefinitionInterface
		{
			$this->width = $width;

			return $this;
		}
	}
}


