<?php
namespace ZealByte\Catalog\Form\Extension\Catalog\Type
{
	use Symfony\Component\Form\AbstractTypeExtension;
	use Symfony\Component\Form\FormTypeInterface;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Form\FormView;
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\Form\Extension\Core\Type\FormType;
	use Symfony\Component\OptionsResolver\OptionsResolver;

	class DateFilterType extends AbstractTypeExtension
	{
		/**
		 * {@inheritdoc}
		 */
		public function buildForm (FormBuilderInterface $builder, array $options)
		{
		}

		/**
		 * {@inheritdoc}
		 */
		public function buildView (FormView $view, FormInterface $form, array $options)
		{
		}

		/**
		 * {@inheritdoc}
		 */
		public function finishView (FormView $view, FormInterface $form, array $options)
		{
		}

		/**
		 * {@inheritdoc}
		 */
		public function configureOptions (OptionsResolver $resolver)
		{
		}

		/**
		 * {@inheritdoc}
		 */
		public function getExtendedType ()
		{
			return FormType::class;
		}
	}
}
