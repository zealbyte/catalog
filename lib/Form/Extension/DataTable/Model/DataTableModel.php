<?php
namespace ZealByte\Catalog\Form\Extension\DataTable\Model
{
	class DataTableModel
	{
		private $draw;

		private $start;

		private $length;

		private $search;

		private $order = [];

		private $columns = [];

		public function getDraw () : int
		{
			return (int) $this->draw;
		}

		public function getStart () : int
		{
			return (int) $this->start;
		}

		public function getLength () : int
		{
			return (int) $this->length;
		}

		public function getSearch () : DataTableSearchModel
		{
			if (!$this->search)
				$this->search = new DataTableSearchModel();

			return $this->search;
		}

		public function getOrder () : array
		{
			return $this->order;
		}

		public function getColumns () : array
		{
			return $this->columns;
		}

		public function setDraw (int $draw) : self
		{
			$this->draw = $draw;

			return $this;
		}

		public function addColumn (DataTableColumnModel $column) : self
		{
			array_push($this->columns, $column);

			return $this;
		}

		public function addOrder (DataTableOrderModel $order) : self
		{
			array_push($this->order, $order);

			return $this;
		}

		public function setOrder (array $order) : self
		{
			foreach ($order as $o)
				if (!($o instanceof DataTableOrderModel))
					throw new \Exception("DataTable order must be an array of ".DataTableOrderModel::class);

			$this->order = $order;

			return $this;
		}

		public function setStart (int $start) : self
		{
			$this->start = $start;

			return $this;
		}

		public function setLength (int $length) : self
		{
			$this->length = $length;

			return $this;
		}

		public function setSearch (DataTableSearchModel $search) : self
		{
			$this->search = $search;

			return $this;
		}

	}
}
