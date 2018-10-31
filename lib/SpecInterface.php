<?php
namespace ZealByte\Catalog
{
	use Symfony\Component\Form\FormBuilderInterface;
	use ZealByte\Catalog\CatalogBuilderInterface;
	use ZealByte\Catalog\CatalogMapperInterface;

	/**
	 * Interface SpecInterface.
	 *
	 * @author Phil Martella <philmartella@live.com>
	 */
	interface SpecInterface
	{
		/**
		 */
		public function buildCatalogMap (CatalogMapperInterface $mapper) : void;

		/**
		 */
		public function buildCatalogView (CatalogBuilderInterface $builder) : void;

	}
}
