<?php

namespace Fuzzy_Search;

trait Traits_Number
{
	/**
	 * @var array $arabic_chars アラビア数字の変換用データ
	 */
	protected static $arabic_chars = array('０','１','２','３','４','５','６','７','８','９');
	
	/**
	 * @var array $arabic_chars 漢数字の変換用データ
	 */
	protected static $chinese_chars = array('〇','一','二','三','四','五','六','七','八','九');
	
	/**
	 * すべての漢数字をアラビア数字に変換
	 *
	 * 例) 五 -> ５
	 *
	 * @param string $str キーワード語句
	 * @return string 変換後のキーワード語句
	 */
	protected static function chinese_to_arabic($str = '')
	{
		return str_replace(self::$chinese_chars, range(0, 9), $str);
	}
	
	/**
	 * すべてのアラビア数字を漢数字に変換
	 *
	 * 例) ７ -> 七
	 *
	 * @param string $str キーワード語句
	 * @return string 変換後のキーワード語句
	 */
	protected static function arabic_to_chinese($str = '')
	{
		$str = str_replace(self::$arabic_chars, range(0, 9), $str);
		
		return str_replace(range(0, 9), self::$chinese_chars, $str);
	}
	
	/**
	 * アラビア数字を漢数字桁名表記に変換
	 *
	 * 例) １２００ -> 千二百
	 *
	 * @param  string $str キーワード語句
	 * @return string 変換後のキーワード語句
	 */
	protected static function arabic_to_chinese_digit_char($str = '')
	{
		$str = self::chinese_to_arabic($str);
		
		$str = mb_convert_kana($str, 'n');
		
		$str = preg_replace('/([1-9])0{4}/u'          , '\1万'  , $str);
		$str = preg_replace('/([1-9])0{3}(\d)/u'      , '\1万\2', $str);
		$str = preg_replace('/([1-9])0{2}(\d{2})/u'   , '\1万\2', $str);
		$str = preg_replace('/([1-9])0{1}(\d{3})/u'   , '\1万\2', $str);
		$str = preg_replace('/([1-9])(\d{4})/u'       , '\1万\2', $str);
		$str = preg_replace('/([1-9])0{3}/u'          , '\1千'  , $str);
		$str = preg_replace('/([1-9])0{2}(\d)/u'      , '\1千\2', $str);
		$str = preg_replace('/([1-9])0{1}(\d{2})/u'   , '\1千\2', $str);
		$str = preg_replace('/([1-9])(\d{3})/u'       , '\1千\2', $str);
		$str = preg_replace('/([1-9])0{2}/u'          , '\1百'  , $str);
		$str = preg_replace('/([1-9])0{1}(([1-9]))/u' , '\1百\2', $str);
		$str = preg_replace('/([1-9])(\d{2})/u'       , '\1百\2', $str);
		$str = preg_replace('/([1-9])([1-9])/u'       , '\1十\2', $str);
		$str = preg_replace('/([1-9])([0])/u'         , '\1十'  , $str);
		$str = preg_replace('/1([千百十])/u'           , '\1'    , $str);
		
		$str = str_replace(range(0, 9), self::$chinese_chars, $str);
	
		return $str;
	}
}