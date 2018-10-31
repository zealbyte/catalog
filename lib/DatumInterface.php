<?php
namespace ZealByte\Catalog
{
	interface DatumInterface
	{
		public function getFieldNames () : array;

		public function getColumnType () : string;

		public function getColumnOptions () : array;

		public function getFilterType () : string;

		public function getFilterOptions () : array;

		public function getFormType () : string;

		public function getFormOptions () : array;

		public function getName () : string;

		public function hasColumnType () : bool;

		public function hasFilterType () : bool;

		public function hasFormType () : bool;

	}
}
