<?php

namespace Fuzzy_Search\Test;
require_once(PKGPATH.'fuzzy-search/tests/classes/share_funcs.php');

/**
 * test class Fuzzy_Zip
 * 
 * @group Package
 * @group PackageFuzzy
 */
class Test_Fuzzy_Zip extends \TestCase
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
		$method = self::get_accessible_method('Fuzzy_Zip', 'normalize');
	
	
		// ■ハイフンと類似する文字がハイフンに、全ての文字列が半角に変換され、空白は連続を除去される
	
		// メソッドを実行
		$result = $method->invokeArgs(null, array('００１ －００１１　　'));
	
		// ハイフンと類似する文字がハイフンに、全ての文字列が半角に変換され、空白は連続を除去されることを確認
		$this->assertEquals('001 -0011', $result);
	}

	/**
	 * 数字の用途ごとに条件文を生成するメソッド make_sentences($keyword = '', $attribute = '') のテスト
	 *
	 * @test
	 */
	public function make_sentences()
	{
		// ■テストに用いる変数の準備
	
		// メソッドのアクセス制限を解除して変数に取得
		$method = self::get_accessible_method('Fuzzy_Zip', 'make_sentences');
	
	
		// ■郵便番号情報が7文字全て揃っているときはハイフンの位置を調整したうえでLIKE検索をしない
	
		// メソッドを実行
		$result = $method->invokeArgs(null, array('065-0806', 'attr'));
	
		// 完全一致文が生成されていることを確認
		$this->assertEquals(array('attr', '065-0806'), $result);
	
		// メソッドを実行
		$result = $method->invokeArgs(null, array('0650806', 'attr'));
	
		// 完全一致文が生成されていることを確認
		$this->assertEquals(array('attr', '065-0806'), $result);
	
	
		// ■4文字以上6文字以下のときはハイフンの位置をずらしてLIKE文を作る
	
		// 6文字でメソッドを実行
		$result = $method->invokeArgs(null, array('065-080', 'attr'));
	
		// ハイフン位置が2通りのLIKE文が生成されていることを確認
		$this->assertEquals(array(
			array('attr', 'LIKE', '%065-080%'),
			'or' => array('attr', 'LIKE', '%06-5080%'),
		), $result);
	
		// 5文字でメソッドを実行
		$result = $method->invokeArgs(null, array('06-508', 'attr'));
	
		// ハイフン位置が3通りのLIKE文が生成されていることを確認
		$this->assertEquals(
			array(
				array('attr', 'LIKE', '%065-08%'),
				'or' => array(
					array('attr', 'LIKE', '%06-508%'),
					'or' => array('attr', 'LIKE', '%0-6508%'),
		)), $result);
	
	
		// ■3文字以下のときはそのままLIKE文を作る
	
		// メソッドを実行
		$result = $method->invokeArgs(null, array('065', 'attr'));
	
		// LIKE文が生成されていることを確認
		$this->assertEquals(array('attr', 'LIKE', '%065%'), $result);
	}
}