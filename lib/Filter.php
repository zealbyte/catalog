<?php
namespace ZealByte\Catalog
{
	class Filter implements FilterInterface
	{
		private $fields = [];

		private $terms = [];

		public function __construct (?CatalogBuilderInterface $builder = null)
		{
			if ($builder && $builder->hasFilters())
				$this->fields = $builder->getFilters();
		}

		public function __get ($name)
		{
			if (!array_key_exists($name, $this->fields))
				throw new InvalidArgumentException("Filter field $name is not defined!");

			return array_key_exists($name, $this->terms) ? $this->terms[$name] : null;
		}

		public function __isset ($name)
		{
			return array_key_exists($name, $this->fields);
		}

		public function __set ($name, $value)
		{
			if (!array_key_exists($name, $this->fields))
				throw new InvalidArgumentException("Filter field $name is not defined!");

			$this->terms[$name] = $value;
		}

		public function FilterFields () : array
		{
			return $this->fields;
		}

	}
}
