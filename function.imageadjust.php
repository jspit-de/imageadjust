<?php
 /*
  * @return resource of GD
  * @param x: width, y: height, scaling false/true or a factor (float)
  * @example $img2 = imageadjust($img,16,9); //adjust to 16:9 Format
  * return false if error
  */  
  function imageadjust($img,$x,$y,$scaling = false) {
    if(!is_resource($img) OR get_resource_type($img) != "gd") return false;
    $srcx = $xCut = imagesx($img);
    $srcy = $yCut = imagesy($img);
    if($srcx < 1 OR $srcy < 1) return false;
    $xstart = $ystart = 0;
    if($x > 0 AND $y > 0) {
      $dstRelation = $x/$y;
      $srcRelation = $srcx/$srcy;
      if($srcRelation > $dstRelation) {
        //reduce x
        $xCut = $srcy * $dstRelation;
        $xstart = (int)round(($srcx - $xCut)/2); 
        $xCut = (int)round($xCut);
      }
      elseif($srcRelation < $dstRelation) {
        //reduce y
        $yCut = $srcx / $dstRelation;
        $ystart = (int)round(($srcy - $yCut)/2);
        $yCut = (int)round($yCut);    
      }
    }
    if(is_numeric($scaling) AND $scaling > 0) {
      //scaling is a factor
      $dstX = $scaling * $xCut;
      $dstY = $scaling * $yCut;
    }
    else {
      $dstX = $scaling ? $x : $xCut;
      $dstY = $scaling ? $y : $yCut;
    }
    
    $dstImg = imagecreate($dstX,$dstY);
    $imOk = imagecopyresampled($dstImg,$img,
      0,0,             //dst_x, dst_y
      $xstart,$ystart, // src_x, src_y
      $dstX,$dstY, //$xCut,$yCut,     // dst_w, dst_h 
      $xCut,$yCut     // src_w, src_h
    );
    return $dstImg;  
  }

