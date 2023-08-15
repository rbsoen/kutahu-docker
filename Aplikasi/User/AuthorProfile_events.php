<?php
//BindEvents Method @1-70D3B501
function BindEvents()
{
    global $authors;
    $authors->AutPhoto->CCSEvents["BeforeShow"] = "authors_AutPhoto_BeforeShow";
    $authors->AutEmail->CCSEvents["BeforeShow"] = "authors_AutEmail_BeforeShow";
    $authors->lblExperience->CCSEvents["BeforeShow"] = "authors_lblExperience_BeforeShow";
}
//End BindEvents Method

//authors_AutPhoto_BeforeShow @11-A3088C25
function authors_AutPhoto_BeforeShow()
{
    $authors_AutPhoto_BeforeShow = true;
//End authors_AutPhoto_BeforeShow

//Custom Code @15-08448E95
// -------------------------
    global $authors;
	if($authors->AutPhoto->GetValue()!="")
	{
	$authors->AutPhoto->SetValue( "<table cellspacing=\"0\" cellpadding=\"0\" width=\"100\" border=\"1\" height=\"130\"><tr><td valign=\"center\" align=\"middle\"><img width=\"100\" src=\"../Images/Author/".$authors->AutPhoto->GetValue()."\" border=\"0\"></td></tr> </table>");
	}
	else
	{
	$authors->AutPhoto->SetValue("<table cellspacing=\"0\" cellpadding=\"0\" width=\"100\" border=\"1\" height=\"130\"><tr><td valign=\"center\" align=\"middle\">Tidak ada foto</td></tr> </table>");

     	
	}
    // Write your own code here.
// -------------------------
//End Custom Code

//Close authors_AutPhoto_BeforeShow @11-0DDABF79
    return $authors_AutPhoto_BeforeShow;
}
//End Close authors_AutPhoto_BeforeShow

//authors_AutEmail_BeforeShow @10-8162207C
function authors_AutEmail_BeforeShow()
{
    $authors_AutEmail_BeforeShow = true;
//End authors_AutEmail_BeforeShow

//Custom Code @16-08448E95
// -------------------------
    global $authors;
	if($authors->AutEmail->GetValue()!="")
		{
			$authors->AutEmail->SetValue("<a href=\"mailto:".$authors->AutEmail->GetValue()."\">".$authors->AutEmail->GetValue()."</a>");
		}
    // Write your own code here.
// -------------------------
//End Custom Code

//Close authors_AutEmail_BeforeShow @10-D7A25039
    return $authors_AutEmail_BeforeShow;
}
//End Close authors_AutEmail_BeforeShow

//authors_lblExperience_BeforeShow @17-E58E9EDD
function authors_lblExperience_BeforeShow()
{
    $authors_lblExperience_BeforeShow = true;
//End authors_lblExperience_BeforeShow

//Custom Code @21-08448E95
// -------------------------
    global $authors;
		$view="<table width=100% border=0 class=NormalFont> ";
		if($authors->AutExperience1->GetValue()!="")
		{
		$data=explode("|",$authors->AutExperience1->GetValue());
		$view=$view."<tr><td width=1%>1.</td><td>". $data[0]."</td><td width=1% nowrap> tahun : ".$data[1]."</td></tr>";
		} else {$view=$view."<tr><td width=1%>1.</td><td>&nbsp;</td><td width=1%>&nbsp;</td></tr>";}

		if($authors->AutExperience2->GetValue()!="")
		{
		$data=explode("|",$authors->AutExperience2->GetValue());
		$view=$view."<tr><td width=1%>2.</td><td>". $data[0]."</td><td width=1% nowrap> tahun : ".$data[1]."</td></tr>";
		} else {		$view=$view."<tr><td width=1%>2.</td><td>&nbsp;</td><td width=1%>&nbsp;</td></tr>";}

		if($authors->AutExperience3->GetValue()!="")
		{
		$data=explode("|",$authors->AutExperience3->GetValue());
		$view=$view."<tr><td width=1%>3.</td><td>". $data[0]."</td><td width=1% nowrap> tahun : ".$data[1]."</td></tr>";
		} else {$view=$view."<tr><td width=1%>3.</td><td>&nbsp;</td><td width=1%>&nbsp;</td></tr>";}
		$view=$view."</table>";
		$authors->lblExperience->SetValue($view);
    // Write your own code here.
// -------------------------
//End Custom Code

//Close authors_lblExperience_BeforeShow @17-FABBBFC7
    return $authors_lblExperience_BeforeShow;
}
//End Close authors_lblExperience_BeforeShow


?>
