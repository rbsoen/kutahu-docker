<?php
//BindEvents Method @1-A690A530
function BindEvents()
{
    global $lblTopLink;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
}
//End BindEvents Method

//lblTopLink_BeforeShow @5-1DF6B4AB
function lblTopLink_BeforeShow()
{
    $lblTopLink_BeforeShow = true;
//End lblTopLink_BeforeShow

//Custom Code @6-89D6DE21
// -------------------------
    global $lblTopLink;
	$MyLink="&nbsp;<a class='CobaltDataLinkTop' href=\"Question.php". "?QueType=k&" . CCGetQueryString("QueryString", Array("QueType","ccsForm"))."\"><img Title=\"Pertanyaan Sub Bab\" src=\"../Images/question.gif\" border=\"0\"></a>";
	$MyLink=$MyLink."&nbsp;<a class='CobaltDataLinkTop' href=\"HomeUser.php\"><img Title=\"Daftar Modul\" src=\"../Images/home.gif\" border=\"0\"></a>";
	$MyLink=$MyLink."&nbsp;<a class='CobaltDataLinkTop' href=\"../Login.php?Logout=1\"><img Title=\"Keluar\" src=\"../Images/logout.gif\" border=\"0\"></a>";

	$lblTopLink->SetValue($MyLink);

    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblTopLink_BeforeShow @5-FEF64FEE
    return $lblTopLink_BeforeShow;
}
//End Close lblTopLink_BeforeShow


?>
