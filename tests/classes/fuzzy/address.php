<?php

namespace Fuzzy_Search\Test;
require_once(PKGPATH.'fuzzy-search/tests/classes/share_funcs.php');

/**
 * test class Fuzzy_Address
 * 
 * @group Package
 * @group PackageFuzzy
 */
class Test_Fuzzy_Address extends \TestCase
{
	use Share_funcs;

	/**
	 * 事前処理するメソッド normalize($str = '') のテスト
	 *
	 * @test
	 */
	public function normalize()
	{
		// ■テストに用いる変数の準備
	
		// メソッドのアクセス制限を解除して変数に取得
		$method = self::get_accessible_method('Fuzzy_Address', 'normalize');
	
	
		// ■空白以外の文字列を全角に変換、空白は連続を除去して半角に変換される
	
		// メソッドを実行
		$result = $method->invokeArgs(null, array('ABC  　　123 '));
	
		// 空白以外の文字列を全角に変換、空白は連続を除去して半角に変換されていることを確認
		$this->assertEquals('ＡＢＣ １２３', $result);
	}

	/**
	 * 数値表現を多様化するメソッド make_sentences($keyword = '', $attribute = '') のテスト
	 *
	 * @test
	 */
	public function make_sentences()
	{
		// ■テストに用いる変数の準備
	
		// メソッドのアクセス制限を解除して変数に取得
		$method = self::get_accessible_method('Fuzzy_Address', 'make_sentences');
	
	
		// ■丁目・番地などの数字を無変換・アラビア数字・漢数字・漢数字桁名表記のそれぞれの書式でLIKE文を生成
	
		// メソッドを実行
		$result = $method->invokeArgs(null, array('三丁目4番56号', 'attr'));
	
		// 丁目・番地などの数字を無変換・アラビア数字・漢数字・漢数字桁名表記のそれぞれの書式でLIKE文が生成されていることを確認
		$this->assertEquals(array(
			array('attr', 'LIKE', '%三丁目4番56号%'),
			array('attr', 'LIKE', '%3丁目4番56号%'),
			array('attr', 'LIKE', '%三丁目四番五六号%'),
			array('attr', 'LIKE', '%三丁目四番五十六号%'),
		), $result);
	}
}