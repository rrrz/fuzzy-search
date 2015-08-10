<?php

namespace Fuzzy_Search;

/**
 * 数字のあいまい検索
 */

class Fuzzy_Tell extends Fuzzy
{
	use Traits_Symbol;
	
	/**
	 * 事前処理関数をオーバーライド
	 * 
	 * ハイフンと類似する文字をハイフンに変換
	 * 文字列をすべて半角に変換
	 * 空白は連続を除去する
	 */
	protected static function normalize($str = '')
	{
		$str = mb_convert_kana($str, 'anhks');
		$str = self::unificate_hyphen($str, '-');
		$str = parent::normalize($str);
		
		return $str;
	}
	
	/**
	 * 数字の用途ごとに条件文を生成
	 *
	 * @param string $keyword キーワード語句
	 * @param string $field_name 属性名
	 * @return array 配列形式の条件文
	 */
	protected static function make_sentences($keyword = '', $field_name = '')
	{
		// フォーマットが完全なときはあいまい検索をしない
		if (preg_match('/^\d{2,4}-\d{2,4}-\d{3,5}$/', $keyword)){
			
			return array($field_name, $keyword);
		}
		
		return array($field_name, 'LIKE', '%'.$keyword.'%');
	}
}