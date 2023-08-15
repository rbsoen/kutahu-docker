<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @2-39DC296A
include_once("./Header.php");
//End Include Page implementation

//Include Page implementation @4-D20A616D
include_once("./MenuAuthor.php");
//End Include Page implementation
$Pat=CCGetRequestParam("Pat", ccsGet);
$dirpath="../Document".$Pat;
class clsRecordMakeDir { //MakeDir Class @13-03F6AB04

//Variables @13-5C5E2D83

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

//Class_Initialize Event @13-89614398
    function clsRecordMakeDir()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record MakeDir/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "MakeDir";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->txtDir = new clsControl(ccsTextBox, "txtDir", "txtDir", ccsText, "", CCGetRequestParam("txtDir", $Method));
            $this->btnMakeDir = new clsButton("btnMakeDir");
        }
    }
//End Class_Initialize Event

//Validate Method @13-918A7E57
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->txtDir->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @13-854B345A
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->txtDir->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @13-80E9507F
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->EditMode = false;
        if(!$this->FormSubmitted)
            return;

        if($this->FormSubmitted) {
            $this->PressedButton = "btnMakeDir";
            if(strlen(CCGetParam("btnMakeDir", ""))) {
                $this->PressedButton = "btnMakeDir";
            }
        }
        $Redirect = "UploadDocument.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "DelFolder", "DelFile"));
        if($this->Validate()) {
            if($this->PressedButton == "btnMakeDir") {
                if(!CCGetEvent($this->btnMakeDir->CCSEvents, "OnClick")) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @13-67CAA58C
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");


        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->txtDir->Errors->ToString();
            $Error .= $this->Errors->ToString();
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

        $this->txtDir->Show();
        $this->btnMakeDir->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End MakeDir Class @13-FCB6E20C

class clsRecordNewRecord1 { //NewRecord1 Class @5-D7EDAFB1

//Variables @5-5C5E2D83

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

//Class_Initialize Event @5-E8CFF900
    function clsRecordNewRecord1()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record NewRecord1/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "NewRecord1";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "multipart/form-data";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->FileUpload1 = new clsFileUpload("FileUpload1", "Upload File", "/tmp/", "../Document/", "*.gif;*.jpg", "", 1000000);
            $this->Button_Insert = new clsButton("Button_Insert");
        }
    }
//End Class_Initialize Event

//Validate Method @5-FEF81870
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->FileUpload1->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @5-38627CEA
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->FileUpload1->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @5-9F4A53B8
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->EditMode = false;
        if(!$this->FormSubmitted)
            return;

        $this->FileUpload1->Upload();

        if($this->FormSubmitted) {
            $this->PressedButton = "Button_Insert";
            if(strlen(CCGetParam("Button_Insert", ""))) {
                $this->PressedButton = "Button_Insert";
            }
        }
        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->Validate()) {
            if($this->PressedButton == "Button_Insert") {
                if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick")) {
                    $Redirect = "";
                } else {
                    $Redirect = "UploadDocument.php". "?" . CCGetQueryString("QueryString", Array("ccsForm", "DelFolder", "DelFile"));
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @5-DDC8A1FE
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");


        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->FileUpload1->Errors->ToString();
            $Error .= $this->Errors->ToString();
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

        $this->FileUpload1->Show();
        $this->Button_Insert->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End NewRecord1 Class @5-FCB6E20C



//Include Page implementation @3-5CD56755
include_once("./Footer.php");
//End Include Page implementation

//Initialize Page @1-CF56B6E2
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

$FileName = "UploadDocument.php";
$Redirect = "";
$TemplateFileName = "UploadDocument.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-31F2455B

// Controls
$Header = new clsHeader();
$Header->BindEvents();
$Header->TemplatePath = "./";
$Header->Initialize();
$MenuAuthor = new clsMenuAuthor();
$MenuAuthor->BindEvents();
$MenuAuthor->TemplatePath = "./";
$MenuAuthor->Initialize();
$lblTopLink = new clsControl(ccsLabel, "lblTopLink", "lblTopLink", ccsText, "", CCGetRequestParam("lblTopLink", ccsGet));
$lblTopLink->HTML = true;
$lblDir = new clsControl(ccsLabel, "lblDir", "lblDir", ccsText, "", CCGetRequestParam("lblDir", ccsGet));
$lblDir->HTML = true;
$MakeDir = new clsRecordMakeDir();
$NewRecord1 = new clsRecordNewRecord1();
$lblTable = new clsControl(ccsLabel, "lblTable", "lblTable", ccsText, "", CCGetRequestParam("lblTable", ccsGet));
$lblTable->HTML = true;
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();

// Events
include("./UploadDocument_events.php");
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

//Execute Components @1-9C564088
$Header->Operations();
$MenuAuthor->Operations();
$MakeDir->Operation();
$NewRecord1->Operation();
$Footer->Operations();
//End Execute Components

//Go to destination page @1-BEB91355
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    header("Location: " . $Redirect);
    exit;
}
//End Go to destination page

//Show Page @1-418621C8
$Header->Show("Header");
$MenuAuthor->Show("MenuAuthor");
$MakeDir->Show();
$NewRecord1->Show();
$Footer->Show("Footer");
$lblTopLink->Show();
$lblDir->Show();
$lblTable->Show();
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

//Unload Page @1-AB7622EF
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
unset($Tpl);
//End Unload Page


?>
