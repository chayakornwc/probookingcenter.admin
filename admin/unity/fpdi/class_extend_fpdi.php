<?php
//=========================================================================================
// --------------------------- Define Extends class (by BEFIRST-IT) ------------------------
class PDF extends FPDI
{
	public function __call($name, $arguments)
    {
        if ($name === 'Write_text_thai'){
            if(count($arguments) === 3 ){
                return $this->Write_text_thai($arguments[0],$arguments[1],$arguments[2]);
            }
            if(count($arguments) === 5){
                return $this->Write_text_thai_2($arguments[0], $arguments[1],$arguments[2],$arguments[3],$arguments[4]);
            }
        }
    }
	
	private function Write_text_thai($cor_x,$cor_y,$text)
	{
		$this->SetXY($cor_x, $cor_y);
		$this->Write(0, iconv( ENCODE_FROM , ENCODE_TO , $text ));
		
	}
	
	private function Write_text_thai_2($cor_x_start,$cor_x_end,$cor_y,$text,$align)			// Overload function
	{
		$cor_width = $cor_x_end - $cor_x_start;
		$cor_center = $cor_x_start + ($cor_width/2);
		
		$this->SetXY($cor_x_start, $cor_y);
		$this->Cell( $cor_width ,4,iconv( ENCODE_FROM , ENCODE_TO , $text ),'',0,$align);
		//For show Column border
		//$this->Cell( $cor_width ,4,iconv( ENCODE_FROM , ENCODE_TO , $text ),'LR',0,$align);
	}
	
	function Write_check($cor_x,$cor_y,$size)
	{
		$this->Image(CHECK_IMAGE_PATH,$cor_x,$cor_y,$size);
	}	
}

?>