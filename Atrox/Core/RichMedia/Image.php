<?php
/**
 * @package Core
 * @subpackage Core/RichMedia
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 */

/**
 * Image manipulation functions
 *
 * @author Paul Serby<paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 927 $ - $Date: 2009-04-23 13:53:11 +0100 (Thu, 23 Apr 2009) $
 * @package Core
 * @subpackage Core/RichMedia
 */
class Atrox_Core_RichMedia_Image {

	public static function crop($sourceFile, $destinationFile, $startX, $startY, $endX, $endY,
		$destinationWidth = null, $destinationHeight = null) {
	}

	public static function autoCrop($sourceFile, $destinationFile, $destinationWidth = null, $destinationHeight = null, $outputQuality = 90) {

		if (!file_exists($sourceFile)) {
			throw new Atrox_Core_Exception_NoSuchFileException("Source file '$sourceFile' does not exisit");
		}

		if (!file_exists($destinationFile)) {
			throw new Atrox_Core_Exception_NoSuchFileException("Destination file '$destinationFile' does not exisit");
		}

		clearstatcache();
		$size = getimagesize($sourceFile);

		// If the source and destination are the same do nothing
		if ((($destinationWidth == null && $destinationHeight == null)) ||
			(($size[0] == $destinationWidth) && ($size[1] == $destinationHeight))) {

			// If the sizes are the same just copy the source file
			if ($sourceFile != $destinationFile) {
				copy($sourceFile, $destinationFile);
			}
			return true;
		}

		switch($size[2]) {
			case 1:
				$sourceImage = imagecreatefromgif($sourceFile);
				break;
			case 2:
				$sourceImage = imagecreatefromjpeg($sourceFile);
				break;
			case 3:
				$sourceImage = imagecreatefrompng($sourceFile);
				break;
			default:
				throw new Atrox_Core_Exception_InvalidFileFormatException("'$sourceFile' must be either a gif, jpg, or png");
				return false;
		}

		$sourceWidth = imagesx($sourceImage);
		$sourceHeight = imagesy($sourceImage);

		if ($destinationWidth == "") {
			$destinationWidth = $sourceWidth;
		}

		if ($destinationHeight == "") {
			$destinationHeight = $sourceHeight;
		}

		// Width and height aspect ratios
		$widthRatio = $destinationWidth / $sourceWidth;
		$heightRatio = $destinationHeight / $sourceHeight;

		$wOffset = 0;
		$hOffset = 0;

		$destinationImage = imagecreatetruecolor($destinationWidth, $destinationHeight);

		$newSourceWidth = $sourceWidth;
		$newSourceHeight = $sourceHeight;
		if ($widthRatio > $heightRatio) {
			$newSourceHeight = ($destinationHeight / $destinationWidth) * $destinationWidth;
			$hOffset = ($sourceHeight - $newSourceHeight) / 2;
		} else {

			$newSourceWidth = ($destinationWidth / $destinationHeight) * $sourceHeight;
			$wOffset = ($sourceWidth - $newSourceWidth) / 2;
		}

		imagecopyresampled($destinationImage, $sourceImage, 0, 0, $wOffset, $hOffset, $destinationWidth, $destinationHeight, $newSourceWidth, $sourceHeight);

		clearstatcache();
		switch($size[2]) {
			case 1:
				imagegif ($destinationImage, $destinationFile, $outputQuality);
				break;
			case 2:
				imagejpeg($destinationImage, $destinationFile, $outputQuality);
				break;
			case 3:
				imagepng($destinationImage, $destinationFile);
				break;
		}
	}
}