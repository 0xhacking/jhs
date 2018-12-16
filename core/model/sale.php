<?php  if( !defined("IN_IA") ) 
{
	exit( "Access Denied" );
}
class Sale_EweiShopV2Model 
{
	public function getFullBackText($echo = false) 
	{
		$text = "全返";
		$set = m("common")->getSysset("fullback");
		if( !empty($set["text"]) ) 
		{
			$text = $set["text"];
		}
		if( $echo ) 
		{
			echo $text;
		}
		else 
		{
			return $text;
		}
	}
}
?>