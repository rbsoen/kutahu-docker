<?php
// //Events @1-F81417CB

//MenuAuthor_BeforeShow @1-37B8BC7F
function MenuAuthor_BeforeShow()
{
    $MenuAuthor_BeforeShow = true;
//End MenuAuthor_BeforeShow

//Custom Code @3-A51B1834
// -------------------------
    global $MenuAuthor;
	 if(CCGetSession("AutLevel")!="1")
	 	{
			$MenuAuthor->lblMenu->Visible=false;
		}
    // Write your own code here.
// -------------------------
//End Custom Code

//Close MenuAuthor_BeforeShow @1-CF7091FB
    return $MenuAuthor_BeforeShow;
}
//End Close MenuAuthor_BeforeShow


?>
