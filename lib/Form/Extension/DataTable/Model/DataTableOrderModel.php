<?php
namespace ZealByte\Catalog\Form\Extension\DataTable\Model
{
	class DataTableOrderModel
	{
		private $column;

		private $dir;

		public function getColumn () : int
		{
			return (int) $this->column;
		}

		public function getDir () : string
		{
			return (string) $this->dir;
		}

		public function setColumn (string $column) : self
		{
			$this->column = $column;

			return $this;
		}

		public function setDir (string $dir) : self
		{
			$this->dir = $dir;

			return $this;
		}
	}
}
