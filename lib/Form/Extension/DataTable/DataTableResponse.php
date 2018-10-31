<?php
namespace ZealByte\Catalog\Form\Extension\DataTable
{
	use JsonSerializable;
	use Symfony\Component\EventDispatcher\EventDispatcherInterface;
	use ZealByte\Catalog\Catalog;

	class DataTableResponse implements JsonSerializable
	{
		/**
		 * @var Catalog
		 */
		private $catalog;

		/**
		 * @var int
		 */
		private $draw;

		/**
		 * @var int
		 */
		private $recordsTotal;

		/**
		 * @var int
		 */
		private $recordsFiltered;

		/**
		 * @var int
		 */
		private $page;

		/**
		 * @var int
		 */
		private $length;

		/**
		 * @var int
		 */
		private $start;

		/**
		 * @var int
		 */
		private $end;

		/**
		 * @var string
		 */
		private $message;

		/**
		 * @var DataTableRow[]
		 */
		private $data = [];

		public function __construct (?Catalog $catalog = null)
		{
			if ($catalog)
				$this->setCatalog($catalog);
		}

		/**
		 * {@inheritdoc}
		 */
		public function jsonSerialize ()
		{
			return array_filter(get_object_vars($this), function ($value, $property) {
				return (!in_array($property, ['catalog']) || null !== $value);
			}, ARRAY_FILTER_USE_BOTH);
		}

		/**
		 *
		 */
		public function getCatalog () : Catalog
		{
			return $this->catalog;
		}

		/**
		 *
		 */
		public function getDraw () : int
		{
			return (int) $this->draw;
		}

		/**
		 *
		 */
		public function getData () : array
		{
			return (array) $this->data;
		}

		/**
		 *
		 */
		public function getRecordsTotal () : int
		{
			return (int) $this->recordsTotal;
		}

		/**
		 *
		 */
		public function getRecordsFiltered () : int
		{
			return (int) $this->recordsFiltered;
		}

		/**
		 *
		 */
		public function getPage () : int
		{
			return (int) $this->page;
		}

		/**
		 *
		 */
		public function getLength () : int
		{
			return (int) $this->length;
		}

		/**
		 *
		 */
		public function getStart () : int
		{
			return (int) $this->start;
		}

		/**
		 *
		 */
		public function getEnd () : int
		{
			return (int) $this->end;
		}

		/**
		 *
		 */
		public function getMessage () : string
		{
			return (string) $this->message;
		}

		/**
		 *
		 */
		public function hasCatalog () : bool
		{
			return ($this->catalog) ? true : false;
		}

		/**
		 *
		 */
		public function hasDraw () : bool
		{
			return (null !== $this->draw) ? true : false;
		}

		/**
		 *
		 */
		public function hasData () : bool
		{
			return ($this->data) ? true : false;
		}

		/**
		 *
		 */
		public function hasRecordsTotal () : bool
		{
			return (null !== $this->recordsTotal) ? true : false;
		}

		/**
		 *
		 */
		public function hasRecordsFiltered () : bool
		{
			return (null !== $this->recordsFiltered) ? true : false;
		}

		/**
		 *
		 */
		public function hasPage () : bool
		{
			return (null !== $this->page) ? true : false;
		}

		/**
		 *
		 */
		public function hasLength () : bool
		{
			return (null !== $this->length) ? true : false;
		}

		/**
		 *
		 */
		public function hasStart () : bool
		{
			return (null !== $this->start) ? true : false;
		}

		/**
		 *
		 */
		public function hasEnd () : bool
		{
			return (null !== $this->end) ? true : false;
		}

		/**
		 *
		 */
		public function hasMessage () : bool
		{
			return ($this->message) ? true : false;
		}

		/**
		 *
		 */
		public function setCatalog (Catalog $catalog) : self
		{
			$this->catalog = $catalog;
		}

		/**
		 *
		 */
		public function setDraw (int $draw) : self
		{
			$this->draw = $draw;

			return $this;
		}

		/**
		 *
		 */
		public function setData (array $data) : self
		{
			$this->data = $data;

			return $this;
		}

		/**
		 *
		 */
		public function setRecordsTotal (int $records_total) : self
		{
			$this->recordsTotal = $records_total;

			return $this;
		}

		/**
		 *
		 */
		public function setRecordsFiltered (int $records_filtered) : self
		{
			$this->recordsFiltered = $records_filtered;

			return $this;
		}

		/**
		 *
		 */
		public function setPage (int $page) : self
		{
			$this->page = $page;

			return $this;
		}

		/**
		 *
		 */
		public function setLength (int $length) : self
		{
			$this->length = $length;

			return $this;
		}

		/**
		 *
		 */
		public function setStart (int $start) : self
		{
			$this->start = $start;

			return $this;
		}

		/**
		 *
		 */
		public function setEnd (int $end) : self
		{
			$this->end = $end;

			return $this;
		}

		/**
		 *
		 */
		public function setMessage (string $message) : self
		{
			$this->message = $message;

			return $this;
		}

	}
}
