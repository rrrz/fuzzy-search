<?php

namespace Fuzzy_Search;

trait Traits_Symbol
{
	/**
	 * ハイフンの変換
	 *
	 * @param  string $val         変換される文字列
	 * @param  string $replacement 置換後のハイフン
	 * @return string              変換された文字列
	 */
	protected static function unificate_hyphen($val, $replacement = "\xEF\xBC\x8D")
	{
		$val = str_replace("\xEF\xBC\x8D", $replacement, $val);
		$val = str_replace("\x2D",         $replacement, $val);
		$val = str_replace("\xEF\xB9\xA3", $replacement, $val);
		$val = str_replace("\xE2\x80\x90", $replacement, $val);
		$val = str_replace("\xE2\x81\x83", $replacement, $val);
		$val = str_replace("\xCB\x97",     $replacement, $val);
		$val = str_replace("\xE2\x88\x92", $replacement, $val);
		$val = str_replace("\xE2\xA7\xBF", $replacement, $val);
		$val = str_replace("\xE2\x9E\x96", $replacement, $val);
		$val = str_replace("\xE2\x80\x92", $replacement, $val);
		$val = str_replace("\xE2\x80\x93", $replacement, $val);
		$val = str_replace("\xE2\x80\x94", $replacement, $val);
		$val = str_replace("\xE2\x80\x95", $replacement, $val);
		$val = str_replace("\xE2\xB8\xBA", $replacement, $val);
		$val = str_replace("\xE2\xB8\xBB", $replacement, $val);
		$val = str_replace("\xEF\xB9\x98", $replacement, $val);
		$val = str_replace("\xE2\x8E\xAF", $replacement, $val);
		$val = str_replace("\xE2\x8F\xA4", $replacement, $val);
		$val = str_replace("\xE3\x83\xBC", $replacement, $val);
		$val = str_replace("\xEF\xBC\x8D", $replacement, $val);
		
		return $val;
	}
}