<?php
namespace ZealByte\Catalog\Form\Extension\DataTable\Event
{
	use Symfony\Component\Form\FormInterface;
	use ZealByte\Catalog\InventoryRequestInterface;
	use ZealByte\Catalog\Catalog;

	class DataTableProcessFormEvent extends DataTableFormEvent
	{
		private $catalog;

		private $inventoryRequest;

		private $draw;

		private $page;

		private $pageSize;

		public function getCatalog () : Catalog
		{
			return $this->catalog;
		}

		public function getInventoryRequest () : InventoryRequestInterface
		{
			return $this->inventoryRequest;
		}

		public function getDraw () : int
		{
			return $this->draw;
		}

		public function getPage () : int
		{
			return $this->page;
		}

		public function getPageSize () : int
		{
			return $this->pageSize;
		}

		public function hasCatalog () : bool
		{
			return ($this->catalog) ? true : false;
		}

		public function hasInventoryRequest () : bool
		{
			return ($this->inventoryRequest) ? true : false;
		}

		public function hasDraw () : bool
		{
			return ($this->draw) ? true : false;
		}

		public function hasPage () : bool
		{
			return ($this->page) ? true : false;
		}

		public function hasPageSize () : bool
		{
			return ($this->pageSize) ? true : false;
		}

		public function setCatalog (Catalog $catalog) : self
		{
			$this->catalog = $catalog;

			return $this;
		}

		public function setInventoryRequest (InventoryRequestInterface $inventory_request) : self
		{
			$this->inventoryRequest = $inventory_request;

			return $this;
		}

		public function setDraw (int $draw) : self
		{
			$this->draw = $draw;

			return $this;
		}

		public function setPage (int $page) : self
		{
			$this->page = $page;

			return $this;
		}

		public function setPageSize (int $page_size) : self
		{
			$this->pageSize = $page_size;

			return $this;
		}

	}
}
