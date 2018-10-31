<?php
namespace ZealByte\Catalog\Event
{
	use Symfony\Component\EventDispatcher\Event;
	use ZealByte\Catalog\SpecInterface;

	class CatalogEvent extends Event implements CatalogEventInterface
	{
		private $alias;

		private $category;

		private $spec;


		/**
		 *
		 */
		public function getAlias () : string
		{
			return $this->alias;
		}

		/**
		 *
		 */
		public function getCategory () : string
		{
			return $this->category;
		}

		/**
		 *
		 */
		public function getSpec () : SpecInterface
		{
			return $this->spec;
		}

		/**
		 *
		 */
		public function hasAlias () : bool
		{
			return ($this->alias) ? true : false;
		}

		/**
		 *
		 */
		public function hasCategory () : bool
		{
			return ($this->category) ? true : false;
		}

		/**
		 *
		 */
		public function hasSpec () : bool
		{
			return ($this->spec) ? true : false;
		}

		/**
		 *
		 */
		public function setAlias (string $alias) : CatalogEventInterface
		{
			$this->alias = $alias;

			return $this;
		}

		/**
		 *
		 */
		public function setCategory (string $category) : CatalogEventInterface
		{
			$this->category = $category;

			return $this;
		}

		/**
		 *
		 */
		public function setSpec (SpecInterface $spec) : CatalogEventInterface
		{
			$this->spec = $spec;

			return $this;
		}

	}
}
