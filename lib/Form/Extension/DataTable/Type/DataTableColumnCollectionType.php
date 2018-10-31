<?php
namespace ZealByte\Catalog\Form\Extension\DataTable\Type
{
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Form\FormView;
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\OptionsResolver\Options;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Translation\TranslatorInterface;
	use ZealByte\Catalog\Form\Type\CatalogTypeTrait;
	use ZealByte\Catalog\Form\Extension\DataTable\EventListener\DataTableColumnCollectionFormSubscriber;
	use ZealByte\Catalog\SpecInterface;

	class DataTableColumnCollectionType extends AbstractType
	{
		use CatalogTypeTrait;

		const FORM_NAME = 'data_table_column_collection';

		private $translator;

		public function setTranslator (TranslatorInterface $translator)
		{
			$this->translator = $translator;
		}

		/**
		 * {@inheritdoc}
		 */
		public function buildForm (FormBuilderInterface $builder, array $options)
		{
			$catalogDefinition = $this->getCatalogFactory()->getDefinition($options['spec']);
			$catalogBuilder = $this->getCatalogFactory()->getCatalogBuilder($options['spec']);

			$columnCollectionListener = new DataTableColumnCollectionFormSubscriber(
				$options['spec'],
				$catalogDefinition,
				$catalogBuilder,
				$this->translator
			);

			$builder->addEventSubscriber($columnCollectionListener);
		}

		/**
		 * {@inheritdoc}
		 */
		public function buildView (FormView $view, FormInterface $form, array $options)
		{
			/*
			$view->vars = array_replace($view->vars, array(
				'allow_add' => $options['allow_add'],
				'allow_delete' => $options['allow_delete'],
			));

			if ($form->getConfig()->hasAttribute('prototype')) {
				$prototype = $form->getConfig()->getAttribute('prototype');
				$view->vars['prototype'] = $prototype->setParent($form)->createView($view);
			}
			 */
		}

		/**
		 * {@inheritdoc}
		 */
		public function finishView (FormView $view, FormInterface $form, array $options)
		{
			/*
			if ($form->getConfig()->hasAttribute('prototype') && $view->vars['prototype']->vars['multipart']) {
				$view->vars['multipart'] = true;
			}
			*/
		}

		/**
		 * {@inheritdoc}
		 */
		public function configureOptions (OptionsResolver $resolver)
		{
			/*
			$entryOptionsNormalizer = function (Options $options, $value) {
				$value['block_name'] = 'entry';

				return $value;
			};
			 */

			$resolver->setDefaults([
				'spec' => null,
				//'csrf_token_id' => self::FORM_NAME,
				//'data_class' => null,
			]);

			$resolver->setAllowedTypes('spec', SpecInterface::class);
			$resolver->setRequired('spec');

			//$resolver->setNormalizer('entry_options', $entryOptionsNormalizer);
			//$resolver->setAllowedTypes('delete_empty', array('bool', 'callable'));
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
