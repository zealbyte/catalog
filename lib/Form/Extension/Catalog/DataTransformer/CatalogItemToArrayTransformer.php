<?php
namespace ZealByte\Catalog\Form\Extension\Catalog\DataTransformer
{
	use Symfony\Component\Form\DataTransformerInterface;
	use ZealByte\Catalog\CatalogMapperInterface;
	use ZealByte\Catalog\CatalogBuilderInterface;
	use ZealByte\Catalog\CatalogItem;

	class CatalogItemToArrayTransformer implements DataTransformerInterface
	{
		private $mapper;

		private $builder;

		public function __construct (CatalogMapperInterface $mapper, CatalogBuilderInterface $builder)
		{
			$this->mapper = $mapper;
			$this->builder = $builder;
		}

		public function transform ($catalog_item)
		{
			$data = [
				'_identifier' => ($catalog_item && $catalog_item->hasIdentifier()) ? $catalog_item->getIdentifier() : null,
				'_label' => ($catalog_item && $catalog_item->hasLabel()) ? $catalog_item->getLabel() : null,
			];

			if ($this->builder && $this->builder->hasDatums()) {
				foreach ($this->builder->getDatums() as $datum) {
					$datumName = $datum->getName();
					$data[$datumName] = [];

					foreach ($datum->getFieldNames() as $fieldName) {
						$data[$datumName][$fieldName] =
							($catalog_item) ? $catalog_item[$fieldName] : null;
					}
				}
			}

			return $data;
		}

		public function reverseTransform ($value)
		{
			$data = [];
			$identifier = null;

			if (isset($value['_identifier']))
				$identifier = $value['_identifier'];

			if ($this->builder && $this->builder->hasDatums()) {
				foreach ($this->builder->getDatums() as $datum) {
					$datumName = $datum->getName();

					if (isset($value[$datumName]) && is_array($value[$datumName]))
						foreach ($datum->getFieldNames() as $fieldName) {
							$data[$fieldName] =
								isset($value[$datumName][$fieldName]) ? $value[$datumName][$fieldName] : null;
					}
				}
			}

			return new CatalogItem($this->mapper, $this->builder, $identifier, $data);
		}

	}
}
