<?php
namespace ZealByte\Catalog\Component
{
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\Form\FormView;
	use ZealByte\Platform\Component\ComponentInterface;
	use ZealByte\Platform\Component\DispatcherComponentAbstract;
	use ZealByte\Catalog\Inventory\CatalogFactory;
	use ZealByte\Catalog\InventoryRequestInterface;
	use ZealByte\Catalog\SpecInterface;
	use ZealByte\Catalog\CatalogItem;
	use ZealByte\Catalog\Catalog;
	use ZealByte\Catalog\CatalogEvents;
	use ZealByte\Catalog\Event\CatalogRequestEventInterface;
	use ZealByte\Catalog\Form\Extension\DataTable\Event\DataTableFormEvent;
	use ZealByte\Catalog\Form\Extension\DataTable\Event\DataTableProcessFormEvent;
	use ZealByte\Catalog\Form\Extension\DataTable\Event\DataTableRowEvent;
	use ZealByte\Catalog\Form\Extension\DataTable\DataTableResponse;
	use ZealByte\Catalog\ZealByteCatalog;

	class CatalogComponent extends DispatcherComponentAbstract
	{
		const BLOCK_NAME = 'catalog_component';

		const VIEW = '@Catalog/components.html.twig';

		private $request;


		public function __construct (?string $alias = null, ?string $category = null)
		{
			if ($alias)
				$this->setAlias($alias);

			if ($category)
				$this->setCategory($category);
		}

		/**
		 * {@inheritdoc}
		 */
		public function getParameters () : array
		{
			$this->stat();

			return parent::getParameters();
		}

		/**
		 * {@inheritdoc}
		 */
		public function getData ()
		{
			$this->stat();
			$this->setForm();

			return parent::getData();
		}

		/**
		 *
		 */
		public function getAlias () : string
		{
			return $this->getParameter('alias');
		}

		/**
		 *
		 */
		public function getCategory () : string
		{
			return $this->getParameter('category');
		}

		/**
		 *
		 */
		public function getForm () : FormView
		{
			return $this->getParameter('form');
		}

		/**
		 *
		 */
		public function getRequest () : Request
		{
			return $this->request;
		}

		/**
		 *
		 */
		public function hasAlias () : bool
		{
			return $this->hasParameter('alias');
		}

		/**
		 *
		 */
		public function hasCategory () : bool
		{
			return $this->hasParameter('category');
		}

		/**
		 *
		 */
		public function hasForm () : bool
		{
			return $this->hasParameter('form');
		}

		/**
		 *
		 */
		public function hasRequest () : bool
		{
			return ($this->request) ? true : false;
		}

		/**
		 *
		 */
		public function setAlias (string $alias) : self
		{
			$this->setParameter('alias', $alias);

			return $this;
		}

		/**
		 *
		 */
		public function setCategory (string $category) : self
		{
			$this->setParameter('category', $category);

			return $this;
		}

		/**
		 *
		 */
		public function setForm (?FormView $form = null) : self
		{
			$this->setParameter('form', $form);

			return $this;
		}

		/**
		 *
		 */
		public function setRequest (Request $request) : self
		{
			$this->request = $request;

			return $this;
		}

		/**
		 *
		 */
		public function setRelationshipAlias (string $relationship_alias) : self
		{
			$this->setParameter('related_alias', $relationship_alias);

			return $this;
		}

		/**
		 *
		 */
		public function setRelationshipId (string $relationship_id) : self
		{
			$this->setParameter('related_id', $relationship_id);

			return $this;
		}

		/**
		 *
		 */
		protected function stat () : void
		{
			if (!$this->hasForm())
				$this->createForm();
		}

		/**
		 *
		 */
		protected function addEventProps (CatalogRequestEventInterface $event)
		{
			if ($this->hasRequest())
				$event->setRequest($this->getRequest());

			if ($this->hasAlias())
				$event->setAlias($this->getAlias());

			if ($this->hasCategory())
				$event->setCategory($this->getCategory());
		}

		/**
		 *
		 */
		private function createForm () : void
		{
		}

	}
}
