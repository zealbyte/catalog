<?php
namespace ZealByte\Catalog\Form\Extension\DataTable\Type
{
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormView;
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\Extension\Core\Type as FormType;
	use ZealByte\Catalog\Form\Extension\DataTable\Model;

	class DataTableFilterType extends AbstractType
	{
		const FORM_NAME = 'data_table_filter';

		public function buildForm (FormBuilderInterface $builder, array $options)
		{
			$builder
				->add('regex', FormType\HiddenType::class);

			if ($options['filter_type'])
				$builder->add('value', $options['filter_type'], $options['filter_options']);
		}

		/**
		 * {@inheritdoc}
		 */
		public function configureOptions (OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'data_class' => Model\DataTableSearchModel::class,
				'filter_type' => FormType\TextType::class,
				'filter_options' => [],
			]);

			$resolver->setAllowedTypes('filter_type', 'string');
			$resolver->setAllowedTypes('filter_options', 'array');
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
