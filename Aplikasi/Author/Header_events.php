<?php
// //Events @1-F81417CB
function GetAutID($ModID)	
{
	$MyAutID=0;
	$db = new clsDBConnection1();    
    $db->query("select AutUsername from `module` where ModID=".$ModID);
 	$Result = $db->next_record();
    if($Result)
    {
		$MyAutID=$db->f("AutUsername");
	}
    $db->close();
	return $MyAutID;
}
//Header_BeforeShow @1-DACF0966
function Header_BeforeShow()
{
    $Header_BeforeShow = true;
//End Header_BeforeShow

//Custom Code @2-DBA303E4
// -------------------------
    	global $Header;
		$Header->lblDate->SetValue("<font class='SmallFontBold'>".CCGetSession("AutName")."</font><font class='SmallFont'> (".CCGetSession("AutDate").")</font>");

		$ModID=CCGetFromGet("ModID", "");
		if(CCGetSession("AutID")=="")
		{
			Header("Location:../Login.php");
		}
		else
			{
				if(CCGetSession("AutLevel")!="1")
				{
					if($ModID !="")
					{
						if(CCGetSession("AutID") != GetAutID($ModID))
						{
							Header("Location:../Login.php");
						}
					}
				}
			}
    // Write your own code here.
// -------------------------
//End Custom Code

//Close Header_BeforeShow @1-E0152CE0
    return $Header_BeforeShow;
}
//End Close Header_BeforeShow


?>
