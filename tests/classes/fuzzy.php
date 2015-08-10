<?php

namespace Fuzzy_Search\Test;
use Fuzzy_Search\Fuzzy;
use Fuzzy_Search\Fuzzy_Search;
require_once(PKGPATH.'fuzzy-search/tests/classes/share_funcs.php');

/**
 * test class Fuzzy
 * 
 * @group Package
 * @group PackageFuzzy
 */
class Test_Fuzzy extends \TestCase
{
	use Share_funcs;

	/**
	 * 配列の最外殻を脱離させるメソッド peel($ary) のテスト
	 *
	 * @test
	 */
	public function peel()
	{
		// ■テストに用いる変数の準備
	
		// メソッドのアクセス制限を解除して変数に取得
		$method = self::get_accessible_method('Fuzzy', 'peel');
	
	
		// ■多次元配列の最外殻要素数が1の間、繰り返し2次の要素を1次に引き上げる
		
		// 情報が2次に詰まっている配列を準備
		$ary = array(array('key' => 'val'));
		
		// メソッドを実行
		$result = $method->invokeArgs(null, array($ary));
	
		// 外殻の配列がなくなっていることを確認
		$this->assertEquals(array('key' => 'val'), $result);
		
		
		// 情報が4次に詰まっている配列を準備
		$ary = array(array(array(array('key' => 'val'))));
		
		// メソッドを実行
		$result = $method->invokeArgs(null, array($ary));
	
		// 外殻の配列がなくなっていることを確認
		$this->assertEquals(array('key' => 'val'), $result);
	}

	/**
	 * 検索キーワードの事前処理を行うメソッド normalize($str = '') のテスト
	 *
	 * @test
	 */
	public function normalize()
	{
		// ■テストに用いる変数の準備
	
		// メソッドのアクセス制限を解除して変数に取得
		$method = self::get_accessible_method('Fuzzy', 'normalize');
	
	
		// ■空白文字が半角に変換され、他の文字列に挟まれた空白の重複と、文字列の最初と最後の空白は削除される
		
		// 全角と半角の混合した文字列を引数にメソッドを実行
		$result = $method->invokeArgs(null, array('  　　a  　  b 　 　'));
	
		// 文字列を区切る半角の空白ひとつに変換されたことを確認
		$this->assertEquals('a b', $result);
	}

	/**
	 * 条件文のAND結合配列をOR結合に変換するメソッド associate_or($conditions) のテスト
	 *
	 * @test
	 */
	public function associate_or()
	{
		// ■テストに用いる変数の準備
	
		// メソッドのアクセス制限を解除して変数に取得
		$method = self::get_accessible_method('Fuzzy', 'associate_or');
	
	
		// ■引数に渡した配列の1次元階層の要素がORMのwhere句で有効なOR結合に変換される
		
		// 配列を作成
		$ary = array(
			'first', 'second', 'third', array('4', '5' => 'fifth', array('6', '7' => 'seventh')),
		);
		
		// メソッドを実行
		$result = $method->invokeArgs(null, array($ary));
	
		// 引数に渡した1次元階層の要素のみが変換され、2次元以下の配列構造は保存されていることを確認
		$this->assertEquals(
			array(
				'first',
				'or' => array(
					'second',
					'or' => array(
						'third',
						'or' => array('4', '5' => 'fifth', array('6', '7' => 'seventh')
			))))
		, $result);
	}

	/**
	 * キーワードの一致範囲を拡大するメソッド make_sentences($keyword = '', $attribute = '') のテスト
	 *
	 * @test
	 */
	public function make_sentences()
	{
		// ■テストに用いる変数の準備
	
		// メソッドのアクセス制限を解除して変数に取得
		$method = self::get_accessible_method('Fuzzy', 'make_sentences');
	
	
		// ■引数の文字列がそのまま・全半角数字・全半角アルファベット・ひらがな・全半角カタカナのなかで計13通りに組み合わされたLIKE文が生成される
		
		// メソッドを実行
		$result = $method->invokeArgs(null, array('8augustはちがつ', 'attr'));
	
		// 引数の文字列がそのまま・全半角数字・全半角アルファベット・ひらがな・全半角カタカナのなかで計13通りに組み合わされたLIKE文が生成されることを確認
		$this->assertEquals(array(
			array('attr', 'LIKE', '%8augustはちがつ%'),
			array('attr', 'LIKE', '%8augustハチガツ%'),
			array('attr', 'LIKE', '%8augustはちがつ%'),
			array('attr', 'LIKE', '%8augustﾊﾁｶﾞﾂ%'),
			array('attr', 'LIKE', '%8ａｕｇｕｓｔハチガツ%'),
			array('attr', 'LIKE', '%8ａｕｇｕｓｔはちがつ%'),
			array('attr', 'LIKE', '%8ａｕｇｕｓｔﾊﾁｶﾞﾂ%'),
			array('attr', 'LIKE', '%８augustハチガツ%'),
			array('attr', 'LIKE', '%８augustはちがつ%'),
			array('attr', 'LIKE', '%８augustﾊﾁｶﾞﾂ%'),
			array('attr', 'LIKE', '%８ａｕｇｕｓｔハチガツ%'),
			array('attr', 'LIKE', '%８ａｕｇｕｓｔはちがつ%'),
			array('attr', 'LIKE', '%８ａｕｇｕｓｔﾊﾁｶﾞﾂ%'),
		), $result);
	}

	/**
	 * where句生成アルゴリズム generate_conditions($keywords, $attributes) のテスト
	 *
	 * @test
	 */
	public function generate_conditions()
	{
		// ■テストに用いる変数の準備
	
		// メソッドのアクセス制限を解除して変数に取得
		$method = self::get_accessible_method('Fuzzy', 'generate_conditions');

		// クエリ作成メソッドのモックを作成
		$make_sentences = $this->getMock('Fuzzy', array('make_sentences'));
		
		// ラッパーが対応する引数で一度だけ呼ばれるアサーションを追加
		$make_sentences->method('make_sentences')->willReturn('query');
	
		// ■全てのキーワードと被検索列が組み合わされた複数の比較文が返却される
		
		// キーワードと検索列を指定してメソッドを実行
		$result = $method->invokeArgs(null, array(
			array('keyword1', 'keyword2'),
			array('field1', 'field2'),
		));
	
		// 
		$this->assertEquals(array(
			array(array('field1', 'LIKE', '%keyword1%'),
			'or' => array(array('field1', 'LIKE', '%keyword1%'),
			'or' => array(array('field1', 'LIKE', '%keyword1%'),
			'or' => array(array('field1', 'LIKE', '%keyword1%'),
			'or' => array(array('field1', 'LIKE', '%ｋｅｙｗｏｒｄ1%'),
			'or' => array(array('field1', 'LIKE', '%ｋｅｙｗｏｒｄ1%'),
			'or' => array(array('field1', 'LIKE', '%ｋｅｙｗｏｒｄ1%'),
			'or' => array(array('field1', 'LIKE', '%keyword１%'),
			'or' => array(array('field1', 'LIKE', '%keyword１%'),
			'or' => array(array('field1', 'LIKE', '%keyword１%'),
			'or' => array(array('field1', 'LIKE', '%ｋｅｙｗｏｒｄ１%'),
			'or' => array(array('field1', 'LIKE', '%ｋｅｙｗｏｒｄ１%'),
			'or' => array(array('field1', 'LIKE', '%ｋｅｙｗｏｒｄ１%'),
			'or' => array(array('field2', 'LIKE', '%keyword1%'),
			'or' => array(array('field2', 'LIKE', '%keyword1%'),
			'or' => array(array('field2', 'LIKE', '%keyword1%'),
			'or' => array(array('field2', 'LIKE', '%keyword1%'),
			'or' => array(array('field2', 'LIKE', '%ｋｅｙｗｏｒｄ1%'),
			'or' => array(array('field2', 'LIKE', '%ｋｅｙｗｏｒｄ1%'),
			'or' => array(array('field2', 'LIKE', '%ｋｅｙｗｏｒｄ1%'),
			'or' => array(array('field2', 'LIKE', '%keyword１%'),
			'or' => array(array('field2', 'LIKE', '%keyword１%'),
			'or' => array(array('field2', 'LIKE', '%keyword１%'),
			'or' => array(array('field2', 'LIKE', '%ｋｅｙｗｏｒｄ１%'),
			'or' => array(array('field2', 'LIKE', '%ｋｅｙｗｏｒｄ１%'),
			'or' => array('field2', 'LIKE', '%ｋｅｙｗｏｒｄ１%'),
			))))))))))))))))))))))))),
			array(array('field1', 'LIKE', '%keyword2%'),
			'or' => array(array('field1', 'LIKE', '%keyword2%'),
			'or' => array(array('field1', 'LIKE', '%keyword2%'),
			'or' => array(array('field1', 'LIKE', '%keyword2%'),
			'or' => array(array('field1', 'LIKE', '%ｋｅｙｗｏｒｄ2%'),
			'or' => array(array('field1', 'LIKE', '%ｋｅｙｗｏｒｄ2%'),
			'or' => array(array('field1', 'LIKE', '%ｋｅｙｗｏｒｄ2%'),
			'or' => array(array('field1', 'LIKE', '%keyword２%'),
			'or' => array(array('field1', 'LIKE', '%keyword２%'),
			'or' => array(array('field1', 'LIKE', '%keyword２%'),
			'or' => array(array('field1', 'LIKE', '%ｋｅｙｗｏｒｄ２%'),
			'or' => array(array('field1', 'LIKE', '%ｋｅｙｗｏｒｄ２%'),
			'or' => array(array('field1', 'LIKE', '%ｋｅｙｗｏｒｄ２%'),
			'or' => array(array('field2', 'LIKE', '%keyword2%'),
			'or' => array(array('field2', 'LIKE', '%keyword2%'),
			'or' => array(array('field2', 'LIKE', '%keyword2%'),
			'or' => array(array('field2', 'LIKE', '%keyword2%'),
			'or' => array(array('field2', 'LIKE', '%ｋｅｙｗｏｒｄ2%'),
			'or' => array(array('field2', 'LIKE', '%ｋｅｙｗｏｒｄ2%'),
			'or' => array(array('field2', 'LIKE', '%ｋｅｙｗｏｒｄ2%'),
			'or' => array(array('field2', 'LIKE', '%keyword２%'),
			'or' => array(array('field2', 'LIKE', '%keyword２%'),
			'or' => array(array('field2', 'LIKE', '%keyword２%'),
			'or' => array(array('field2', 'LIKE', '%ｋｅｙｗｏｒｄ２%'),
			'or' => array(array('field2', 'LIKE', '%ｋｅｙｗｏｒｄ２%'),
			'or' => array('field2', 'LIKE', '%ｋｅｙｗｏｒｄ２%'),
			)))))))))))))))))))))))))
		), $result);
	}

	/**
	 * where句を生成する公開メソッド get_where($keyword = null, $attribute = null) のテスト
	 *
	 * @test
	 */
	public function get_where()
	{
		// ■引数の無効を検知する
		
		// キーワードにnullを渡してメソッドを実行
		$result = Fuzzy::get_where(null, 'attr');
	
		// 空の配列が返却されることを確認
		$this->assertEquals(array(), $result);
		
		// 属性にnullを渡してメソッドを実行
		$result = Fuzzy::get_where('kwd', null);
	
		// 空の配列が返却されることを確認
		$this->assertEquals(array(), $result);
		
		// キーワードに''を渡してメソッドを実行
		$result = Fuzzy::get_where('', 'attr');
	
		// 空の配列が返却されることを確認
		$this->assertEquals(array(), $result);
		
		// 属性に''を渡してメソッドを実行
		$result = Fuzzy::get_where('kwd', '');
	
		// 空の配列が返却されることを確認
		$this->assertEquals(array(), $result);
	}

}