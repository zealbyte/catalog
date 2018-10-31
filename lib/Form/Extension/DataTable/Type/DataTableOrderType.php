<?php
namespace ZealByte\Catalog\Form\Extension\DataTable\Type
{
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormView;
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\Extension\Core\Type as FormType;
	use ZealByte\Catalog\SpecInterface;
	use ZealByte\Catalog\Form\Extension\DataTable\Model;

	class DataTableOrderType extends AbstractType
	{
		public function buildForm (FormBuilderInterface $builder, array $options)
		{
			$builder
				->add('column', FormType\IntegerType::class)
				->add('dir', FormType\ChoiceType::class, [
					'choices' => [
						'Ascending' => 'asc',
						'Descending' => 'desc',
					]
				]);
		}

		/**
		 * {@inheritdoc}
		 */
		public function configureOptions (OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'spec' => null,
				'data_class' => Model\DataTableOrderModel::class
			]);

			$resolver->setAllowedTypes('spec', SpecInterface::class);
			$resolver->setRequired('spec');
		}

	}
}
