<?php
namespace ZealByte\Catalog\Form\Extension\DataTable\EventListener
{
	use Symfony\Component\Form\FormEvent;
	use Symfony\Component\Form\FormEvents;
	use Symfony\Component\EventDispatcher\EventSubscriberInterface;
	use Symfony\Component\Form\Extension\Core\Type\HiddenType;
	use ZealByte\Platform\ZealBytePlatform;
	use ZealByte\Catalog\CatalogDefinitionInterface;
	use ZealByte\Catalog\CatalogBuilderInterface;
	use ZealByte\Catalog\Form\Extension\DataTable\Type\DataTableFilterType;

	class DataTableFilterFormSubscriber implements EventSubscriberInterface
	{
		const ON_PRE_SET_DATA_PRIORITY = ZealBytePlatform::REGULAR_PRIORITY;

		const ON_PRE_SUBMIT_DATA_PRIORITY = ZealBytePlatform::REGULAR_PRIORITY;

		protected $definition;

		protected $builder;

		public static function getSubscribedEvents ()
		{
			return [
				FormEvents::PRE_SET_DATA => [
					['onPreSetData', self::ON_PRE_SET_DATA_PRIORITY],
				],
				FormEvents::PRE_SUBMIT => [
					['onPreSubmit', self::ON_PRE_SUBMIT_DATA_PRIORITY],
				],
			];
		}

		public function __construct (CatalogDefinitionInterface $definition, CatalogBuilderInterface $builder)
		{
			$this->definition = $definition;
			$this->builder = $builder;
		}

		public function onPreSetData (FormEvent $event)
		{
			$form = $event->getForm();
			$data = $event->getData();

			$datumName = $data->getName();

			if ($this->builder->hasDatum($datumName)) {
				$form->remove('search');
				$datum = $this->builder->getDatum($datumName);

				if ($datum->hasFilterType()) {
					$form->add('search', DataTableFilterType::class, [
						'label' => $datumName,
						'filter_type' => $datum->getFilterType(),
						'filter_options' => $datum->getFilterOptions(),
					]);
				}
			}
		}

		public function onPreSubmit (FormEvent $event)
		{
			$data = $event->getData();
			$datumName = isset($data['name']) ? $data['name'] : null;

			if ($datumName && $this->builder->hasDatum($datumName)) {
				$datum = $this->builder->getDatum($datumName);

				if (!$datum->hasFilterType() && isset($data['search'])) {
					unset($data['search']);
				}
			}

			$event->setData($data);
		}

	}
}
