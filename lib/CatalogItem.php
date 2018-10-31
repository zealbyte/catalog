<?php
namespace ZealByte\Catalog
{
	use SplObjectStorage;
	use RuntimeException;
	use BadMethodCallException;
	use InvalidArgumentException;
	use Symfony\Component\PropertyAccess\PropertyAccess;
	use ZealByte\Catalog\Inventory\CatalogIndexInterface;
	use ZealByte\Catalog\CatalogDefinitionInterface;
	use ZealByte\Catalog\DatumInterface;
	use ZealByte\Catalog\FieldInterface;

	class CatalogItem implements CatalogItemInterface
	{
		private $identifier;

		private $labelField;

		private $fieldStorage;

		private $dirtyFieldStorage;

		private $catalogIndex;

		private $definition;


		public function __construct (CatalogDefinitionInterface $definition, ?string $identifier = null, $data = null, ?bool $is_catalog = null)
		{
			if ($identifier)
				$this->identifier = $identifier;

			$this->definition = $definition;
			$this->catalogIndex = $definition->getCatalogIndex();

			$this->processDefinition($definition);
			$this->processData($data, $is_catalog);
		}

		/**
		 * {@inheritdoc}
		 */
		public function __get ($name)
		{
			return $this->offsetGet($name);
		}

		/**
		 * {@inheritdoc}
		 */
		public function __isset ($name)
		{
			return $this->offsetExists($name);
		}

		/**
		 * {@inheritdoc}
		 */
		public function __set ($name, $value)
		{
			$this->offsetSet($name, $value);
		}

		/**
		 * {@inheritdoc}
		 */
		public function __unset ($name)
		{
			$this->offsetUnset($name);
		}

		/**
		 * {@inheritdoc}
		 */
		public function __toString ()
		{
			return (string) $this->getLabel();
		}

		/**
		 * {@inheritdoc}
		 */
		public function jsonSerialize ()
		{
			$data = [];

			foreach ($this->fieldStorage as $field)
				$data[$field->getName()] = $this->fieldStorage[$field];

			return $data;
		}

		/**
		 * {@inheritdoc}
		 */
		public function offsetSet ($offset, $value)
		{
			foreach ($this->fieldStorage as $field) {
				if ($field->getName() == $offset && $field->hasWritablePropertyPath()) {
					//$fieldDataOptions = $field->getOptions();
					//$fieldDataType = $this->catalogIndex->getDataType($field->getDataType());
					//$value = $fieldDataType->reverseConvert($value, $fieldDataOptions);

					if (!($value === $this->fieldStorage[$field]))
						$this->updateField($field, $value);

					return;
				}
			}

			throw new InvalidArgumentException("Field $offset is not writable or is not defined!");
		}

		/**
		 * {@inheritdoc}
		 */
		public function offsetExists ($offset)
		{
			foreach ($this->fieldStorage as $field)
				if ($field->getName() == $offset)
					return true;

			return false;
		}

		/**
		 * {@inheritdoc}
		 */
		public function offsetUnset ($offset)
		{
			foreach ($this->fieldStorage as $field) {
				if ($field->getName() == $offset) {
					$this->fieldStorage->detach($field);

					return;
				}
			}
		}

		/**
		 * {@inheritdoc}
		 */
		public function offsetGet ($offset)
		{
			foreach ($this->fieldStorage as $field)
				if ($field->getName() == $offset)
					return $this->fieldStorage[$field];

			throw new InvalidArgumentException("Field $offset does is not defined!");
		}

		/**
		 * {@inheritdoc}
		 */
		public function rewind ()
		{
			return $this->fieldStorage->rewind();
		}

		/**
		 * {@inheritdoc}
		 */
		public function current ()
		{
			return $this->fieldStorage[$this->fieldStorage->current()];
		}

		/**
		 * {@inheritdoc}
		 */
		public function key ()
		{
			return $this->fieldStorage->current()->getName();
		}

		/**
		 * {@inheritdoc}
		 */
		public function next ()
		{
			return $this->fieldStorage->next();
		}

		/**
		 * {@inheritdoc}
		 */
		public function valid ()
		{
			return $this->fieldStorage->valid();
		}

		/**
		 *
		 */
		public function hydrate (CatalogDefinitionInterface $definition, ?string $identifier = null, $data, ?bool $is_catalog = null)
		{
			if (!($definition === $this->definition))
				throw new \Exception("Catalog item spec definition must match!");

			if ($identifier && $this->hasIdentifier())
				if (!($identifier === $this->getIdentifier()))
					throw new \Exception("Identifier myst match!");

			if ($identifier)
				$this->identifier = $identifier;

			$this->processDefinition($definition);
			$this->processData($data, $is_catalog);
		}

		/**
		 *
		 */
		public function getIdentifier () : string
		{
			if (!$this->hasIdentifier())
				throw new RuntimeException("Catalog item has not defined identifier!");

			return $this->identifier;
		}

		/**
		 *
		 */
		public function getLabel () : string
		{
			if (!$this->hasLabel())
				throw new RuntimeException("Catalog item has not defined a label!");

			return $this->fieldStorage[$this->labelField];
		}

		/**
		 *
		 */
		public function hasIdentifier () : bool
		{
			return $this->identifier ? true : false;
		}

		/**
		 *
		 */
		public function hasLabel () : bool
		{
			return ($this->labelField && $this->fieldStorage[$this->labelField]) ? true : false;
		}

		/**
		 *
		 */
		public function isDirty (?string $name = null)
		{
			$dirty = ($this->dirtyFieldStorage) ? true : false;

			if ($dirty && $name) {
				foreach ($this->fieldStorage as $field) {
					if ($field->getName() == $name) {
						if ($this->dirtyFieldStorage->contains($field))
							return true;

						return false;
					}
				}

				throw new InvalidArgumentException("Field $name is not defined!");
			}

			return $dirty;
		}

		/**
		 *
		 */
		private function updateField (FieldInterface $field, /* */ $value)
		{
			if (!$this->dirtyFieldStorage)
				$this->dirtyFieldStorage = new SplObjectStorage();

			if (!$this->dirtyFieldStorage->contains($field))
				$this->dirtyFieldStorage->attach($field, []);

			$dirt = $this->dirtyFieldStorage[$field];
			$dirt[] = $this->fieldStorage[$field];

			$this->dirtyFieldStorage[$field] = $dirt;
			$this->fieldStorage[$field] = $value;
		}

		/**
		 *
		 */
		private function processDefinition (CatalogDefinitionInterface $definition)
		{
			$this->dirtyFieldStorage = null;

			if (!$this->fieldStorage)
				$this->fieldStorage = new SplObjectStorage();

			foreach ($definition->getFields() as $field) {
				$this->fieldStorage->attach($field);

				if ($definition->hasLabelField() && $definition->getLabelField() == $field)
					$this->labelField = $field;
			}
		}

		/**
		 *
		 */
		private function processData ($data = null, $is_catalog = null) : void
		{
			$accessor = PropertyAccess::createPropertyAccessor();

			foreach ($this->fieldStorage as $field) {
				if (($is_catalog ? $field->hasCatalogPropertyPath() : $field->hasItemPropertyPath())) {
					$propertyPath = ($is_catalog ? $field->getCatalogPropertyPath() : $field->getItemPropertyPath());

					if ($data && !$accessor->isReadable($data, $propertyPath))
						throw new \Exception("Cannot access $propertyPath!");

					$fieldDataOptions = $field->getOptions();
					$fieldDataType = $this->catalogIndex->getDataType($field->getDataType());

					if ($data)
						$this->fieldStorage[$field] = $fieldDataType->convert($accessor->getValue($data, $propertyPath), $fieldDataOptions);
					else
						$this->fieldStorage[$field] = null;
				}
			}
		}

	}
}
