<?php
/**
 * This class is used to color Christ's words in red.
 * Important Note: This class assumes proper placement of “ ” (Not " ") to mark the verses
 *  
 * @author jayarathina
 * @version 3.0
 * 
 */
class RedLetter {
	
	private $inst = array();
	
	/**
	 * @param string $inst - Instructions for coloring from DB; (See switch case in colorRedLetter function)
	 * 
	 */
	function __construct($insts) {
		foreach ($insts as $val) {
			if(empty($val['id_to'])){
				$val['id_to'] = $val['id_from'];
			}
			$this->inst += array_fill($val['id_from'], $val['id_to']-$val['id_from']+1, $val['inst']);
		}
	}

	/**
	 * @param string $vs - Verse text
	 * @param string $vno - Verse number
	 * @return string - Colored String
	 */
	function colorRedLetter($vs, $vno) {
		if(! isset($this->inst[$vno]))  return $vs;

		$rt=''; //Return value
		if(strpos($vs, BLIB_VERSE_NUMBER_END) !== false){
			$vs_t = explode(BLIB_VERSE_NUMBER_END, $vs);
			$rt= $vs_t[0] . BLIB_VERSE_NUMBER_END; //Coupled verses start
			$vs = $vs_t[1];
		}

		$inst = $this->inst[$vno];

		switch ($inst) {//What should be colored?
			case '1':	//Only text within first double quotes
			case '2':	//Only text within second double quotes
			case '1,3':	//First and third quote 49021031; 52021015-52021017
				$v = explode('”', $vs);
				$ct = explode(',', $inst);
				foreach ($ct as $vl) {
					$vt = $vl-1;
					$v[$vt] = $this->defaultAllQuotes ($v[$vt] . '”');
					$v[$vt] = rtrim($v[$vt], '”');//Remove the extra quote added in the prev line
				}
				$rt .= implode('”', $v);
				break;

			default://All text within double quotes
				$rt .= $this->defaultAllQuotes ($vs);
				break;
		}

		$rt = str_replace(BLIB_RED_LTR_END . '”', '”' . BLIB_RED_LTR_END, $rt);
		$rt = str_replace('“'.BLIB_RED_LTR_START, BLIB_RED_LTR_START.'“', $rt);
		
		return $rt;
	}
	
	/**
	 * @param string $vs - Verse text; Note that this class assumes proper placement of “ ” (Not " ") to mark the verses
	 * @return string - String with start and end positions to be tagged 
	 */
	
	private function defaultAllQuotes($vs) {
		//Start and end marker to mark the begining and en
		
		$openQc = substr_count($vs, '“');
		$closeQc = substr_count($vs, '”');
		
		if($openQc <= 1 && $closeQc <= 1){
			//There is one or less than one open and close quote. 
			
			if($openQc === 0){//If open quotes is missing, then prepend
				$vs = BLIB_RED_LTR_START . $vs;
			}else{
				$vs = str_replace('“', '“'.BLIB_RED_LTR_START , $vs);
			}
		
			if($closeQc === 0){//If close quotes is missing, then append
				$vs .= BLIB_RED_LTR_END;
			}else{
				$vs = str_replace('”', BLIB_RED_LTR_END.'”', $vs);
			}
		
			$oQLoc = strpos($vs, '“');
			$cQLoc = strpos($vs, '”');
		
			if( ($oQLoc > $cQLoc) && ($openQc === 1) && ($closeQc === 1) ){
				//Quotes are opposite, eg: [ ..some text” said he, and then he continued “loremp ipsum...   ]
				$vs = BLIB_RED_LTR_START.$vs.BLIB_RED_LTR_END;
			}
		
		}else{
			$vss = explode('”', $vs);
			foreach ($vss as $key => $value) {
				if($key === count($vss)-1 ){//In the last segment
					$openQc = substr_count($value, '“');
					if($openQc === 0){//50005041 //if it does not contains an open quote
						break;// then don't color the last segment
					}
				}
				
				if(! empty($value))//if verse ends with ”, then last value will be empty 
					$vss[$key] = $this->defaultAllQuotes($value . '”');
			}
			$vs = implode('”', $vss);
		}
		$vs = str_replace('””', '”', $vs);
		return $vs;
	}	
}