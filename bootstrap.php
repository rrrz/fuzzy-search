<?php
/**
 * Fuzzy Search
 *
 * @package    Fuzzy_Search
 * @version    1.0
 * @author     Takeshige Nii
 * @license    MIT License
 * @copyright  2015 rz Inc.
 * @link       http://rrr-z.jp/
 */

\Autoloader::add_core_namespace('Fuzzy_Search');

\Autoloader::add_classes(array(
	'Fuzzy_Search\\Fuzzy'                            => __DIR__.'/classes/fuzzy.php',
	'Fuzzy_Search\\Fuzzy_Address'                    => __DIR__.'/classes/fuzzy/address.php',
	'Fuzzy_Search\\Fuzzy_Tell'                       => __DIR__.'/classes/fuzzy/tell.php',
	'Fuzzy_Search\\Fuzzy_Zip'                        => __DIR__.'/classes/fuzzy/zip.php',
	'Fuzzy_Search\\Traits_Number'                    => __DIR__.'/classes/traits/number.php',
	'Fuzzy_Search\\Traits_Symbol'                    => __DIR__.'/classes/traits/symbol.php',
));
