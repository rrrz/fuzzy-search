<?php

namespace Fuzzy_Search;

/**
 * 住所（郵便番号は含みません）
 */

class Fuzzy_Address extends Fuzzy
{
	use Traits_Number;
	
	/**
	 * 事前処理関数をオーバーライド
	 * 
	 * 空白以外の文字列を全角に変換
	 * 空白は連続を除去して半角に変換
	 */
	protected static function normalize($str = '')
	{
		$str = mb_convert_kana($str, 'AKV');
		$str = mb_convert_kana($str, 's');
		$str = parent::normalize($str);
		
		return $str;
	}
	
	/**
	 * 数値表現の多様化
	 * 
	 * 丁目・番地などの数字を無変換・アラビア数字・漢数字・漢数字桁名表記のそれぞれの書式でLIKE文を生成
	 * 
	 * @param string $keyword キーワード語句
	 * @param string $attribute 属性名
	 * @return array 各書式のLIKE文を格納した配列
	 */
	protected static function make_sentences($keyword = '', $attribute = '')
	{
		$conditions = array();
		
		$conditions[] = array($attribute, 'LIKE', '%'.$keyword.'%');
		$conditions[] = array($attribute, 'LIKE', '%'.self::chinese_to_arabic($keyword).'%');
		$conditions[] = array($attribute, 'LIKE', '%'.self::arabic_to_chinese($keyword).'%');
		$conditions[] = array($attribute, 'LIKE', '%'.self::arabic_to_chinese_digit_char($keyword).'%');
		
		return $conditions;
	}
}