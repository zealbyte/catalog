<?php
namespace ZealByte\Catalog
{
	class InventoryRequest implements InventoryRequestInterface
	{
		private $identifierTerms = [];

		private $filterTerms = [];

		private $sortTerms = [];

		private $searchFields = [];

		private $searchTerms = [];

		/**
		 * {@inheritdoc}
		 */
		public function addIdentifier (string $identifier)
		{
			$this->identifierTerms[] = $identifier;

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function addFilterTerm (string $field_name, string $filter_term) : InventoryRequestInterface
		{
			$this->filterTerms[$field_name] = $filter_term;

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function addSortTerm (string $field_name, ?string $direction = null) : InventoryRequestInterface
		{
			$this->sortTerms[$field_name] = $direction;

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function addSearchField (string $field_name) : InventoryRequestInterface
		{
			array_push($this->searchFields, $field_name);

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function addSearchTerm (string $search_term) : InventoryRequestInterface
		{
			array_push($this->searchTerms, $search_term);

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getIdentifierTerms () : array
		{
			if (!$this->hasIdentifierTerms())
				throw new RuntimeException("No identifiers have been sepcified!");

			return $this->identifierTerms;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getFilterTerms () : array
		{
			if (!$this->hasFilterTerms())
				throw new RuntimeException("No filter terms have been defined!");

			return $this->filterTerms;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getSortTerms () : array
		{
			if (!$this->hasSortTerms())
				throw new RuntimeException("No sort terms have been defined!");

			return $this->sortTerms;
		}

		public function getSearchFields () : array
		{
			if (!$this->hasSearchFields())
				throw new RuntimeException("No search fields have been defined!");

			return $this->searchFields;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getSearchTerms () : array
		{
			if (!$this->hasSearchTerms())
				throw new RuntimeException("No search terms have been defined!");

			return $this->searchTerms;
		}

		/**
		 * {@inheritdoc}
		 */
		public function hasIdentifierTerms () : bool
		{
			return !(array() === $this->identifierTerms) ? true : false;
		}

		/**
		 * {@inheritdoc}
		 */
		public function hasFilterTerms () : bool
		{
			return !(array() === $this->filterTerms) ? true : false;
		}

		/**
		 * {@inheritdoc}
		 */
		public function hasSortTerms () : bool
		{
			return !(array() === $this->sortTerms) ? true : false;
		}

		/**
		 * {@inheritdoc}
		 */
		public function hasSearchFields () : bool
		{
			return !(array() === $this->searchFields) ? true :false;
		}

		/**
		 * {@inheritdoc}
		 */
		public function hasSearchTerms () : bool
		{
			return !(array() === $this->searchTerms) ? true : false;
		}

		public function getSortTerm (string $field_name) : bool
		{
			if (!$this->hasSortTerm($field_name))
				throw new RuntimeException("No sort term for $field_name has been defined!");

			return $this->sortTerms[$field_name];
		}

		public function getFilterTerm (string $field_name) : bool
		{
			if (!$this->hasFilterTerm($field_name))
				throw new RuntimeException("No filter term for $field_name has been defined!");

			return $this->filterTerms[$field_name];
		}

		public function hasSortTerm (string $field_name) : bool
		{
			return array_key_exists($field_name, $this->sortTerms);
		}

		public function hasFilterTerm (string $field_name) : bool
		{
			return array_key_exists($field_name, $this->filterTerms);
		}

	}
}
