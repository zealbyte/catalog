<?php
namespace ZealByte\Catalog\Form\Extension\DataTable\Model
{
	class DataTableColumnModel
	{
		private $name;

		private $searchable;

		private $orderable;

		private $search;

		public function getName () : string
		{
			return (string) $this->name;
		}

		public function isSearchable () : bool
		{
			return ($this->searchable) ? true : false;
		}

		public function isOrderable () : bool
		{
			return ($this->orderable) ? true : false;
		}

		public function getSearch () : DataTableSearchModel
		{
			if (!$this->search)
				$this->search = new DataTableSearchModel();

			return $this->search;
		}

		public function setName (string $name) : self
		{
			$this->name = $name;

			return $this;
		}

		public function setSearchable (?bool $searchable = null) : self
		{
			$this->searchable = $searchable;

			return $this;
		}

		public function setOrderable (?bool $orderable = null) : self
		{
			$this->orderable = $orderable;

			return $this;
		}

		public function setSearch (DataTableSearchModel $search) : self
		{
			$this->search = $search;

			return $this;
		}

	}
}
