<?php
namespace ZealByte\Catalog
{
	interface InventoryRequestInterface
	{
		public function getIdentifierTerms () : array;

		public function getFilterTerms () : array;

		public function getSortTerms () : array;

		public function getSearchFields () : array;

		public function getSearchTerms () : array;

		public function hasIdentifierTerms () : bool;

		public function hasFilterTerms () : bool;

		public function hasSortTerms () : bool;

		public function hasSearchFields () : bool;

		public function hasSearchTerms () : bool;
	}
}
