<?php

class TDImageWatermark {

	public function getImgObj($imgPath) {
		$ext = strtolower(strstr($imgPath, "."));
		$img = null;
		switch ($ext) {
			case '.gif': $img = imagecreatefromgif($imgPath);
				break;
			case '.jpg': $img = imagecreatefromjpeg($imgPath);
				break;
			case '.png': $img = imagecreatefrompng($imgPath);
				break;
			case '.bmp': $img = imagecreatefromwbmp($imgPath);
				break;
		}
		return $img;
	}

	public function outputImg($imgObj, $imgPath) {
		$ext = strtolower(strstr($imgPath, "."));
		switch ($ext) {
			case '.gif': imagegif($imgObj, $imgPath);
				break;
			case '.jpg': imagejpeg($imgObj, $imgPath);
				break;
			case '.png': imagepng($imgObj, $imgPath);
				break;
			case '.bmp': imagewbmp($imgObj, $imgPath);
				break;
		}
	}
}
