<?php
//Include Common Files @1-5471E0F2
define("RelativePath", ".");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

class clsRecordLogin { //Login Class @2-58926B8F

//Variables @2-5C5E2D83

    // Public variables
    var $ComponentName;
    var $HTMLFormAction;
    var $PressedButton;
    var $Errors;
    var $ErrorBlock;
    var $FormSubmitted;
    var $FormEnctype;
    var $Visible;
    var $Recordset;

    var $CCSEvents = "";
    var $CCSEventResult;

    var $InsertAllowed = false;
    var $UpdateAllowed = false;
    var $DeleteAllowed = false;
    var $ReadAllowed   = false;
    var $ds;
    var $EditMode;
    var $ValidatingControls;
    var $Controls;

    // Class variables
//End Variables

//Class_Initialize Event @2-D12B6CFF
    function clsRecordLogin()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record Login/Error";
        $this->ds = new clsLoginDataSource();
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "Login";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->rdbUserAuthor = new clsControl(ccsRadioButton, "rdbUserAuthor", "rdbUserAuthor", ccsText, "", CCGetRequestParam("rdbUserAuthor", $Method));
            $this->rdbUserAuthor->DSType = dsListOfValues;
            $this->rdbUserAuthor->Values = array(array("U", "Pengguna"), array("A", "Penulis"));
            $this->rdbUserAuthor->HTML = true;
            $this->login = new clsControl(ccsTextBox, "login", "Login ID", ccsText, "", CCGetRequestParam("login", $Method));
            $this->login->Required = true;
            $this->password = new clsControl(ccsTextBox, "password", "Password", ccsText, "", CCGetRequestParam("password", $Method));
            $this->password->Required = true;
            $this->Link1 = new clsControl(ccsLink, "Link1", "Link1", ccsText, "", CCGetRequestParam("Link1", $Method));
            $this->Link1->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
            $this->Link1->Page = "CreateUser.php";
            $this->Button_DoLogin = new clsButton("Button_DoLogin");
            if(!$this->FormSubmitted) {
                if(!is_array($this->rdbUserAuthor->Value) && !strlen($this->rdbUserAuthor->Value) && $this->rdbUserAuthor->Value !== false)
                $this->rdbUserAuthor->SetText(U);
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @2-5D060BAC
    function Initialize()
    {

        if(!$this->Visible)
            return;

    }
//End Initialize Method

//Validate Method @2-D1C12ACE
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->rdbUserAuthor->Validate() && $Validation);
        $Validation = ($this->login->Validate() && $Validation);
        $Validation = ($this->password->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-6DCDC2E7
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->rdbUserAuthor->Errors->Count());
        $errors = ($errors || $this->login->Errors->Count());
        $errors = ($errors || $this->password->Errors->Count());
        $errors = ($errors || $this->Link1->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-F3EAC95C
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->ds->Prepare();
        $this->EditMode = true;
        if(!$this->FormSubmitted)
            return;

        if($this->FormSubmitted) {
            $this->PressedButton = "Button_DoLogin";
            if(strlen(CCGetParam("Button_DoLogin", ""))) {
                $this->PressedButton = "Button_DoLogin";
            }
        }
        $Redirect = "User/HomeUser.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoLogin") {
                if(!CCGetEvent($this->Button_DoLogin->CCSEvents, "OnClick")) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @2-42D56D1D
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->rdbUserAuthor->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if($this->EditMode)
        {
            $this->ds->open();
            if($this->Errors->Count() == 0)
            {
                if($this->ds->Errors->Count() > 0)
                {
                    echo "Error in Record Login";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                    }
                }
                else
                {
                    $this->EditMode = false;
                }
            }
        }
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->rdbUserAuthor->Errors->ToString();
            $Error .= $this->login->Errors->ToString();
            $Error .= $this->password->Errors->ToString();
            $Error .= $this->Link1->Errors->ToString();
            $Error .= $this->Errors->ToString();
            $Error .= $this->ds->Errors->ToString();
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", $this->HTMLFormAction);
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->rdbUserAuthor->Show();
        $this->login->Show();
        $this->password->Show();
        $this->Link1->Show();
        $this->Button_DoLogin->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End Login Class @2-FCB6E20C

class clsLoginDataSource extends clsDBConnection1 {  //LoginDataSource Class @2-68CD7B92

//DataSource Variables @2-73702E03
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $rdbUserAuthor;
    var $login;
    var $password;
    var $Link1;
//End DataSource Variables

//Class_Initialize Event @2-68698023
    function clsLoginDataSource()
    {
        $this->ErrorBlock = "Record Login/Error";
        $this->Initialize();
        $this->rdbUserAuthor = new clsField("rdbUserAuthor", ccsText, "");
        $this->login = new clsField("login", ccsText, "");
        $this->password = new clsField("password", ccsText, "");
        $this->Link1 = new clsField("Link1", ccsText, "");

    }
//End Class_Initialize Event

//Prepare Method @2-DFF3DD87
    function Prepare()
    {
    }
//End Prepare Method

//Open Method @2-DC1AA46D
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM users";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-BAF0975B
    function SetValues()
    {
    }
//End SetValues Method

} //End LoginDataSource Class @2-FCB6E20C

//Initialize Page @1-1D39B2FF
// Variables
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";

// Events;
$CCSEvents = "";
$CCSEventResult = "";

$FileName = "Login.php";
$Redirect = "";
$TemplateFileName = "Login.html";
$BlockToParse = "main";
$PathToRoot = "./";
//End Initialize Page

//Initialize Objects @1-38E38D22
$DBConnection1 = new clsDBConnection1();

// Controls
$Login = new clsRecordLogin();
$Login->Initialize();

// Events
include("./Login_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");

if($Charset) {
    header("Content-Type: text/html; charset=" . $Charset);
}
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-05EBF99F
$Login->Operation();
//End Execute Components

//Go to destination page @1-6F9FD7CC
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBConnection1->close();
    header("Location: " . $Redirect);
    exit;
}
//End Go to destination page

//Show Page @1-316DBF23
$Login->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$generated_with = "<center><font face=\"Arial\"><small></small></font></center>";
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", $generated_with . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= $generated_with;
}
echo $main_block;
//End Show Page

//Unload Page @1-A4D34ABE
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBConnection1->close();
unset($Tpl);
//End Unload Page


?>
