<?php
namespace ZealByte\Catalog\Form\Extension\DataTable\Model
{
	class DataTableSearchModel
	{
		private $value;

		private $is_regex;

		public function getValue () : ?string
		{
			return $this->value;
		}

		public function getRegex () : bool
		{
			return ($this->is_regex) ? true : false;
		}

		public function setValue (?string $value = null) : self
		{
			$this->value = $value;

			return $this;
		}

		public function setRegex (?bool $is_regex) : self
		{
			$this->is_regex = ($is_regex) ? true : false;

			return $this;
		}

	}
}
