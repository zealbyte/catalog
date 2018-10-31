<?php
namespace ZealByte\Catalog\Form\Extension\Catalog\Type
{
	use Symfony\Component\Form\FormView;
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\Extension\Core\Type as FormType;
	use ZealByte\Catalog\Form\EventListener\DataTableFilterFormListener;
	use ZealByte\Catalog\SpecInterface;

	class ChoiceFilterType implements FilterTypeInterface
	{
		public function buildForm (FormInterface $builder, array $options)
		{
			$builder
				->add('value', FormType\ChoiceType::class, $options);
		}

	}
}

