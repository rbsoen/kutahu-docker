<?php
//BindEvents Method @1-BC6AAABC
function BindEvents()
{
    global $lblTopLink;
    global $users;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $users->CCSEvents["BeforeShowRow"] = "users_BeforeShowRow";
    $users->CCSEvents["BeforeSelect"] = "users_BeforeSelect";
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
	$MyLink="<a class='CobaltDataLinkTop' href=\"UserList.php". "?" . CCGetQueryString("QueryString", Array("UserUsername","update_act","on","del_user","ccsForm"))."\">Daftar Pengguna</a>";
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
//users_BeforeShowRow @7-AECDB47D
function users_BeforeShowRow()
{
    $users_BeforeShowRow = true;
//End users_BeforeShowRow

//Custom Code @16-D37AD2A6
// -------------------------
    global $users;
	$str="";

	
	if($users->hdnUserActive->GetValue()=="1")
	{
	$str.="<a href=\"javascript:#\" onclick=\"javascript:return Active('".StrDelete($users->UserFullName->GetValue())."','UserList.php?UserUsername=".$users->lblDelete->GetValue()."&on=".$users->hdnUserActive->GetValue()."&update_act=true&". CCGetQueryString("QueryString", Array("UserUsername","update_act","on","ccsForm"))."',this)\"><img title=\"Klik untuk Tidak Aktif\" src=\"../Images/on.gif\" border=\"0\"></a>";
	}
	else
	{
	$str.="<a href=\"javascript:#\" onclick=\"javascript:return Active('".StrDelete($users->UserFullName->GetValue())."','UserList.php?UserUsername=".$users->lblDelete->GetValue()."&on=".$users->hdnUserActive->GetValue()."&update_act=true&". CCGetQueryString("QueryString", Array("UserUsername","update_act","on","ccsForm"))."',this)\"><img title=\"Klik untuk Aktif\" src=\"../Images/off.gif\" border=\"0\"></a>";

	}

	$users->lblDelete->SetValue("<a href=\"javascript:#\" onclick=\"javascript:return Delete('".StrDelete($users->UserFullName->GetValue())."','UserList.php?UserUsername=".$users->lblDelete->GetValue()."&del_user=true&". CCGetQueryString("QueryString", Array("UserUsername","update_act","on","del_user","ccsForm"))."',this)\"><img title=\"Hapus\" src=\"../Images/delete.gif\" border=\"0\"></a>");
	$users->lblActive->SetValue($str);
 
    // Write your own code here.
// -------------------------
//End Custom Code

//Close users_BeforeShowRow @7-370775E0
    return $users_BeforeShowRow;
}
//End Close users_BeforeShowRow
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
//users_BeforeSelect @7-7FC9A805
function users_BeforeSelect()
{
    $users_BeforeSelect = true;
//End users_BeforeSelect

//Custom Code @19-D37AD2A6
// -------------------------
    global $users;
 	$del_user=CCGetFromGet("del_user", "");
 	$UserUsername=CCGetFromGet("UserUsername", "");
	
	
	
		if($del_user)
		{
		$db = new clsDBConnection1();
	 	$SqlSelect="select * from test where UserUsername=". $db->ToSQL($UserUsername, ccsText);
	 	$SqlDelete="DELETE FROM users where UserUsername=" . $db->ToSQL($UserUsername, ccsText);
	 	$ErrorMessage="User tidak dapat dihapus";
		MyTrigger($SqlSelect,$SqlDelete,$ErrorMessage);	
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
  	 		$SqlActive = "update users set UserActive='".$on."' where UserUsername=" . $db->ToSQL($UserUsername, ccsText) ;
    		MyActive($SqlActive);

	 }	

	
    // Write your own code here.
// -------------------------
//End Custom Code

//Close users_BeforeSelect @7-B6D080C5
    return $users_BeforeSelect;
}
//End Close users_BeforeSelect

function MyActive($SqlDelete)	
{
	$db = new clsDBConnection1();    

		$db->query($SqlDelete);
    $db->close();
}
?>
