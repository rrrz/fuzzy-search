<?php

namespace Fuzzy_Search;

/**
 * 数字のあいまい検索
 */

class Fuzzy_Zip extends Fuzzy
{
	use Traits_Symbol;
	
	/**
	 * 事前処理関数をオーバーライド
	 * 
	 * ハイフンと類似する文字をハイフンに変換
	 * 空白以外の文字列を半角に変換
	 * 空白は連続を除去して半角に変換
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
	 * @param string $attribute 属性名
	 * @return array 配列形式の条件文
	 */
	protected static function make_sentences($keyword = '', $attribute = '')
	{
		// ハイフン無し、ハイフンの位置間違いを訂正
		$keyword = preg_replace('/[-]/', '', $keyword);
		
		// 7文字全て揃っているときはあいまい検索をしない
		if (strlen($keyword) == 7){
			
			$keyword = substr($keyword, 0, 3).'-'.substr($keyword, 3, 4);
			
			return array($attribute, $keyword);
		
		// 4文字以上ある時はハイフンの位置を変えてLIKE文を作る
		}elseif (($len = strlen($keyword)) >= 4){
			
			$conditions = array();
			
			$ofs = 8 - $len;
			
			for ($i = 0; $i < $ofs; $i++){
				$kwd = substr($keyword, 0, 3 - $i).'-'.substr($keyword, 3 - $i, strlen($keyword) - (3 - $i));
				$conditions[] = array($attribute, 'LIKE', '%'.$kwd.'%');
			}
			
			return self::associate_or($conditions);
		}
		
		// 3文字以下のときはそのままLIKE文を作る
		return array($attribute, 'LIKE', '%'.$keyword.'%');
	}
}