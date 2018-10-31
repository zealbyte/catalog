<?php
namespace ZealByte\Catalog\Data\Source
{
	use RuntimeException;
	use Symfony\Component\PropertyAccess\PropertyAccess;
	use Doctrine\ORM\EntityManager;
	use Doctrine\ORM\Query\Expr;
	use ZealByte\Util;

	class DoctrineRegistry implements DataSourceInterface
	{
		private $entityManager;

		private $entityId;

		private $query;

		private $data_alias = 'obj';

		private $related_column;

		private $related_join;

		private $related_value;

		private $page;

		private $pageSize;

		private $fields = [];

		private $joins = [];

		private $selectFields = [];

		private $filterAliases = [];

		private $sortableAliases = [];

		private $searchAliases = [];

		private $searchAgainsts = [];


		/**
		 * {@inheritdoc}
		 */
		public function addField (string $field) : DataSourceInterface
		{
			$alias = str_replace('.', '_', $field);

			$this->selectFields[$alias] = $field;

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function addFilter (string $field, /*mixed*/ $term) : DataSourceInterface
		{
			$alias = str_replace('.', '_', $field);

			$this->filterAliases[$alias] = $term;

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function addSearchAgainst (string $against, bool $and) : DataSourceInterface
		{
			if (!empty($against)) {
				$canonical = Util\Canonical::term(trim($against));

				if (!array_key_exists($canonical, $this->searchAgainsts))
					$this->searchAgainsts[$canonical] = [
						'term' => $against,
						'and' => $and
					];
			}

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function addSearchField (string $field) : DataSourceInterface
		{
			$alias = str_replace('.', '_', $field);

			if (!in_array($alias, $this->searchAliases))
				array_push($this->searchAliases, $alias);

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function addSort (string $field, string $direction) : DataSourceInterface
		{
			$alias = str_replace('.', '_', $field);

			$this->sortableAliases[$alias] = $direction;

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function setPageSize (int $page_size) : DataSourceInterface
		{
			$this->pageSize = $page_size;

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getTotal () : int
		{
			$this->buildQueryTotal();

			return (int) $this->query->getSingleScalarResult();
		}

		/**
		 * {@inheritdoc}
		 */
		public function getCount () : int
		{
			$this->buildQueryCount();
			$this->applyQueryParams(false);

			return (int) $this->query->getSingleScalarResult();
		}

		/**
		 * {@inheritdoc}
		 */
		public function getPage () : int
		{
			return $this->page ?: 1;
		}

		/**
		 * {@inheritdoc}
		 */
		public function setPage (int $page) : DataSourceInterface
		{
			$this->page = $page;

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getPageSize () : int
		{
			return $this->pageSize ?: 1;
		}

		/**
		 * {@inheritdoc}
		 */
		public function find () : array
		{
			$this->buildQuery();
			$this->applyQueryParams();

			return $this->query->getResult();
		}

		/**
		 * {@inheritdoc}
		 */
		public function findOne (string $identifier)
		{
			$this->buildSingleQuery($identifier);

			return $this->query->getSingleResult();
		}

		/**
		 * {@inheritdoc}
		 */
		public function create (?string $identifier = null, $data)
		{
			$accessor = PropertyAccess::createPropertyAccessor();
			$identifierAliases = $this->getObjectIdentifierFields();
			$entityManager = $this->getEntityManager();
			$entityId = $this->getEntityId();
			$entity = new $entityId();

			foreach ($data as $field => $value)
				$accessor->setValue($entity, $field, $value);

			$entityManager->persist($entity);
			$entityManager->flush();

			foreach ($identifierAliases as $identifierAlias)
				$identifier = $accessor->getValue($entity, $identifierAlias);

			return $this->findOne($identifier);
		}

		public function getWritableData ()
		{
			return [];
		}

		/**
		 * {@inheritdoc}
		 */
		public function update (string $identifier, $data) : void
		{
			$this->buildUpdateQuery($identifier, $data);
			$done = $this->query->execute();

			if (!$done)
				throw new \Exception("Update did not happen!");
		}

		/**
		 * {@inheritdoc}
		 */
		public function delete (string $identifier) : void
		{
		}

		public function getPageStart () : int
		{
			$page = $this->getPage();
			$pageSize = $this->getPageSize();

			return (($page * $pageSize) - $pageSize);
		}

		/**
		 * Set the entity class to build the query from
		 */
		public function setEntityId (string $entity_id) : self
		{
			$this->entityId = $entity_id;

			return $this;
		}

		/**
		 * Set the entity manager to build the query from
		 */
		public function setEntityManager (EntityManager $entity_manager) : self
		{
			$this->entityManager = $entity_manager;

			return $this;
		}

		/**
		 * Get the entity class to build the query from
		 */
		public function getEntityId () : string
		{
			if (!$this->entityId)
				throw new RuntimeException("No entity class has been set.");

			return $this->entityId;
		}

		/**
		 * Get the entity manager to build the query from
		 */
		public function getEntityManager () : EntityManager
		{
			if (!$this->entityManager)
				throw new RuntimeException("No entity manager has been set.");

			return $this->entityManager;
		}

		/**
		 * Get the entity identifier field from entity meta data
		 */
		public function getObjectIdentifierFields ()
		{
			if ($this->getEntityMetaData()->isIdentifierComposite)
				return $this->getEntityMetaData()->getIdentifierFieldNames();

			return [$this->getEntityMetaData()->getSingleIdentifierFieldName()];
		}

		/**
		 * Get the entity meta data
		 */
		public function getEntityMetaData ()
		{
			return $this->getEntityManager()->getClassMetaData($this->getEntityId());
		}


		//////////////////////////////////////////////////////////

		private function setRelated ($table, $column, $value)
		{
			$this->related_join = $table;
			$this->related_column = $column;
			$this->related_value = $value;
		}

		/**
		 *
		 */
		private function buildUpdateQuery ($identifier, $data) : void
		{
			$data_source = $this->getEntityId();
			$em = $this->getEntityManager();
			$qb = $em->createQueryBuilder();

			$qb->update($data_source, $this->data_alias);

			$this->buildQueryAssociations($qb);

			foreach ($data as $field => $value) {
				$alias = str_replace('.', '_', $field);
				$qb->set($this->fields[$alias], ':'.$alias);
				$qb->setParameter($alias, $value);
			}

			$identifierAliases = $this->getObjectIdentifierFields();

			foreach ($identifierAliases as $identifierAlias)
				$qb->orWhere($qb->expr()->eq($this->fields[$identifierAlias], ':'.$identifierAlias));

			$this->query = $qb->getQuery();

			foreach ($identifierAliases as $identifierAlias)
				$this->query->setParameter($identifierAlias, $identifier);

			$this->query->setFirstResult(0);
			$this->query->setMaxResults(1);
		}

		private function buildSingleQuery ($identifier)
		{
			$em = $this->getEntityManager();
			$qb = $em->createQueryBuilder();

			$identifierAliases = $this->getObjectIdentifierFields();

			$this->buildQueryAssociations($qb);
			$this->buildQueryBuilder($qb, null, false);

			foreach ($identifierAliases as $identifierAlias)
				$qb->orWhere($qb->expr()->eq($this->fields[$identifierAlias], ':'.$identifierAlias));

			foreach ($identifierAliases as $identifierAlias)
				$qb->setParameter($identifierAlias, $identifier);

			$this->query = $qb->getQuery();

			$this->query->setFirstResult(0);
			$this->query->setMaxResults(1);
		}

		private function buildQuery ()
		{
			$em = $this->getEntityManager();
			$qb = $em->createQueryBuilder();

			$this->buildQueryAssociations($qb);
			$this->buildQueryBuilder($qb);
			$this->buildQuerySorts($qb);

			$this->query = $qb->getQuery();
		}

		private function buildQueryCount ()
		{
			$em = $this->getEntityManager();
			$qb = $em->createQueryBuilder();

			$this->buildQueryAssociations($qb);
			$this->buildQueryBuilder($qb, $qb->expr()->count(array_values($this->fields)[0]));

			$this->query = $qb->getQuery();
		}

		private function buildQueryTotal ()
		{
			$em = $this->getEntityManager();
			$qb = $em->createQueryBuilder();

			$this->buildQueryAssociations($qb);
			$this->buildQueryBuilder($qb, $qb->expr()->count(array_values($this->fields)[0]), false);

			$this->query = $qb->getQuery();
		}

		private function applyQueryParams ($setLimit = true)
		{
			foreach ($this->filterAliases as $alias => $value) {
				if (is_array($value) && !empty($value)) {
					$this->query->setParameter($alias, $value);
				} else if (is_scalar($value)) {
					$this->query->setParameter($alias, $value);
				} else if ($value instanceof \DateTime) {
					$value_range_1 = clone $value;
					$value_range_2 = clone $value;

					$value_range_2->add(new \DateInterval('PT24H'));

					$this->query->setParameter($alias.'_range_1', $value_range_1);
					$this->query->setParameter($alias.'_range_2', $value_range_2);
				}
			}

			if ($setLimit) {
				$this->query->setFirstResult($this->getPageStart());
				$this->query->setMaxResults($this->getPageSize());
			}
		}

		private function buildQueryAssociations ($qb)
		{
			$meta_fields = [];
			$meta_joins = [];

			foreach ($this->selectFields as $alias => $field) {
				$field = "$this->data_alias.$field";
				$branch = explode('.', $field);

				foreach ($branch as $level => $leaf) {
					$meta_field = $leaf;

					if (0 == $level) {
						$meta_alias = $alias;
					} else {
						$meta_alias = $branch[$level - 1];
						$meta_fields[$alias] = $meta_alias . '.' . $meta_field;

						if (1 < $level) {
							$meta_alias_field = $branch[$level - 2];
							$meta_joins[$meta_alias_field . '.' . $meta_alias] = $level;
						}
					}
				}
			}

			asort($meta_joins);

			foreach ($meta_joins as $repo => $level) {
				$branch = explode('.', $repo);

				if (!array_key_exists($branch[1], $this->joins))
					$this->joins[$branch[1]] = $repo;
			}

			$this->fields = $meta_fields;
		}

		private function buildQueryBuilder ($qb, $select = null, $add_where = true)
		{
			$this->buildQueryBuilderFrom($qb);
			$this->buildQueryBuilderJoins($qb);

			if ($add_where)
				$this->buildQueryBuilderWhere($qb);

			if (!$select)
				$this->buildQueryBuilderSelect($qb);
			else
				$this->buildQueryBuilderSelectCustom($qb, $select);
		}

		private function buildQuerySorts ($qb)
		{
			foreach ($this->sortableAliases  as $alias => $dir)
				$qb->addOrderBy($this->fields[$alias], $dir);
		}

		private function buildQueryBuilderSelect ($qb)
		{
			$column_list = [];

			foreach ($this->fields as $alias => $field)
				$column_list[$alias] = $field . ' AS ' . $alias;

			$qb->add('select', new Expr\Select($column_list));
		}

		private function buildQueryBuilderSelectCustom ($qb, $select)
		{
			$qb->add('select', $select);
		}

		private function buildQueryBuilderFrom ($qb)
		{
			$data_source = $this->getEntityId();

			$qb->from($data_source, $this->data_alias);
		}

		private function buildQueryBuilderJoins ( $qb )
		{
			$doRelated = true;

			foreach ( $this->joins as $alias => $join ) {
				if ( strtolower($this->data_alias . '.' . $this->related_join) == strtolower($join) ) {
					$this->related_join = $alias;
					$doRelated = false;
				}
				$qb->join($join, $alias);
			}

			if ( $doRelated && $this->related_join ) {
				$qb->join($this->data_alias . '.' . $this->related_join, $this->related_join);
			}
		}

		private function buildQueryBuilderWhere ($qb)
		{
			if ($this->related_value && $this->related_join && $this->related_column) {
				$table = $this->related_join;
				$column = $this->related_column;
				$qb->andWhere(new Expr\Comparison($table . '.' . $column[0], '=', $qb->expr()->literal($this->related_value)));
			}

			if (!empty($this->searchAliases) && !empty($this->searchAgainsts)) {
				$ands = [];
				$ors = [];

				foreach ($this->searchAgainsts as $against) {
					$terms = [];
					$term = $against['term'];
					$and = $against['and'];

					foreach ($this->searchAliases as $alias) {
						$terms[] = $qb->expr()->like(
							$this->fields[$alias],
							$qb->expr()->literal('%'.$term.'%')
						);
					}

					if ($and)
						$ands[] = call_user_func_array([$qb->expr(), 'orX'], $terms);
					else
						$ors[] = call_user_func_array([$qb->expr(), 'orX'], $terms);
				}

				if (!empty($ands)) {
					$andTerms = call_user_func_array([$qb->expr(), 'andX'], $ands);
					$qb->andWhere($qb->expr()->andX($andTerms));
				}

				if (!empty($ors)) {
					$orTerms = call_user_func_array([$qb->expr(), 'orX'], $ors);
					$qb->andWhere($qb->expr()->andX($orTerms));
				}
			}

			foreach ($this->filterAliases as $alias => $value) {
				if (is_array($value) && !empty($value)) {
					$qb->andWhere($qb->expr()->in($this->fields[$alias], ':'.$alias));
				} else if (is_scalar($value)) {
					$qb->andWhere($qb->expr()->eq($this->fields[$alias], ':'.$alias));
				} else if ( $value instanceof \DateTime ) {
					$qb->andWhere($qb->expr()->between($this->fields[$alias], ':'.$alias.'_range_1', ':'.$alias.'_range_2'));
				}
			}
		}

	}
}
