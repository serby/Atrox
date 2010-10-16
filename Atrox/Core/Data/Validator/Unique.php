<?php
/**
 * @package Core
 * @subpackage Data/Validator
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 */

/**
 * Validates that the value is an Unique for this datasource
 *
 * @author Tom Smith <thomas.smith@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage Data/Validator
 */
class Atrox_Core_Data_Validator_Unique implements Atrox_Core_Data_IValidator {

	/**
	 *
	 * @see Core/Data/Atrox_Core_Data_IValidator#validate($value)
	 */
	public function validate($value, Atrox_Core_Data_Property $property) {
		$dataSource = $property->getDataSource();
		$filter = $property->getDataSource()->makeFilter();
		$filter->addConditional($dataSource->getTableName(), $property->getName(), $value);
		$count = $dataSource->count($filter);
		return $count <= 1 ? true : "'" . $property->getDescription() . "' must be unique. '{$value}' is already used";
	}
}