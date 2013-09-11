<?php

class GD_Resize
{
	// Fonction pour redimensionner une image
	public static function run($source, $destination, $width, $height = "")
	{
        // Get the image's MIME
        $mime = exif_imagetype($source);
        
		// Check if the MIME is supported
		switch($mime)
        {
			case IMAGETYPE_JPEG :
				$source = imagecreatefromjpeg($source);
                break;
			
			case IMAGETYPE_PNG :
				$source = imagecreatefrompng($source);
                break;
				
			case IMAGETYPE_GIF :
				$source = imagecreatefromgif($source);
                break;
			
			default :
				return; // No support
		}

		// Get the width and height of the source
		$width_src = imagesx($source);
		$height_src = imagesy($source);
		
		// Initialize the height and width of image destination
		$width_dest = 0;
		$height_dest= 0;
		
		// If the height is not provided, keep the proportions
		if(!$height)
        {
			// Get the ratio
			$ratio = ($width * 100) / $width_src;
			
			// Need resize ?
			if ($ratio>100)
            {
				imagejpeg($source, $destination, 70);
				imagedestroy($source);
				return;
			}
			
			// height and width of image resized
			$width_dest = $width;
			$height_dest = $height_src * $ratio/100;
			
		}
		else
        {
			if($height_src >= $width_src )
            {
				$height_dest = ($height_src * $width ) / $width_src;
				$width_dest = $width;
			}
			else if($height_src < $width_src)
            {
				$width_dest = ($width_src * $height ) / $height_src;
				$height_dest = $height;
			}
		
		}

		// Build the image resized
		$emptyPicture = imagecreatetruecolor($width, ($height)?$height:$height_dest);
		imagecopyresampled($emptyPicture, $source, 0, 0, 0, 0, $width_dest, $height_dest, $width_src, $height_src);
		
		// Save image
		imagejpeg($emptyPicture, $destination, 70);
		
		// Destruct tmp images
		imagedestroy($source);
		imagedestroy($emptyPicture);
		
		return;
	}
}
