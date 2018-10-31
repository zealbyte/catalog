<?php
namespace ZealByte\Catalog\Form\Extension\DataTable\EventListener
{
	use Traversable;
	use Symfony\Component\Form\FormEvent;
	use Symfony\Component\Form\FormEvents;
	use Symfony\Component\EventDispatcher\EventSubscriberInterface;
	use ZealByte\Catalog\SpecInterface;
	use ZealByte\Catalog\DatumInterface;
	use ZealByte\Catalog\CatalogDefinitionInterface;
	use ZealByte\Catalog\CatalogBuilderInterface;
	use ZealByte\Catalog\Form\Extension\DataTable\Type\DataTableColumnType;

	class DataTableColumnCollectionFormSubscriber implements EventSubscriberInterface
	{
		protected $spec;

		protected $definition;

		protected $builder;

		protected $translator;


		public static function getSubscribedEvents ()
		{
			return [
				FormEvents::PRE_SET_DATA => 'preSetData',
				FormEvents::PRE_SUBMIT => 'preSubmit',
			];
		}

		public function __construct (SpecInterface $spec, CatalogDefinitionInterface $definition, CatalogBuilderInterface $builder, $translator)
		{
			$this->spec = $spec;
			$this->definition = $definition;
			$this->builder = $builder;
			$this->translator = $translator;
		}

		public function preSetData (FormEvent $event)
		{
			$coldata = [];
			$form = $event->getForm();
			$data = $event->getData();

			// Remove all columns
			foreach ($form as $name => $child)
				$form->remove($child);

			// Add columns from spec
			foreach ($this->builder->getDatums() as $itr => $datum) {
				if ($datum->hasColumnType()) {
					$form->add($itr, DataTableColumnType::class, [
						'spec' => $this->spec,
						'label' => $datum->getName(),
					]);
				}
			}
		}

		public function preSubmit (FormEvent $event)
		{
		}

	}
}

