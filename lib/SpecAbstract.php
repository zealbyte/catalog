<?php
namespace ZealByte\Catalog
{
	use Symfony\Component\Form\FormBuilderInterface;
	use ZealByte\Catalog\CatalogBuilderInterface;
	use ZealByte\Catalog\CatalogMapperInterface;

	/**
	 */
	abstract class SpecAbstract implements SpecInterface
	{
		/**
		 * {@inheritdoc}
		 */
		abstract public function buildCatalogMap (CatalogMapperInterface $mapper) : void;

		/**
		 * {@inheritdoc}
		 */
		public function buildCatalogView (CatalogBuilderInterface $builder) : void
		{
		}

		/**
		 * {@inheritdoc}
		 */
		public function create (string $identifier = null): void
		{
			$this->prePersist($identifier);

			foreach ($this->extensions as $extension)
				$extension->prePersist($this, $identifier);

			$this->getModelManager()->create($this, $identifier);

			$this->postPersist($identifier);

			foreach ($this->extensions as $extension)
				$extension->postPersist($this, $identifier);
		}

		/**
		 * {@inheritdoc}
		 */
		public function update (string $identifier) : void
		{
			$this->preUpdate($identifier);

			foreach ($this->extensions as $extension)
				$extension->preUpdate($this, $identifier);

			$this->getModelManager()->update($this, $identifier);

			$this->postUpdate($identifier);

			foreach ($this->extensions as $extension)
				$extension->postUpdate($this, $identifier);
		}

		/**
		 * {@inheritdoc}
		 */
		public function delete (string $identifier): void
		{
			$this->preRemove($identifier);

			foreach ($this->extensions as $extension)
				$extension->preRemove($this, $identifier);

			$this->getModelManager()->delete($this, $identifier);

			$this->postRemove($identifier);

			foreach ($this->extensions as $extension)
				$extension->postRemove($this, $identifier);
		}

		protected function preUpdate (string $identifier) : void
		{
		}

		protected function postUpdate (string $identifier) : void
		{
		}

		protected function prePersist (string $identifier) : void
		{
		}

		protected function postPersist (string $identifier) : void
		{
		}

		protected function preRemove (string $identifier) : void
		{
		}

		protected function postRemove (string $identifier) : void
		{
		}

		public function checkAccess ($action)
		{
			$access = array_merge([
				'acl'                     => 'MASTER',
				'export'                  => 'EXPORT',
				'historyCompareRevisions' => 'EDIT',
				'historyViewRevision'     => 'EDIT',
				'history'                 => 'EDIT',
				'edit'                    => 'EDIT',
				'show'                    => 'VIEW',
				'create'                  => 'CREATE',
				'delete'                  => 'DELETE',
				'batchDelete'             => 'DELETE',
				'list'                    => 'LIST',
			], $this->getAccessMapping());

			if (!array_key_exists($action, $access)) {
				throw new InvalidArgumentException(sprintf('Action "%s" could not be found in access mapping. Please make sure your action is defined into your admin class accessMapping property.', $action));
			}

			if (!is_array($access[$action])) {
				$access[$action] = [$access[$action]];
			}

			foreach ($access[$action] as $role) {
				if ( false === $this->isGranted($role) ) {
					throw new AccessDeniedException(sprintf('Access Denied to the action %s and role %s', $action, $role));
				}
			}
		}

	}
}
