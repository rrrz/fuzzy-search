# Fuzzy Search

* Version: 1.0

## Information

* PHP >= 5.4
* FuelPHP = 1.7/master

## Description

入力された文字列を変換してあいまいな検索を可能にします。
たとえば「Fuzzy」と入力したとき「Fuzzy」「FUZZY」「fuzzy」「ＦＵＺＺＹ」「ｆｕｚｚｙ」のすべてにヒットさせることが可能です。

###機能

本パッケージ内のメソッドで、FuelPHPのORMパッケージで "Model::find()" 等に渡す以下のような条件文の配列を生成します。

各キーワードはAND条件で結合され、各フィールドはOR条件で結合されます。

	array(
		array('field_name_1', 'LIKE', '%Keyword_1%'),
		'or' => array(
			array('field_name_1', 'LIKE', '%KEYWORD_1%'),
			'or' => array(
				array('field_name_2', 'LIKE', '%Keyword_1%'),
				'or' => array(
					array('field_name_2', 'LIKE', '%KEYWORD_1%'),
		),
		array('field_name_1', 'LIKE', '%Keyword_2%'),
		'or' => array(
			array('field_name_1', 'LIKE', '%KEYWORD_2%'),
			
			...
		),
	)


デフォルトでは以下のクラスが利用できます。

1. Fuzzy

* 引数の検索キーワードをそのまま・全半角数字・全半角アルファベット・ひらがな・全半角カタカナのなかで計13通りに組み合わされたLIKE文が生成される

2. Fuzzy_Address

* 丁目・番地などの数字をそのまま・アラビア数字・漢数字・漢数字桁名表記のそれぞれの書式に変換してLIKE文を生成
* 全文字列を全角に変換

3. Fuzzy_Tell

* 電話番号のフォーマット(例： 0120-123-456)通りに書かれたキーワードではLIKE文ではなく完全一致文を生成
* ハイフンに似た文字が渡された場合"-"に統一する
* 全文字列を半角に変換

4. Fuzzy_Zip

* 郵便番号のフォーマット(例： 001-2345)通りに書かれたキーワードではLIKE文ではなく完全一致文を生成
* キーワードの数字が7ケタに満たなかった場合、その数字の並びを含む郵便番号を検索するためのLIKE文を生成
* ハイフンの位置が間違っていた場合に修正する
* ハイフンに似た文字が渡された場合"-"に統一する
* 全文字列を半角に変換


## Install

(1) パッケージのインストール

■ gitリポジトリよりクローンする場合

	git clone https://github.com/rrrz/fuzzy-search fuel/packages/
	
■ composerによりインストールする場合

composer.jsonに以下を追記

	"require": {
		"fuzzy-search": "1.*"
	},
	{
		"type":"package",
		"package":{
			"name":"fuzzy-search",
			"type":"fuel-package",
			"version":"1.0",
			"source":{
				"type":"git",
				"url":"https://github.com/rrrz/fuzzy-search.git",
				"reference":"master"
			}
		}
	}

インストール

	composer.phar install
	composer.phar update

(2) configの設定

	vi fuel/app/config.php

		// オートローダに登録
		always_load => array(
			packages => 'fuzzy-search',
		),


## Usage

### Controller

公開メソッド get_where($keyword, $field_name) に検索ワードとフィールド名を渡してwhere句の配列を生成します。

複数のフィールドにまたがった検索条件を生成する場合には配列でフィールド名を渡してください。

	Fuzzy::get_where('Jeff', array('name', 'name_kana'))

検索ワードが複数ある場合には、配列と空白区切のどちらでも構いません。

	Fuzzy::get_where(array('Jeff', 'Tim'), 'name'))
	Fuzzy::get_where('Jeff Tim Max', 'name'))

生成した条件を用いてレコードを取得してビューに渡します。

	public function action_index()
	{
		$options = array('where' => array());
		
		if( $name = Input::param('name') ){
			if ($condition = Fuzzy::get_where($name, array('name', 'name_kana'))){
				$options['where'][] = $condition;
			}
		}
		
		if( $zip_code = Input::param('zip_code') ){
			if ($condition = Fuzzy_Zip::get_where($zip_code, 'zip_code')){
				$options['where'][] = $condition;
			}
		}
		
		if( $full_address = Input::param('full_address') ){
			if ($condition = Fuzzy_Address::get_where($full_address, 'full_address')){
				$options['where'][] = $condition;
			}
		}
		
		if( $tell = Input::param('tell') ){
			if ($condition = Fuzzy_Tell::get_where($tell, array('tell1', 'tell2', 'fax1', 'fax2'))){
				$options['where'][] = $condition;
			}
		}
		
		$item = Model_Example::find('all', $options);
		
		$data = array('items'=> $item, 'url_base'=>static::$url_base );
		$this->template->content = View::forge( static::$url_base. '/index',$data);
	}


## Customize

###classes/fuzzy/example.php

パッケージのFuzzyクラスを継承して、make_sentences()メソッド等をオーバーライドします。

	namespace Fuzzy_Search;
	
	class Fuzzy_Example extends Fuzzy
	{
		use Traits_Example;
		
		// キーワード文字列の事前処理にオリジナルの処理を追加しています
		protected static function normalize($str = '')
		{
			$str = self::example_process($str);
			$str = parent::normalize($str);
			
			return $str;
		}
		
		// 渡されたキーワード1語とDBのフィールド1列から比較文を作ります
		protected static function make_sentences($keyword = '', $field_name = '')
		{
			// フィールド名が'field_1'のときはLIKE文を、'field_2'のときは完全一致文を返しています
			if ($field_name == 'field_1'){
			
				return array($field_name, 'LIKE', '%'.$keyword.'%');
			
			}elseif ($field_name == 'field_2'){
			
				return array($field_name, $keyword);
			
			}
		}
	}
	
	
###classes/traits/example.php

共有する関数などはtraitにしておきます。

	namespace Fuzzy_Search;
	
	trait Traits_Example
	{
		protected static function example_process($str = '')
		{
			return preg_replace($pattern, $replace, $str);
		}
	}


## License

MIT License

