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

	class DataTableSearchType extends AbstractType
	{
		const FORM_NAME = 'data_table_search';

		public function buildForm (FormBuilderInterface $builder, array $options)
		{
			$builder
				->add('value', FormType\HiddenType::class, [
					'required' => false,
				])
				->add('regex', FormType\HiddenType::class, [
					'required' => false,
				]);
		}

		/**
		 * {@inheritdoc}
		 */
		public function configureOptions (OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'data_class' => Model\DataTableSearchModel::class,
			]);
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
