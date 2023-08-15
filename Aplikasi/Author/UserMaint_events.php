<?php
//BindEvents Method @1-3349929A
function BindEvents()
{
    global $lblTopLink;
    global $users;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $users->Button_Insert->CCSEvents["OnClick"] = "users_Button_Insert_OnClick";
    $users->Button_Update->CCSEvents["OnClick"] = "users_Button_Update_OnClick";
}
//End BindEvents Method

//lblTopLink_BeforeShow @28-1DF6B4AB
function lblTopLink_BeforeShow()
{
    $lblTopLink_BeforeShow = true;
//End lblTopLink_BeforeShow

//Custom Code @29-89D6DE21
// -------------------------
    global $lblTopLink;
	global $lblTitle;
	$UserUsername=CCGetFromGet("UserUsername", "");

	$MyLink="<a class='CobaltDataLinkTop' href=\"UserList.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Daftar Pengguna</a>";
	if($UserUsername =="")
	{
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"UserMaint.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Tambah Pengguna</a>";
	$lblTitle->SetValue("Tambah Pengguna");
	}
	else
	{
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"UserMaint.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Ubah Pengguna</a>";
	$lblTitle->SetValue("Ubah Pengguna");
	}
		$lblTopLink->SetValue($MyLink);
    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblTopLink_BeforeShow @28-FEF64FEE
    return $lblTopLink_BeforeShow;
}
//End Close lblTopLink_BeforeShow

//users_Button_Insert_OnClick @6-CFBB3212
function users_Button_Insert_OnClick()
{
    $users_Button_Insert_OnClick = true;
//End users_Button_Insert_OnClick

//Custom Code @31-D37AD2A6
// -------------------------
    global $users;
		$db = new clsDBConnection1();
    $SQL = "select * from users where UserUsername=". $db->ToSQL($users->UserUsername->GetValue(), ccsText);
    $db->query($SQL);
 	$Result = $db->next_record();
    if($Result)
    {
        $users_Button_Insert_OnClick=false;
		$users->Errors->addError("Login ID Sudah ada.");
	}
    $db->close();
    // Write your own code here.
// -------------------------
//End Custom Code

//Close users_Button_Insert_OnClick @6-5A7682ED
    return $users_Button_Insert_OnClick;
}
//End Close users_Button_Insert_OnClick

//users_Button_Update_OnClick @7-49710DE1
function users_Button_Update_OnClick()
{
    $users_Button_Update_OnClick = true;
//End users_Button_Update_OnClick

//Custom Code @32-D37AD2A6
// -------------------------
    global $users;
		$UserUsername=CCGetFromGet("UserUsername", "");
if($users->UserUsername->GetValue()!=$UserUsername)
{
			$db = new clsDBConnection1();
    $SQL = "select * from users where UserUsername=". $db->ToSQL($users->UserUsername->GetValue(), ccsText);
    $db->query($SQL);
 	$Result = $db->next_record();
    if($Result)
    {
        $users_Button_Update_OnClick=false;
		$users->Errors->addError("Login ID Sudah ada.");
	}
    $db->close();
}
    // Write your own code here.
// -------------------------
//End Custom Code

//Close users_Button_Update_OnClick @7-F1E1AA50
    return $users_Button_Update_OnClick;
}
//End Close users_Button_Update_OnClick


?>
