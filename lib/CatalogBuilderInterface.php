<?php
namespace ZealByte\Catalog
{
	use Symfony\Component\Form\FormTypeInterface;
	use ZealByte\Catalog\DatumInterface;

	interface CatalogBuilderInterface
	{
		public function addDatum (DatumInterface $datum) : CatalogBuilderInterface;

		public function getDatum (string $name) : DatumInterface;

		public function getDatums () : array;

		public function hasDatum (string $name) : bool;

		public function hasDatums () : bool;
	}
}
