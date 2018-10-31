<?php
namespace ZealByte\Catalog\Twig\Extension
{
	use Twig_Extension;
	use Twig_SimpleFunction;
	use Twig_ExtensionInterface;
	use ZealByte\Catalog\Inventory\CatalogFactory;
	use ZealByte\Catalog\DatumInterface;
	use ZealByte\Catalog\SpecInterface;

	class ZealByteCatalogExtension extends Twig_Extension implements Twig_ExtensionInterface
	{
		private $catalogFactory;

		public function __construct (CatalogFactory $catalog_factory)
		{
			$this->catalogFactory = $catalog_factory;
		}

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
		public function getFunctions ()
		{
			return [
				new Twig_SimpleFunction('datatable_columns', [$this, 'datatableColumns'], ['is_safe' => ['html','json','javascript','js']])
			];
		}

		public function datatableColumns (SpecInterface $spec) : ?array
		{
			if (!$spec)
				return null;

			$columns = [];
			$builder = $this->catalogFactory->getCatalogBuilder($spec);

			foreach ($builder->getDatums() as $datum)
				if ($datum->hasColumnType())
					$columns[] = $this->datatableColumnsColumn($datum);

			return $columns;
		}

		private function datatableColumnsColumn (DatumInterface $datum) : array
		{
			$columnOptions = $datum->getColumnOptions();
			$name = $datum->getName();
			$title = isset($columnOptions['title']) ? $columnOptions['title'] : $name;

			if (isset($this->translator))
				$title = $this->translator->trans($title);

			$column = array_replace([
				'name' => $name,
				'data' => "datums.$name",
				'type' => $datum->getColumnType(),
			], $columnOptions, ['title' => $title]);

			return $column;
		}

	}
}
