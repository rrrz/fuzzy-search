<?php

namespace Fuzzy_Search\Test;
require_once(PKGPATH.'fuzzy-search/tests/classes/share_funcs.php');

/**
 * test class Traits_Number
 * 
 * @group Package
 * @group PackageFuzzy
 * @group PackageFuzzyTraits
 */
class Test_Traits_Number extends \TestCase
{
	use Share_funcs;

	/**
	 * すべての漢数字をアラビア数字に変換するメソッド chinese_to_arabic($str = '') のテスト
	 *
	 * @test
	 */
	public function chinese_to_arabic()
	{
		// ■テストに用いる変数の準備
	
		// メソッドのアクセス制限を解除して変数に取得
		$method = self::get_accessible_method('Fuzzy_Address', 'chinese_to_arabic');
	
	
		// ■すべての漢数字がアラビア数字に変換される
	
		// メソッドを実行
		$result = $method->invokeArgs(null, array('〇一二三四五六七八九'));
	
		// アラビア数字になっていることを確認
		$this->assertEquals('0123456789', $result);
	}

	/**
	 * すべてのアラビア数字を漢数字に変換するメソッド arabic_to_chinese($str = '') のテスト
	 *
	 * @test
	 */
	public function arabic_to_chinese()
	{
		// ■テストに用いる変数の準備
	
		// メソッドのアクセス制限を解除して変数に取得
		$method = self::get_accessible_method('Fuzzy_Address', 'arabic_to_chinese');
	
	
		// ■すべてのアラビア数字が漢数字に変換される
	
		// メソッドを実行
		$result = $method->invokeArgs(null, array('0123456789'));
	
		// 漢数字になっていることを確認
		$this->assertEquals('〇一二三四五六七八九', $result);
	
		// メソッドを実行
		$result = $method->invokeArgs(null, array('０１２３４５６７８９'));
	
		// 漢数字になっていることを確認
		$this->assertEquals('〇一二三四五六七八九', $result);
	}

	/**
	 * すべてのアラビア数字を漢数字桁名表記に変換するメソッド arabic_to_chinese_digit_char($str = '') のテスト
	 *
	 * @test
	 */
	public function arabic_to_chinese_digit_char()
	{
		// ■テストに用いる変数の準備
	
		// メソッドのアクセス制限を解除して変数に取得
		$method = self::get_accessible_method('Fuzzy_Address', 'arabic_to_chinese_digit_char');
	
	
		// ■以下、メソッドを実行してすべてのアラビア数字が漢数字桁名表記に変換されることを繰り返し確認
	
		$this->assertEquals('十'            , $method->invokeArgs(null, array('10')));
		$this->assertEquals('十一'          , $method->invokeArgs(null, array('11')));
		$this->assertEquals('二十'          , $method->invokeArgs(null, array('20')));
		$this->assertEquals('二十二'         , $method->invokeArgs(null, array('22')));
		$this->assertEquals('百'            , $method->invokeArgs(null, array('100')));
		$this->assertEquals('百五'          , $method->invokeArgs(null, array('105')));
		$this->assertEquals('百五十七'       , $method->invokeArgs(null, array('157')));
		$this->assertEquals('五百六十三'     , $method->invokeArgs(null, array('563')));
		$this->assertEquals('千'            , $method->invokeArgs(null, array('1000')));
		$this->assertEquals('千一'          , $method->invokeArgs(null, array('1001')));
		$this->assertEquals('千二十四'       , $method->invokeArgs(null, array('1024')));
		$this->assertEquals('八千百九十二'    , $method->invokeArgs(null, array('8192')));
		$this->assertEquals('一万'           , $method->invokeArgs(null, array('10000')));
		$this->assertEquals('二万八'         , $method->invokeArgs(null, array('20008')));
		$this->assertEquals('三万五十六'      , $method->invokeArgs(null, array('30056')));
		$this->assertEquals('七万六百五十九'   , $method->invokeArgs(null, array('70659')));
		$this->assertEquals('八万千五百六十二' , $method->invokeArgs(null, array('81562')));
	}
}