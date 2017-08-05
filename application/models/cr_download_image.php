<?php
// 폰관련처리를 위한 모델
class cr_download_image extends CI_Model {
    function __construct()
    {
		parent::__construct();
	}
	

	// 이미지 다운로드(이미지 사이즈 변화가능)
	public function download_img($width, $rate, $image_path)	
	{
		if($width < 1 || $image_path == "") return false;

		global $baseDir;
		$image_url = $_SERVER["DOCUMENT_ROOT"].$baseDir.$image_path;

		if(!file_exists($image_url))
			return false;
			
		$proc_img = new SimpleImage();
		$proc_img->load($image_url);
		
		if ($width > 1)	$proc_img->resizeToWidth($width);
		
		header('Content-Type: image/jpeg');
		if(isset($rate)) imagejpeg($proc_img->image, null, $rate);
		else imagejpeg($proc_img->image);
		imagedestroy($proc_img->image);
		return true;
	}
}
?>