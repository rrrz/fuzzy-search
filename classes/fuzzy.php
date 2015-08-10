<?php

namespace Fuzzy_Search;

/**
 * あいまい検索のwhere句作成クラス
 *
 * 検索キーワードに応じた具体的なアルゴリズムは継承先のサブクラスにて定義してください
 */

class Fuzzy
{
	/**
	 * where句を生成する公開メソッド
	 * 
	 * @param mixed[] $keyword   検索キーワード （文字列または配列、空白区切りも可）
	 * @param mixed[] $field_name 検索対象の属性名 （文字列または配列）
	 * @return array  ORMが解釈できる構造のwhere句
	 */
	public static function get_where($keyword = null, $field_name = null)
	{
		if (!$keyword || !$field_name){ return array(); }
		
		$field_name = (array)$field_name;
		
		is_array($keyword) and $keyword = implode(' ', $keyword);
		
		if (!($keyword = self::normalize($keyword))){ return array(); }
		
		$keyword = explode(' ', $keyword);
		
		$where = self::generate_conditions($keyword, $field_name);
		
		return $where;
	}

	/**
	 * where句生成アルゴリズム
	 * 
	 * キーワード語句と属性名に応じて比較文を生成し、and_where/or_whereに構造化します
	 * 
	 * @param  array $keywords   キーワード語句を格納した配列
	 * @param  array $field_names 属性名を格納した配列
	 * @return array             where句として構造化された配列
	 */
	protected static function generate_conditions($keywords, $field_names)
	{
		$conditions = array();
		
		foreach ($keywords as $kwd){
			
			$queries = array();
			
			foreach ($field_names as $attr){
				
				$queries = array_merge($queries, self::make_sentences($kwd, $attr));
			}
			
			if (count($queries, COUNT_RECURSIVE) > 0){
				
				$queries = self::associate_or($queries);
				
				$conditions[] = $queries;
			}
		}
		
		$conditions = self::peel($conditions);
		
		return $conditions;
	}
	
	/**
	 * キーワードの一致範囲を拡大
	 * 
	 * 半角・全角の変換を組み合わせて多様化
	 * 
	 * @param  string $keyword   キーワード語句
	 * @param  string $field_name 属性名
	 * @return array             and_where構造のLIKE文
	 */
	protected static function make_sentences($keyword = '', $field_name = '')
	{
		if (!$keyword || !$field_name){ return array(); }
		
		$options_num = array('n', 'N');
		$options_arp = array('r', 'R');
		$options_kana = array('CKV', 'cHV', 'kh');
		
		$condition = array(
			array($field_name, 'LIKE', '%'.$keyword.'%')
		);
		
		foreach ($options_num as $option_num){
			foreach ($options_arp as $option_arp){
				foreach ($options_kana as $option_kana){
					
					$option = $option_num.$option_arp.$option_kana;
					
					$condition[] = array($field_name, 'LIKE', '%'.mb_convert_kana($keyword, $option).'%');
				}
			}
		}
		
		return $condition;
	}
	
	/**
	 * 条件文のAND結合配列をOR結合に変換
	 * 
	 * ORMのwhere句として使用できるOR結合の構造を作ります
	 * 渡された配列の1次元階層にある要素のみが対象です
	 *
	 * @param  array $conditions AND結合された条件文配列
	 * @return array OR結合に変換された配列
	 */
	protected static function associate_or($conditions)
	{
		$count = count($conditions);
		
		if ($count > 1){
			
			$associated = $conditions[$count - 1];
			
			for ($i = $count - 1; $i > 0; $i--){
				
				$associated = array($conditions[$i - 1], 'or' => $associated);
			}
			
			return $associated;
				
		}elseif ($count == 1){
				
			return $conditions[0];
				
		}else{
			
			return array();
		}
	}
	
	/**
	 * 検索キーワードの事前処理
	 * 
	 * @param string $str 検索キーワード
	 * @return string 処理後の文字列
	 */
	protected static function normalize($str = '')
	{
		$str = mb_convert_kana($str, 's');
		$str = preg_replace('/\s{2,}/u', ' ', $str);
		$str = preg_replace('/^\s/u', '', $str);
		$str = preg_replace('/\s$/u', '', $str);
		
		return $str;
	}
	
	/**
	 * 配列の最外殻を脱離
	 * 
	 * 多次元配列の最外殻要素数が1の間、繰り返し2次の要素を1次に引き上げる
	 * 引き上げられる前に要素が格納されていた1次の配列キーは失われます
	 * 
	 * @param array $ary 被処理配列
	 * @return array 処理済み配列
	 */
	protected static function peel($ary)
	{
		if (!is_array($ary)){ return $ary; }
		
		reset($ary);
		
		while (count($ary) == 1 && count($ary, COUNT_RECURSIVE) > 1){
			
			$ary = current($ary);
		}
		
		return $ary;
	}
}
