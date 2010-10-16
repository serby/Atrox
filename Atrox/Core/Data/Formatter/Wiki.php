<?php
/**
 * @package Core
 * @subpackage Data/Formatter
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1483 $ - $Date: 2010-05-19 19:29:54 +0100 (Wed, 19 May 2010) $
 */

/**
 * Simple WIKI markup parser
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 1483 $ - $Date: 2010-05-19 19:29:54 +0100 (Wed, 19 May 2010) $
 * @package Core
 * @subpackage Data/Formatter
 */
class Atrox_Core_Data_Formatter_Wiki implements Atrox_Core_Data_IFormatter {

	const MRK_PLAIN = 1;
	const MRK_OL = 2;
	const MRK_UL = 3;
	const MRK_DIV = 4;

	public function format($value) {
		return $this->parse($value);
	}

	public function parse($text) {
		$output = str_replace("\r", "", $text);
		$contentSectionText = "";

		$output = htmlentities($output);

		// Make the table of contents and parse headings and actions points
		$contentSection = $this->makeContents($output);

		// Parse line markup. i.e. * #
		$output = $this->parseLineMarkup($output);

		// Parse links, images

		$lastLevel = 1;
		if (count($contentSection) > 4) {
			foreach ($contentSection as $k => $v) {
				if ($lastLevel < $v["Level"]) {
					$contentSectionText .= "<ul>\n";
				}
				while ($lastLevel -- > $v["Level"]) {
					$contentSectionText .= "\t</ul>\n";
				}
				$contentSectionText .= "\t\t<li>$k. " . $v["Text"] . "</li>\n";
				$lastLevel = $v["Level"];
			}
			while ($lastLevel -- > 1) {
				$contentSectionText .= "\t</ul>\n";
			}
			$contentSectionText = "<div class=\"wiki-contents\">" .
			"<div id=\"advanced\"\"><h2><span>Contents</span></h2></div>" .
			"<div id=\"extra\">$contentSectionText</div>" . 
			"<div class=\"footer\"><span>&nbsp;</span></div></div>\n";
		}
		return "$contentSectionText\n$output";
	}

	public function createImageSelector($sourcePath, $width, $height, 
	 	$className, $caption, $crop = true) {
	 		
		if ($sourcePath) {
			$application = CoreFactory::getApplication();
			$imageName = $width . "x" . $height . "-" . basename($sourcePath);
			$imageHash = 0x100000000 + crc32($sourcePath);
			$imageDirectory = $application->registry->get("Cache/Path") . "/" . $imageHash;
			$imagePath = $imageDirectory . "/" . $imageName;

			if (!is_file($imagePath)) {
				@mkdir($imageDirectory, false, true);
 				$imageControl = CoreFactory::getImageControl();

 				if ($width == null || $height == null) {
 					$crop = false;
 				}
				if (substr($sourcePath, 0, 4) == "http") {
					$tempName = tempnam("/tmp", $imageHash);
					copy($sourcePath, $tempName);
					$imageControl->resizeAndCrop($tempName, $imagePath, $width, $height, false, !$crop);
					unlink($tempName);
				} else {
					$sourcePath = $application->registry->get("Path") . "/" . $sourcePath;
					$imageControl->resizeAndCrop($sourcePath, $imagePath, $width, $height, false, !$crop);
				}
			}
			$sourcePath = "/resource/cache/" . $imageHash . "/" . $imageName;
		}
		if ($caption == "") {
			return "<span class=\"$className\"><img src=\"$sourcePath\" width=\"$width\" " .
			"height=\"$height\" alt=\"Linked Image('$sourcePath')\" " .
			"title=\"Linked Image('$sourcePath')\" /></span>";
		} else {
			return "<span class=\"$className\"><img src=\"$sourcePath\" width=\"$width\" " .
			"height=\"$height\" alt=\"$caption\" title=\"$caption\" /><span>$caption</span></span>";
		}
	}

	public function parseMarkup($text) {
		$searchArray = array (
			"'\'\'\'(.*?)\'\'\''is",
			"'\'\'(.*?)\'\''is",
			"'\[image:([^|]*?)((\|(frame))|(\|(left|right|center))){0,2}\]'ise",
			"'\[image:([^|]*?)((\|(frame))|(\|(left|right|center))){0,2}(\|(\d+)?x(\d+)?)\]'ise",
			"'\[image:([^|]*?)((\|(frame))|(\|(left|right|center))){0,2}(\|(\d+)?x(\d+)?)(\|(.*?))?\]'ise",
			"'\[image:([^|]*?)((\|(frame))|(\|(left|right|center))){0,2}(\|(.*?))?\]'ise",
			"'\[(http(s?)://.*?)(\|)(.*?)\]'i",
			"'\[(http(s?)://\S*?)\]'i",
			"'\[(mailto:.*?)(\|)(.*?)\]'i",
			"'\[(mailto:(\S*?))\]'i",
			"'\[(.*)(\|)(.*?)\]'i",
			"'\[(.*)\]'i"
		);

		//"<img src=\"/photo/cache/$8x$9/$1\" class=\"$4 $6\" title=\"Linked Image ('$1')\" alt=\"Linked Image ('$1')\" width=\"$8\" height=\"$9\" />",
		$replaceArray = array (
			"<em>$1</em>",
			"<strong>$1</strong>",
			"Atrox_Core_Data_Formatter_Wiki::createImageSelector('$1', '', '', '', '')",
			"Atrox_Core_Data_Formatter_Wiki::createImageSelector('$1', '$8', '$9', '$4 $6', '')",
			"Atrox_Core_Data_Formatter_Wiki::createImageSelector('$1', '$8', '$9', '$4 $6', '$11')",
			"<img src=\"$1\" class=\"$4 $6\" title=\"$8\" alt=\"$8\" />",
			"<a href=\"$1\" target=\"_blank\" class=\"external\">$4</a>",
			"<a href=\"$1\" target=\"_blank\" class=\"external\">$1</a>",
			"<a href=\"$1\" target=\"_blank\" class=\"email\">$3</a>",
			"<a href=\"$1\" target=\"_blank\" class=\"email\">$1</a>",
			"<a href=\"$1\">$3</a>",
			"<a href=\"$1\">$1</a>");

		$text = preg_replace($searchArray, $replaceArray, $text);
		return $text;
	}

	public function popStack(& $stack) {
		$stackSymbol = array_pop($stack);
		switch ($stackSymbol) {
			case Atrox_Core_Data_Formatter_Wiki::MRK_PLAIN :
				if ($this->checkStack($stack) != Atrox_Core_Data_Formatter_Wiki::MRK_PLAIN) {
					return "</pre>\n";
				}
				break;
			case Atrox_Core_Data_Formatter_Wiki::MRK_OL :
				return "</ol>\n";
			case Atrox_Core_Data_Formatter_Wiki::MRK_UL :
				return "</ul>\n";
			case Atrox_Core_Data_Formatter_Wiki::MRK_DIV :
				return "</div>\n";
		}
		return false;
	}

	public function popEntireStack(& $stack) {
		$output = "";
		while ($pop = $this->popStack($stack)) {
			$output .= $pop;
		}
		return $output;
	}

	public function pushStack(& $stack, $symbol) {
		array_push($stack, $symbol);
	}

	public function checkStack(& $stack) {
		if (isset ($stack[count($stack) - 1])) {
			return $stack[count($stack) - 1];
		}
	}

	public function parseLineMarkup($text) {
		$lines = explode("\n", $text);
		$stack = array ();
		$currentListLevel = 0;
		$output = "";
		for ($i = 0; $i < count($lines); $i ++) {
			if (isset ($lines[$i][0])) {
				switch ($lines[$i][0]) {
					case ":" :
						$currentListLevel = 0;
						$output .= $this->popEntireStack($stack);
						$output .= "<p>&nbsp;&nbsp;" . $this->parseMarkup(mb_substr($lines[$i], 1)) . "</p>\n";
						break;
					case " " :
						if ($this->checkStack($stack) != Atrox_Core_Data_Formatter_Wiki::MRK_PLAIN) {
							$currentListLevel = 0;
							$output .= $this->popEntireStack($stack);
							$output .= "<pre>\n";
							$this->pushStack($stack, Atrox_Core_Data_Formatter_Wiki::MRK_PLAIN);
						}
						$output .= mb_substr($lines[$i], 1) . "\n";
						break;
					case "*":
					case "#":
						$level = 1;
						if ($lines[$i][0] == "#") {
							$symbol = Atrox_Core_Data_Formatter_Wiki::MRK_OL;
							$openingTag = "<ol>\n";
						} else if ($lines[$i][0] == "*") {
							$symbol = Atrox_Core_Data_Formatter_Wiki::MRK_UL;
							$openingTag = "<ul>\n";
						}
						while ((isset($lines[$i][$level])) && (($lines[$i][$level] == "*") || ($lines[$i][$level] == "#"))) {
							if ($lines[$i][$level] == "#") {
								$symbol = Atrox_Core_Data_Formatter_Wiki::MRK_OL;
								$openingTag = "<ol>\n";
							} else if ($lines[$i][$level] == "*") {
							 $symbol = Atrox_Core_Data_Formatter_Wiki::MRK_UL;
								$openingTag = "<ul>\n";
							}
							$level ++;
						}
						if (($this->checkStack($stack) != Atrox_Core_Data_Formatter_Wiki::MRK_OL) && 
							($this->checkStack($stack) != Atrox_Core_Data_Formatter_Wiki::MRK_UL)) {
							$currentListLevel = 0;
							$output .= $this->popEntireStack($stack);
						}
						if ($level > $currentListLevel) {
							$output .= $openingTag;
							$this->pushStack($stack, $symbol);
						} else if ($level < $currentListLevel) {
								$currentListLevel --;
								$output .= $this->popStack($stack);
						} else if ($this->checkStack($stack) != $symbol) {
							$output .= $this->popStack($stack);
							$output .= $openingTag;
							$this->pushStack($stack, $symbol);
						}
						$output .= "<li>" . $this->parseMarkup(mb_substr($lines[$i], $level)) . "</li>\n";
						$currentListLevel = $level;
						break;
					case "<" :
					case "=" :
						$currentListLevel = 0;
						$output .= $this->popEntireStack($stack);
						$output .= $this->parseMarkup($lines[$i]) . "\n";
						break;
					default:
						$currentListLevel = 0;
						$output .= $this->popEntireStack($stack);
						$output .= "<p>". $this->parseMarkup($lines[$i]) ."</p>\n";
				}
			}
		}
		$output .= $this->popEntireStack($stack);
		return $output;
	}

	public function makeContents(&$text) {
		$output = "";
		$this->contents(null, null, null, true);
		$text = preg_replace_callback("'^(=+)(#?)(.*?)(=+)\s*$'m", array ("self", "headings"), $text);
		return $this->contents(null);
	}

	public function contents($text, $level = null, $number = null, $clear = false) {
		static $contents;
		if ($clear) {
			$contents = array ();
		}
		if ($text) {
			$contents[$number]["Text"] = $text;
			$contents[$number]["Level"] = $level;
		}
		return $contents;
	}

	public function headings($matches) {
		static $level;
		$i = mb_strlen($matches[1]);
		if (isset($level[$i]["Count"])) {
			$level[$i]["Count"]++;
		} else {
			$level[$i]["Count"] = 1;
		}
		if ($level[$i]["Count"] != null) {
			$level[$i + 1]["Count"] = 0;
		}

		$anchor = "";

		foreach ($level as $l) {
			if ($l["Count"] > 0) {
				$anchor .= $l["Count"].".";
			}
		}
		$anchor = mb_substr($anchor, 0, -1);
		$text = $matches[3];
		if ($matches[2] == "#") {
			$text = $anchor.". ".$matches[3];
		}
		$this->contents("<a href=\"#Heading-$anchor\">{$matches[3]}</a>", $i, $anchor);
		$headingSelector = $i + 1;
		return "<h$headingSelector>$text<a name=\"Heading-$anchor\"></a></h$headingSelector>";
	}
}