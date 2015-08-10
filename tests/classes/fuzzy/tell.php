<?php

namespace Fuzzy_Search\Test;
require_once(PKGPATH.'fuzzy-search/tests/classes/share_funcs.php');

/**
 * test class Fuzzy_Tell
 * 
 * @group Package
 * @group PackageFuzzy
 */
class Test_Fuzzy_Tell extends \TestCase
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
		$method = self::get_accessible_method('Fuzzy_Tell', 'normalize');
	
	
		// ■ハイフンと類似する文字がハイフンに、全ての文字列が半角に変換され、空白は連続を除去される
	
		// メソッドを実行
		$result = $method->invokeArgs(null, array('０１１ －２３４ －５６７８　　'));
	
		// ハイフンと類似する文字がハイフンに、全ての文字列が半角に変換され、空白は連続を除去されることを確認
		$this->assertEquals('011 -234 -5678', $result);
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
		$method = self::get_accessible_method('Fuzzy_Tell', 'make_sentences');
	
	
		// ■電話番号のフォーマットに従ったキーワードのときはLIKE検索をしない
	
		// メソッドを実行
		$result = $method->invokeArgs(null, array('0120-123-456', 'attr'));
	
		// 完全一致文が生成されていることを確認
		$this->assertEquals(array('attr', '0120-123-456'), $result);
	
		// メソッドを実行
		$result = $method->invokeArgs(null, array('03-1233-4566', 'attr'));
	
		// 完全一致文が生成されていることを確認
		$this->assertEquals(array('attr', '03-1233-4566'), $result);
	
		// メソッドを実行
		$result = $method->invokeArgs(null, array('0797-55-5555', 'attr'));
	
		// 完全一致文が生成されていることを確認
		$this->assertEquals(array('attr', '0797-55-5555'), $result);
	
	
		// ■電話番号のフォーマットに適合しないときはLIKE検索をする
	
		// メソッドを実行
		$result = $method->invokeArgs(null, array('0120-', 'attr'));
	
		// LIKE文が生成されていることを確認
		$this->assertEquals(array('attr', 'LIKE', '%0120-%'), $result);
	
		// メソッドを実行
		$result = $method->invokeArgs(null, array('551', 'attr'));
	
		// LIKE文が生成されていることを確認
		$this->assertEquals(array('attr', 'LIKE', '%551%'), $result);
	}
}