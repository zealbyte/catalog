<?php
namespace ZealByte\Catalog
{
	interface FieldInterface
	{
		public function getDataType () : string;

		public function getOptions () : array;

		public function getName () : string;

		public function getCatalogPropertyPath () : string;

		public function getItemPropertyPath () : string;

		public function getWritablePropertyPath () : string;

		public function hasDataType () : bool;

		public function hasCatalogPropertyPath () : bool;

		public function hasItemPropertyPath () : bool;

		public function hasWritablePropertyPath () : bool;
	}
}
