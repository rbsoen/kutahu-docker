<?php
//BindEvents Method @1-788B314F
function BindEvents()
{
    global $Login;
    global $CCSEvents;
    $Login->Button_DoLogin->CCSEvents["OnClick"] = "Login_Button_DoLogin_OnClick";
    $CCSEvents["BeforeShow"] = "Page_BeforeShow";
}
//End BindEvents Method

//Login_Button_DoLogin_OnClick @3-545F569A
function Login_Button_DoLogin_OnClick()
{
    $Login_Button_DoLogin_OnClick = true;
//End Login_Button_DoLogin_OnClick

//Login @4-319E0F84
    global $Login;
	if($Login->rdbUserAuthor->GetValue()=="U")
	{
    	 $db = new clsDBConnection1();
   	 		$SQL = "SELECT * FROM users WHERE UserUsername=" . $db->ToSQL($Login->login->Value, ccsText) . " AND UserPassword=" . $db->ToSQL($Login->password->Value, ccsText) . " AND UserActive=1";
    		$db->query($SQL);
    		$Result = $db->next_record();
    		if($Result)
    		{
        		 CCSetSession("UserID", $db->f("UserUsername"));
				 CCSetSession("UserFullName", $db->f("UserFullName"));
        		 CCSetSession("UserLogin", $Login->password->Value);
				 CCSetSession("UserDate", date("d F Y"));
				 global $Redirect;
        		 $Redirect="User/HomeUser.php";
        		 $Redirect = CCGetParam("ret_link", $Redirect);
        		 $Login_Button_DoLogin_OnClick = true;
    		}
			else
			{
				$Login->Errors->addError("Login ID atau Password salah.");
        		$Login->password->SetValue("");
        		$Login_Button_DoLogin_OnClick = false;
			}
    		$db->close();
	}
	else
	{
			 $db = new clsDBConnection1();
   	 		$SQL = "SELECT * FROM authors WHERE AutUsername=" . $db->ToSQL($Login->login->Value, ccsText) . " AND AutPassword=" . $db->ToSQL($Login->password->Value, ccsText) . " AND AutActive=1";
    		$db->query($SQL);
    		$Result = $db->next_record();
    		if($Result)
    		{
        		 CCSetSession("AutID", $db->f("AutUsername"));
				 CCSetSession("AutName", $db->f("AutName"));
        		 CCSetSession("AutLogin", $Login->password->Value);
				 CCSetSession("AutLevel",$db->f("AutLevel"));
				 CCSetSession("AutDate", date("d F Y"));
				 global $Redirect;
        		 $Redirect="Author/ModuleList.php";
        		 $Redirect = CCGetParam("ret_link", $Redirect);
        		 $Login_Button_DoLogin_OnClick = true;
    		}
			else
			{
				$Login->Errors->addError("Login ID atau Password salah.");
        		$Login->password->SetValue("");
        		$Login_Button_DoLogin_OnClick = false;
			}
    		$db->close();
    		
    	
	}
//End Login

//Close Login_Button_DoLogin_OnClick @3-0EB5DCFE
    return $Login_Button_DoLogin_OnClick;
}
//End Close Login_Button_DoLogin_OnClick

//Page_BeforeShow @1-D8BD2467
function Page_BeforeShow()
{
    $Page_BeforeShow = true;
//End Page_BeforeShow

//Custom Code @10-ADD652EE
// -------------------------
    global $Login;
	$Logout=CCGetFromGet("Logout", "");
	if($Logout==1)
	{
		session_destroy();
	}
    // Write your own code here.
// -------------------------
//End Custom Code

//Close Page_BeforeShow @1-4BC230CD
    return $Page_BeforeShow;
}
//End Close Page_BeforeShow


?>
