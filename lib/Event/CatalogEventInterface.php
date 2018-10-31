<?php
namespace ZealByte\Catalog\Event
{
	use ZealByte\Catalog\SpecInterface;

	interface CatalogEventInterface
	{
		/**
		 *
		 */
		public function getAlias () : string;

		/**
		 *
		 */
		public function getCategory () : string;

		/**
		 *
		 */
		public function getSpec () : SpecInterface;

		/**
		 *
		 */
		public function hasAlias () : bool;

		/**
		 *
		 */
		public function hasCategory () : bool;

		/**
		 *
		 */
		public function hasSpec () : bool;

		/**
		 *
		 */
		public function setAlias (string $alias) : CatalogEventInterface;

		/**
		 *
		 */
		public function setCategory (string $category) : CatalogEventInterface;

		/**
		 *
		 */
		public function setSpec (SpecInterface $spec) : CatalogEventInterface;
	}
}
