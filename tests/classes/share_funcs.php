<?php

namespace Fuzzy_Search\Test;

/**
 * 共通機能
 */
trait Share_funcs 
{
	/**
	 * 被検査メソッドのアクセス制限を解除する
	 * 
	 * @param string $class_name  メソッドの定義されているクラス名
	 * @param string $method_name メソッド名
	 * @return ReflectionMethod   メソッド
	 */
	protected function get_accessible_method($class_name, $method_name)
	{
		$class = new \ReflectionClass($class_name);
	
		$method = $class->getMethod($method_name);
		$method->setAccessible(true);
	
		return $method;
	}

	/**
	 * 被検査プロパティのアクセス制限を解除する
	 *
	 * @param string $class_name  プロパティの定義されているクラス名
	 * @param string $method_name プロパティ名
	 * @return ReflectionProperty プロパティ
	 */
	protected function get_accessible_property($class_name, $property_name)
	{
		$class = new \ReflectionClass($class_name);
	
		$property = $class->getProperty($property_name);
		$property->setAccessible(true);
	
		return $property;
	}
}