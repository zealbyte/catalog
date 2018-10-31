<?php
namespace ZealByte\Catalog\Form\Extension\DataTable\Type
{
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormView;
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\Extension\Core\Type as FormType;
	use ZealByte\Catalog\Form\Type\CatalogTypeTrait;
	use ZealByte\Catalog\Form\Extension\DataTable\EventListener\DataTableFilterFormSubscriber;
	use ZealByte\Catalog\SpecInterface;
	use ZealByte\Catalog\Form\Extension\DataTable\Model;

	class DataTableColumnType extends AbstractType
	{
		use CatalogTypeTrait;

		const FORM_NAME = 'data_table_column';

		public function buildForm (FormBuilderInterface $builder, array $options)
		{
			$catalogDefinition = $this->getCatalogFactory()->getDefinition($options['spec']);
			$catalogBuilder = $this->getCatalogFactory()->getCatalogBuilder($options['spec']);

			$builder
				->add('data', FormType\HiddenType::class, [
					'required' => false,
					'mapped' => false,
				])
				->add('name', FormType\HiddenType::class, [
					'required' => true,
				])
				->add('searchable', FormType\HiddenType::class, [
					'required' => false,
				])
				->add('orderable', FormType\HiddenType::class, [
					'required' => false,
				]);

			$dataTableFilterListener = new DataTableFilterFormSubscriber(
				$catalogDefinition,
				$catalogBuilder
			);

			$builder->addEventSubscriber($dataTableFilterListener);
		}

		/**
		 * {@inheritdoc}
		 */
		public function configureOptions (OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'spec' => null,
				'data_class' => Model\DataTableColumnModel::class
			]);

			$resolver->setAllowedTypes('spec', SpecInterface::class);
			$resolver->setRequired('spec');
		}

		/**
		 * {@inheritdoc}
		 */
		public function getBlockPrefix ()
		{
			return self::FORM_NAME;
		}


	}
}
