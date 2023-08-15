<?php
//BindEvents Method @1-76C65C40
function BindEvents()
{
    global $lblTopLink;
    global $authors;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $authors->CCSEvents["BeforeShowRow"] = "authors_BeforeShowRow";
    $authors->CCSEvents["BeforeSelect"] = "authors_BeforeSelect";
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
	$MyLink="<a class='CobaltDataLinkTop' href=\"AuthorList.php". "?" . CCGetQueryString("QueryString", Array("AutUsername","update_act","on","del_aut","ccsForm"))."\">Daftar Penulis</a>";
	$lblTopLink->SetValue($MyLink);

    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblTopLink_BeforeShow @5-FEF64FEE
    return $lblTopLink_BeforeShow;
}
//End Close lblTopLink_BeforeShow
function StrDelete($str)
{
$str=ereg_replace("'","`",$str);
$str=ereg_replace('"',"`",$str);
$str=htmlspecialchars($str);
return $str;
}
//authors_BeforeShowRow @7-3B7FF33C
function authors_BeforeShowRow()
{
    $authors_BeforeShowRow = true;
//End authors_BeforeShowRow

//Custom Code @23-08448E95
// -------------------------
    global $authors;
	$str="";

	
	if($authors->hdnAutActive->GetValue()=="1")
	{
	$str.="<a href=\"javascript:#\" onclick=\"javascript:return Active('".StrDelete($authors->AutName->GetValue())."','AuthorList.php?AutUsername=".$authors->lblDelete->GetValue()."&on=".$authors->hdnAutActive->GetValue()."&update_act=true&". CCGetQueryString("QueryString", Array("AutUsername","update_act","on","ccsForm"))."',this)\"><img title=\"Klik untuk Tidak Aktif\" src=\"../Images/on.gif\" border=\"0\"></a>";
	}
	else
	{
	$str.="<a href=\"javascript:#\" onclick=\"javascript:return Active('".StrDelete($authors->AutName->GetValue())."','AuthorList.php?AutUsername=".$authors->lblDelete->GetValue()."&on=".$authors->hdnAutActive->GetValue()."&update_act=true&". CCGetQueryString("QueryString", Array("AutUsername","update_act","on","ccsForm"))."',this)\"><img title=\"Klik untuk Aktif\" src=\"../Images/off.gif\" border=\"0\"></a>";

	}
			
	$authors->lblDelete->SetValue("<a href=\"javascript:#\" onclick=\"javascript:return Delete('".StrDelete($authors->AutName->GetValue())."','AuthorList.php?AutUsername=".$authors->lblDelete->GetValue()."&del_aut=true&". CCGetQueryString("QueryString", Array("AutUsername","update_act","on","del_aut","ccsForm"))."',this)\"><img title=\"Hapus\" src=\"../Images/delete.gif\" border=\"0\"></a>");
	$authors->lblActive->SetValue($str);
    // Write your own code here.
// -------------------------
//End Custom Code

//Close authors_BeforeShowRow @7-2CF8020B
    return $authors_BeforeShowRow;
}
//End Close authors_BeforeShowRow
function MyTrigger($SqlSelect,$SqlDelete,$ErrorMessage)	
{
	global $lblError;
	$db = new clsDBConnection1();    
    $db->query($SqlSelect);
 	$Result = $db->next_record();
    if($Result)
    {
		$lblError->SetValue("<script language=\"javascript\">function MyInformation(){alert(\"".$ErrorMessage."\");}window.onload=MyInformation;</script>");
	}
	else
	{
		$db->query($SqlDelete);
	}
    $db->close();
}
//authors_BeforeSelect @7-980C49A2
function authors_BeforeSelect()
{
    $authors_BeforeSelect = true;
//End authors_BeforeSelect

//Custom Code @24-08448E95
// -------------------------
    global $authors;
	$del_aut=CCGetFromGet("del_aut", "");
	$AutUsername=CCGetFromGet("AutUsername", "");

 	$AutPhoto="";
	 
	
	
	
		if($del_aut)
		{

		$db = new clsDBConnection1();
		$db->query("Select AutPhoto FROM authors where AutUsername=" . $db->ToSQL($AutUsername, ccsText));
		while($db->next_record())
		{
			$AutPhoto=$db->f("AutPhoto");
		}

		$SqlSelect="select * from module where AutUsername=". $db->ToSQL($AutUsername, ccsText);
	 	$SqlDelete="DELETE FROM authors where AutUsername=" . $db->ToSQL($AutUsername, ccsText);
	 	$ErrorMessage="Penulis tidak dapat dihapus";
		MyTrigger($SqlSelect,$SqlDelete,$ErrorMessage);	
				$ArtDelete = new clsFileUpload("AutPhoto", "AutPhoto", "%TEMP", "../Images/Author/", "*", "", 1000000);
	  			$ArtDelete->SetValue($AutPhoto);
				$ArtDelete->Delete();

	 	}
		
	$on=CCGetFromGet("on", "");
		$update_act=CCGetFromGet("update_act", "");
	if($on=="1")
	{
		$on="";
	} else { $on="1";}

		if($update_act)
	{

		 	$db = new clsDBConnection1();
  	 		$SqlActive = "update authors set AutActive='".$on."' where AutUsername=" . $db->ToSQL($AutUsername, ccsText) ;
    		MyActive($SqlActive);

	 }

    // Write your own code here.
// -------------------------
//End Custom Code

//Close authors_BeforeSelect @7-48021090
    return $authors_BeforeSelect;
}
//End Close authors_BeforeSelect

function MyActive($SqlDelete)	
{
	$db = new clsDBConnection1();    

		$db->query($SqlDelete);
    $db->close();
}
?>
