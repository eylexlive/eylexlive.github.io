<?php

namespace Webcraft\Helpers\Minecraft;

class Color
{
	const REGEX = '/(?:§|&amp;)([0-9a-fklmnor])/i';
	const START_TAG  = '<span style="%s">';
	const CLOSE_TAG  = '</span>';
	const CSS_COLOR  = 'color: #';
	const EMPTY_TAGS = '/<[^\/>]*>([\s]?)*<\/[^>]*>/';
	const LINE_BREAK = '<br />';

	static private $colors = array(
		'0' => '000000', //Black
		'1' => '0000AA', //Dark Blue
		'2' => '00AA00', //Dark Green
		'3' => '00AAAA', //Dark Aqua
		'4' => 'AA0000', //Dark Red
		'5' => 'AA00AA', //Dark Purple
		'6' => 'FFAA00', //Gold
		'7' => 'AAAAAA', //Gray
		'8' => '555555', //Dark Gray
		'9' => '5555FF', //Blue
		'a' => '55FF55', //Green
		'b' => '55FFFF', //Aqua
		'c' => 'FF5555', //Red
		'd' => 'FF55FF', //Light Purple
		'e' => 'FFFF55', //Yellow
		'f' => 'FFFFFF'  //White
	);

	static private $formatting = array(
		'k' => '',                               //Obfuscated
		'l' => 'font-weight: bold;',             //Bold
		'm' => 'text-decoration: line-through;', //Strikethrough
		'n' => 'text-decoration: underline;',    //Underline
		'o' => 'font-style: italic;',            //Italic
		'r' => ''                                //Reset
	);

	static private function utf8($text)
	{
		//Encode the text in UTF-8, but only if it's not already.
		if (mb_detect_encoding($text) != 'UTF-8')
			$text = utf8_encode($text);
		return $text;
	}

	static public function clean($text)
	{
		$text = self::utf8($text);
		$text = htmlspecialchars($text, ENT_QUOTES);
		return preg_replace(self::REGEX, '', $text);
	}

	static public function motd($text, $sign = '\u00A7')
	{
		$text = self::utf8($text);
		$text = str_replace("&", "&amp;", $text);
		$text = preg_replace(self::REGEX, $sign.'${1}', $text);
		$text = str_replace("\n", '\n', $text);
		$text = str_replace("&amp;", "&", $text);
		return $text;
	}

	static public function html($text, $line_break_element = false)
	{
		$text = self::utf8($text);
		$text = htmlspecialchars($text, ENT_QUOTES);
		preg_match_all(self::REGEX, $text, $offsets);
		$colors      = $offsets[0]; //This is what we are going to replace with HTML.
		$color_codes = $offsets[1]; //This is the color numbers/characters only.
		//No colors? Just return the text.
		if (empty($colors))
			return $text;
		$open_tags = 0;
		foreach ($colors as $index => $color) {
			$color_code = strtolower($color_codes[$index]);
			//We have a normal color.
			if (isset(self::$colors[$color_code])) {
				$html = sprintf(self::START_TAG, self::CSS_COLOR.self::$colors[$color_code]);
				//New color clears the other colors and formatting.
				if ($open_tags != 0) {
					$html = str_repeat(self::CLOSE_TAG, $open_tags).$html;
					$open_tags = 0;
				}
				$open_tags++;
			}
			//We have some formatting.
			else {
				switch ($color_code) {
					//Reset is special, just close all open tags.
					case 'r':
						$html = '';
						if ($open_tags != 0) {
							$html = str_repeat(self::CLOSE_TAG, $open_tags);
							$open_tags = 0;
						}
						break;
					//Can't do obfuscated in CSS...
					case 'k':
						$html = '';
						break;
					default:
						$html = sprintf(self::START_TAG, self::$formatting[$color_code]);
						$open_tags++;
						break;
				}
			}
			//Replace the color with the HTML code. We use preg_replace because of the limit parameter.
			$text = preg_replace('/'.$color.'/', $html, $text, 1);
		}
		//Still open tags? Close them!
		if ($open_tags != 0)
			$text = $text.str_repeat(self::CLOSE_TAG, $open_tags);
		//Replace \n with <br />
		if ($line_break_element) {
			$text = str_replace("\n", self::LINE_BREAK, $text);
			$text = str_replace('\n', self::LINE_BREAK, $text);
		}
		//Return the text without empty HTML tags. Only to clean up bad color formatting from the user.
		return preg_replace(self::EMPTY_TAGS, '', $text);
	}
}