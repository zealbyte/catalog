<?php
namespace ZealByte\Catalog\Form\Extension\DataTable\Event
{
	use ZealByte\Catalog\Event\CatalogRequestEvent;
	use ZealByte\Catalog\CatalogItem;

	class DataTableRowEvent extends CatalogRequestEvent
	{
		private $catalogItem;

		private $row;

		public function getCatalogItem () : CatalogItem
		{
			return $this->catalogItem;
		}

		public function getRow () : array
		{
			return $this->row;
		}

		public function hasCatalogItem () : bool
		{
			return ($this->catalogItem) ? true : false;
		}

		public function hasRow () : bool
		{
			return ($this->row) ? true : false;
		}

		public function setCatalogItem (CatalogItem $item) : self
		{
			$this->catalogItem = $item;

			return $this;
		}

		public function setRowProperty (string $property, $value) : self
		{
			if (!$this->row)
				$this->row = [];

			$this->row[$property] = $value;

			return $this;
		}

	}
}
