<?php
namespace ZealByte\Catalog\Form\Extension\DataTable\Type
{
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormView;
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\ReversedTransformer;
	use Symfony\Component\Form\Extension\Core\Type as FormType;
	use ZealByte\Catalog\Form\Type\CatalogTypeTrait;
	use ZealByte\Catalog\Form\Extension\DataTable\DataTransformer\InventoryToDatatableTransformer;
	use ZealByte\Catalog\Form\Extension\DataTable\DataTransformer\InventoryToDatatableResponseTransformer;
	use ZealByte\Catalog\Form\EventListener\DataTableFormListener;
	use ZealByte\Catalog\SpecInterface;
	use ZealByte\Catalog\Form\Extension\DataTable\Model;
	use ZealByte\Catalog\InventoryRequestInterface;

	class DataTableType extends AbstractType
	{
		use CatalogTypeTrait;

		const FORM_NAME = 'data_table';

		public function buildForm (FormBuilderInterface $builder, array $options)
		{
			$builder
				->add('draw', FormType\HiddenType::class, [
					'required' => true,
				])
				->add('start', FormType\HiddenType::class, [
					'required' => true,
				])
				->add('length', FormType\HiddenType::class, [
					'required' => true,
				])
				->add('search', DataTableSearchType::class, [
					'required' => false,
				])
				->add('order', FormType\CollectionType::class, [
					'allow_add' => true,
					'allow_delete' => true,
					'delete_empty' => true,
					'prototype' => false,
					'entry_type' => DataTableOrderType::class,
					'entry_options' => [
						'spec' => $options['spec'],
					],
				])
				->add('columns', DataTableColumnCollectionType::class, [
					'required' => true,
					'spec' => $options['spec']
				]);

			$builder
				->addModelTransformer(new InventoryToDatatableTransformer($this->getCatalogFactory(), $options['spec']));
		}

		/**
		 * {@inheritdoc}
		 */
		public function configureOptions (OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'spec' => null,
				'csrf_token_id' => self::FORM_NAME,
				'data_class' => null,
				'data_class' => Model\DataTableModel::class,
			]);

			$resolver->setAllowedTypes('spec', SpecInterface::class);
			$resolver->setRequired('spec');
		}

		public function buildView (FormView $view, FormInterface $form, array $options)
		{
			$view->vars = array_replace($view->vars, [
				'spec' => $options['spec'],
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
