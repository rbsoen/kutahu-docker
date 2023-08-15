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

class clsRecordcategory { //category Class @5-5822A817

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

//Class_Initialize Event @5-DB18288B
    function clsRecordcategory()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record category/Error";
        $this->ds = new clscategoryDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "category";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->CatTitle = new clsControl(ccsTextBox, "CatTitle", "Bab", ccsText, "", CCGetRequestParam("CatTitle", $Method));
            $this->CatTitle->Required = true;
            $this->lblCatTitle = new clsControl(ccsLabel, "lblCatTitle", "lblCatTitle", ccsText, "", CCGetRequestParam("lblCatTitle", $Method));
            $this->lblCatTitle->HTML = true;
            $this->lblLinkCategory = new clsControl(ccsLabel, "lblLinkCategory", "lblLinkCategory", ccsText, "", CCGetRequestParam("lblLinkCategory", $Method));
            $this->lblLinkCategory->HTML = true;
            $this->Cat_CatID = new clsControl(ccsHidden, "Cat_CatID", "Cat_CatID", ccsText, "", CCGetRequestParam("Cat_CatID", $Method));
            $this->CatDesc = new clsControl(ccsTextArea, "CatDesc", "Keterangan", ccsMemo, "", CCGetRequestParam("CatDesc", $Method));
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
        }
    }
//End Class_Initialize Event

//Initialize Method @5-DA880C84
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlCatID"] = CCGetFromGet("CatID", "");
    }
//End Initialize Method

//Validate Method @5-16AF6594
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->CatTitle->Validate() && $Validation);
        $Validation = ($this->Cat_CatID->Validate() && $Validation);
        $Validation = ($this->CatDesc->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @5-A13756EF
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->CatTitle->Errors->Count());
        $errors = ($errors || $this->lblCatTitle->Errors->Count());
        $errors = ($errors || $this->lblLinkCategory->Errors->Count());
        $errors = ($errors || $this->Cat_CatID->Errors->Count());
        $errors = ($errors || $this->CatDesc->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @5-8445DF55
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->ds->Prepare();
        $this->EditMode = $this->ds->AllParametersSet;
        if(!$this->FormSubmitted)
            return;

        if($this->FormSubmitted) {
            $this->PressedButton = $this->EditMode ? "Button_Update" : "Button_Insert";
            if(strlen(CCGetParam("Button_Insert", ""))) {
                $this->PressedButton = "Button_Insert";
            } else if(strlen(CCGetParam("Button_Update", ""))) {
                $this->PressedButton = "Button_Update";
            } else if(strlen(CCGetParam("Button_Delete", ""))) {
                $this->PressedButton = "Button_Delete";
            } else if(strlen(CCGetParam("Button_Cancel", ""))) {
                $this->PressedButton = "Button_Cancel";
            }
        }
        $Redirect = "Category.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button_Cancel") {
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "Button_Insert") {
                if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick") || !$this->InsertRow()) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Button_Update") {
                if(!CCGetEvent($this->Button_Update->CCSEvents, "OnClick") || !$this->UpdateRow()) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//InsertRow Method @5-E5D3057A
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->CatTitle->SetValue($this->CatTitle->GetValue());
        $this->ds->CatDesc->SetValue($this->CatDesc->GetValue());
        $this->ds->Cat_CatID->SetValue($this->Cat_CatID->GetValue());
        $this->ds->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert");
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @5-75C084F5
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->CatTitle->SetValue($this->CatTitle->GetValue());
        $this->ds->CatDesc->SetValue($this->CatDesc->GetValue());
        $this->ds->Cat_CatID->SetValue($this->Cat_CatID->GetValue());
        $this->ds->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate");
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//Show Method @5-9EFFB217
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
        if($this->EditMode)
        {
            $this->ds->open();
            if($this->Errors->Count() == 0)
            {
                if($this->ds->Errors->Count() > 0)
                {
                    echo "Error in Record category";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    $this->lblCatTitle->SetValue($this->ds->lblCatTitle->GetValue());
                    $this->lblLinkCategory->SetValue($this->ds->lblLinkCategory->GetValue());
                    if(!$this->FormSubmitted)
                    {
                        $this->CatTitle->SetValue($this->ds->CatTitle->GetValue());
                        $this->Cat_CatID->SetValue($this->ds->Cat_CatID->GetValue());
                        $this->CatDesc->SetValue($this->ds->CatDesc->GetValue());
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
            $Error .= $this->CatTitle->Errors->ToString();
            $Error .= $this->lblCatTitle->Errors->ToString();
            $Error .= $this->lblLinkCategory->Errors->ToString();
            $Error .= $this->Cat_CatID->Errors->ToString();
            $Error .= $this->CatDesc->Errors->ToString();
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
        $this->Button_Insert->Visible = !$this->EditMode && $this->InsertAllowed;
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;
        $this->Button_Delete->Visible = $this->EditMode && $this->DeleteAllowed;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->CatTitle->Show();
        $this->lblCatTitle->Show();
        $this->lblLinkCategory->Show();
        $this->Cat_CatID->Show();
        $this->CatDesc->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End category Class @5-FCB6E20C

class clscategoryDataSource extends clsDBConnection1 {  //categoryDataSource Class @5-273C90C9

//DataSource Variables @5-8DDF5AB0
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $InsertParameters;
    var $UpdateParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $CatTitle;
    var $lblCatTitle;
    var $lblLinkCategory;
    var $Cat_CatID;
    var $CatDesc;
//End DataSource Variables

//Class_Initialize Event @5-43825AEA
    function clscategoryDataSource()
    {
        $this->ErrorBlock = "Record category/Error";
        $this->Initialize();
        $this->CatTitle = new clsField("CatTitle", ccsText, "");
        $this->lblCatTitle = new clsField("lblCatTitle", ccsText, "");
        $this->lblLinkCategory = new clsField("lblLinkCategory", ccsText, "");
        $this->Cat_CatID = new clsField("Cat_CatID", ccsText, "");
        $this->CatDesc = new clsField("CatDesc", ccsMemo, "");

    }
//End Class_Initialize Event

//Prepare Method @5-C360BDFE
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlCatID", ccsInteger, "", "", $this->Parameters["urlCatID"], 0, false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
    }
//End Prepare Method

//Open Method @5-5E9811EF
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT category.*, category1.CatTitle AS category1_CatTitle  " .
        "FROM category Left JOIN category category1 ON " .
        "category.Cat_CatID = category1.CatID " .
        "WHERE category.CatID = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, "", $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @5-C2AFEAB7
    function SetValues()
    {
        $this->CatTitle->SetDBValue($this->f("CatTitle"));
        $this->lblCatTitle->SetDBValue($this->f("category1_CatTitle"));
        $this->lblLinkCategory->SetDBValue($this->f("CatID"));
        $this->Cat_CatID->SetDBValue($this->f("Cat_CatID"));
        $this->CatDesc->SetDBValue($this->f("CatDesc"));
    }
//End SetValues Method

//Insert Method @5-978C7765
    function Insert()
    {
        $this->BlockExecution = true;
        $this->cp["CatTitle"] = new clsSQLParameter("ctrlCatTitle", ccsText, "", "", $this->CatTitle->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["CatDesc"] = new clsSQLParameter("ctrlCatDesc", ccsMemo, "", "", $this->CatDesc->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["Cat_CatID"] = new clsSQLParameter("ctrlCat_CatID", ccsText, "", "", $this->Cat_CatID->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["ModID"] = new clsSQLParameter("urlModID", ccsInteger, "", "", CCGetFromGet("ModID", ""), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO category ("
             . "CatTitle, "
             . "CatDesc, "
             . "Cat_CatID, "
             . "ModID"
             . ") VALUES ("
             . $this->ToSQL($this->cp["CatTitle"]->GetDBValue(), $this->cp["CatTitle"]->DataType) . ", "
             . $this->ToSQL($this->cp["CatDesc"]->GetDBValue(), $this->cp["CatDesc"]->DataType) . ", "
             . $this->ToSQL($this->cp["Cat_CatID"]->GetDBValue(), $this->cp["Cat_CatID"]->DataType) . ", "
             . $this->ToSQL($this->cp["ModID"]->GetDBValue(), $this->cp["ModID"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        if($this->Errors->Count() == 0 && $this->BlockExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        }
        $this->close();
    }
//End Insert Method

//Update Method @5-B09A71BE
    function Update()
    {
        $this->BlockExecution = true;
        $this->cp["CatTitle"] = new clsSQLParameter("ctrlCatTitle", ccsText, "", "", $this->CatTitle->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["CatDesc"] = new clsSQLParameter("ctrlCatDesc", ccsMemo, "", "", $this->CatDesc->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["Cat_CatID"] = new clsSQLParameter("ctrlCat_CatID", ccsText, "", "", $this->Cat_CatID->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["ModID"] = new clsSQLParameter("urlModID", ccsInteger, "", "", CCGetFromGet("ModID", ""), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlCatID", ccsInteger, "", "", CCGetFromGet("CatID", ""), "", false);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("One or more parameters missing to perform the Update/Delete. The application is misconfigured.");
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "CatID", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $Where = $wp->Criterion[1];
        $this->SQL = "UPDATE category SET "
             . "CatTitle=" . $this->ToSQL($this->cp["CatTitle"]->GetDBValue(), $this->cp["CatTitle"]->DataType) . ", "
             . "CatDesc=" . $this->ToSQL($this->cp["CatDesc"]->GetDBValue(), $this->cp["CatDesc"]->DataType) . ", "
             . "Cat_CatID=" . $this->ToSQL($this->cp["Cat_CatID"]->GetDBValue(), $this->cp["Cat_CatID"]->DataType) . ", "
             . "ModID=" . $this->ToSQL($this->cp["ModID"]->GetDBValue(), $this->cp["ModID"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        if($this->Errors->Count() == 0 && $this->BlockExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        }
        $this->close();
    }
//End Update Method

} //End categoryDataSource Class @5-FCB6E20C

//Include Page implementation @3-5CD56755
include_once("./Footer.php");
//End Include Page implementation

//Initialize Page @1-38B9845B
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

$FileName = "CategoryMaint.php";
$Redirect = "";
$TemplateFileName = "CategoryMaint.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-A589B402
$DBConnection1 = new clsDBConnection1();

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
$lblTitle = new clsControl(ccsLabel, "lblTitle", "lblTitle", ccsText, "", CCGetRequestParam("lblTitle", ccsGet));
$lblTitle->HTML = true;
$category = new clsRecordcategory();
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$category->Initialize();

// Events
include("./CategoryMaint_events.php");
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

//Execute Components @1-020FBE2C
$Header->Operations();
$MenuAuthor->Operations();
$category->Operation();
$Footer->Operations();
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

//Show Page @1-BFB5262D
$Header->Show("Header");
$MenuAuthor->Show("MenuAuthor");
$category->Show();
$Footer->Show("Footer");
$lblTopLink->Show();
$lblTitle->Show();
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
