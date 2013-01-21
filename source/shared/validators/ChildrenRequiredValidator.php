<?php
namespace shared\validators;
/**
 * Specify a list of attributes which are required only when their parent value
 * is set.
 *
 * @author Dana Luther <dluther@internationalstudent.com>
 * @see http://www.yiiframework.com/doc/api/1.1/CRequiredValidator
 * @yiiVersion 1.1.8
 */
class ChildrenRequiredValidator extends \CRequiredValidator
{

	/**
	 * @var string The parent attribute which determines whether the children
	 * should be required or not.
	 */
	public $parentAttribute;
	
	/**
	 * The parent attribute value that will trigger the required setting for
	 * the children.
	 * @var mixed the parent value that will trigger the requirement
	 */
	public $match = true;

	/**
	 * Run the actual validation
	 * @param CModel $object
	 * @param string $attribute 
	 */
	protected function validateAttribute($object,$attribute)
	{
		$parent = $this->parentAttribute;
		
		// If the required parent isn't set, then we don't need 
		// to validate these children.
		if ( !$object->$parent || $object->$parent != $this->match )
			return;
		
		parent::validateAttribute($object,$attribute);
	}
	
}
?>
