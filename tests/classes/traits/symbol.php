<?php

namespace Fuzzy_Search\Test;
require_once(PKGPATH.'fuzzy-search/tests/classes/share_funcs.php');

/**
 * test class Traits_Symbol
 * 
 * @group Package
 * @group PackageFuzzy
 * @group PackageFuzzyTraits
 */
class Test_Traits_Symbol extends \TestCase
{
	use Share_funcs;

	/**
	 * ハイフンを変換するメソッドunificate_hyphen($val, $replacement = "\xEF\xBC\x8D") のテスト
	 *
	 * @test
	 */
	public function unificate_hyphen()
	{
		// ■テストに用いる変数の準備
	
		// メソッドのアクセス制限を解除して変数に取得
		$method = self::get_accessible_method('Fuzzy_Zip', 'unificate_hyphen');
	
	
		// ■以下、メソッドを実行して指定したハイフンに変換されることを繰り返し確認
	
		$this->assertEquals('-'            , $method->invokeArgs(null, array('－', '-')));
		$this->assertEquals('-'            , $method->invokeArgs(null, array('-', '-')));
		$this->assertEquals('-'            , $method->invokeArgs(null, array('﹣', '-')));
		$this->assertEquals('-'            , $method->invokeArgs(null, array('‐', '-')));
		$this->assertEquals('-'            , $method->invokeArgs(null, array('⁃', '-')));
		$this->assertEquals('-'            , $method->invokeArgs(null, array('˗', '-')));
		$this->assertEquals('-'            , $method->invokeArgs(null, array('−', '-')));
		$this->assertEquals('-'            , $method->invokeArgs(null, array('⧿', '-')));
		$this->assertEquals('-'            , $method->invokeArgs(null, array('➖', '-')));
		$this->assertEquals('-'            , $method->invokeArgs(null, array('‒', '-')));
		$this->assertEquals('-'            , $method->invokeArgs(null, array('–', '-')));
		$this->assertEquals('-'            , $method->invokeArgs(null, array('—', '-')));
		$this->assertEquals('-'            , $method->invokeArgs(null, array('―', '-')));
		$this->assertEquals('-'            , $method->invokeArgs(null, array('⸺', '-')));
		$this->assertEquals('-'            , $method->invokeArgs(null, array('⸻', '-')));
		$this->assertEquals('-'            , $method->invokeArgs(null, array('﹘', '-')));
		$this->assertEquals('-'            , $method->invokeArgs(null, array('⎯', '-')));
		$this->assertEquals('-'            , $method->invokeArgs(null, array('⏤', '-')));
		$this->assertEquals('-'            , $method->invokeArgs(null, array('ー', '-')));
		$this->assertEquals('-'            , $method->invokeArgs(null, array('－', '-')));
	}
}