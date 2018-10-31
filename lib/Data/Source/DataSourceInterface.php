<?php
namespace ZealByte\Catalog\Data\Source
{
	interface DataSourceInterface
	{
		public function addField (string $field) : DataSourceInterface;

		public function addFilter (string $field, /*mixed*/ $term) : DataSourceInterface;

		public function addSearchField (string $field) : DataSourceInterface;

		public function addSearchAgainst (string $against, bool $and) : DataSourceInterface;

		public function addSort (string $field, string $direction) : DataSourceInterface;

		public function setPage (int $page) : DataSourceInterface;

		public function setPageSize (int $page_size) : DataSourceInterface;

		public function getCount () : int;

		public function getPage () : int;

		public function getPageSize () : int;

		public function getTotal () : int;

		public function getWritableData () /*mixed*/;

		public function find () : array;

		public function findOne (string $identifier);

		public function create (?string $identifier, /*mixed*/ $data);

		public function update (string $identifier, /*mixed*/ $data) : void;

		public function delete (string $identifier) : void;
	}
}
