<?php session_start();
 
class CaptchaSecurityImages
{
 
	var $font = 'monofont.ttf';
 
	function generateCode($characters)
	{
      /* list all possible characters, similar looking characters and vowels have been removed */
      $possible = '23456789';
      $code = '';
      $i = 0;
    while ($i < $characters)
	{ 
         $code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
         $i++;
    }
      return $code;
   }
 
   function CaptchaSecurityImages($width='100',$height='50',$characters='3') 
	{
	   
      $code = $this->generateCode($characters);
      $code=strtoupper($code);
      $font_size = $height * 0.75;
      $image = @imagecreate($width, $height) or die('Cannot initialize new GD image stream');
      
      $background_color = imagecolorallocate($image, 255, 255, 255);
      $text_color = imagecolorallocate($image, 220, 40, 100);
      $noise_color = imagecolorallocate($image, 250, 250, 250);
      /* generate random dots in background */
		for( $i=0; $i<70; $i++ )
		{
			 imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
		}
      
      $font = imageloadfont("font/anonymous.gdf");
	//$font = imageloadfont("../font1/sfballoon.gdf");
		imagestring($image, $font, 10, 0,  $code, $text_color);

	  header('Content-Type: image/jpeg');
      imagejpeg($image);
      imagedestroy($image);

      $_SESSION['imgcode'] = $code;
   }
 
}

$captcha = new CaptchaSecurityImages(100,50,3);
 
?>