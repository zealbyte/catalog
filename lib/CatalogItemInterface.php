<?php
namespace ZealByte\Catalog
{
	use ArrayAccess;
	use Iterator;
	use JsonSerializable;

	interface CatalogItemInterface extends ArrayAccess, Iterator, JsonSerializable
	{
		/**
		 *
		 */
		public function getIdentifier () : string;

		/**
		 *
		 */
		public function getLabel () : string;

		/**
		 *
		 */
		public function hasIdentifier () : bool;

		/**
		 *
		 */
		public function hasLabel () : bool;

		/**
		 *
		 */
		public function isDirty ();
	}
}
