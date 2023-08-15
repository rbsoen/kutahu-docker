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

class clsGridauthors { //authors class @7-9DACDE3F

//Variables @7-A3A378A1

    // Public variables
    var $ComponentName;
    var $Visible;
    var $Errors;
    var $ErrorBlock;
    var $ds; var $PageSize;
    var $SorterName = "";
    var $SorterDirection = "";
    var $PageNumber;

    var $CCSEvents = "";
    var $CCSEventResult;

    // Grid Controls
    var $StaticControls; var $RowControls;
    var $Sorter_AutName;
    var $Sorter_AutDept;
    var $Sorter_AutInstance;
    var $Sorter_AutEmail;
    var $Navigator;
//End Variables

//Class_Initialize Event @7-5D18812D
    function clsGridauthors()
    {
        global $FileName;
        $this->ComponentName = "authors";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid authors";
        $this->ds = new clsauthorsDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 25;
        else if ($this->PageSize > 100)
            $this->PageSize = 100;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("authorsOrder", "");
        $this->SorterDirection = CCGetParam("authorsDir", "");

        $this->ImageLink1 = new clsControl(ccsImageLink, "ImageLink1", "ImageLink1", ccsText, "", CCGetRequestParam("ImageLink1", ccsGet));
        $this->lblDelete = new clsControl(ccsLabel, "lblDelete", "lblDelete", ccsText, "", CCGetRequestParam("lblDelete", ccsGet));
        $this->lblDelete->HTML = true;
        $this->hdnAutActive = new clsControl(ccsHidden, "hdnAutActive", "hdnAutActive", ccsText, "", CCGetRequestParam("hdnAutActive", ccsGet));
        $this->AutName = new clsControl(ccsLabel, "AutName", "AutName", ccsText, "", CCGetRequestParam("AutName", ccsGet));
        $this->AutDept = new clsControl(ccsLabel, "AutDept", "AutDept", ccsText, "", CCGetRequestParam("AutDept", ccsGet));
        $this->AutInstance = new clsControl(ccsLabel, "AutInstance", "AutInstance", ccsText, "", CCGetRequestParam("AutInstance", ccsGet));
        $this->AutEmail = new clsControl(ccsLabel, "AutEmail", "AutEmail", ccsText, "", CCGetRequestParam("AutEmail", ccsGet));
        $this->lblActive = new clsControl(ccsLabel, "lblActive", "lblActive", ccsText, "", CCGetRequestParam("lblActive", ccsGet));
        $this->lblActive->HTML = true;
        $this->Sorter_AutName = new clsSorter($this->ComponentName, "Sorter_AutName", $FileName);
        $this->Sorter_AutDept = new clsSorter($this->ComponentName, "Sorter_AutDept", $FileName);
        $this->Sorter_AutInstance = new clsSorter($this->ComponentName, "Sorter_AutInstance", $FileName);
        $this->Sorter_AutEmail = new clsSorter($this->ComponentName, "Sorter_AutEmail", $FileName);
        $this->Link1 = new clsControl(ccsLink, "Link1", "Link1", ccsText, "", CCGetRequestParam("Link1", ccsGet));
        $this->Link1->Parameters = CCGetQueryString("QueryString", Array("AutUsername", "del_aut", "on", "update_act", "ccsForm"));
        $this->Link1->Page = "AuthorMaint.php";
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
    }
//End Class_Initialize Event

//Initialize Method @7-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @7-0EAFED6C
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;


        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");


        $this->ds->Prepare();
        $this->ds->Open();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        $is_next_record = $this->ds->next_record();
        if($is_next_record && $ShownRecords < $this->PageSize)
        {
            do {
                    $this->ds->SetValues();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->ImageLink1->Parameters = CCGetQueryString("QueryString", Array("del_aut", "on", "update_act", "ccsForm"));
                $this->ImageLink1->Parameters = CCAddParam($this->ImageLink1->Parameters, "AutUsername", $this->ds->f("AutUsername"));
                $this->ImageLink1->Page = "AuthorMaint.php";
                $this->lblDelete->SetValue($this->ds->lblDelete->GetValue());
                $this->hdnAutActive->SetValue($this->ds->hdnAutActive->GetValue());
                $this->AutName->SetValue($this->ds->AutName->GetValue());
                $this->AutDept->SetValue($this->ds->AutDept->GetValue());
                $this->AutInstance->SetValue($this->ds->AutInstance->GetValue());
                $this->AutEmail->SetValue($this->ds->AutEmail->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->ImageLink1->Show();
                $this->lblDelete->Show();
                $this->hdnAutActive->Show();
                $this->AutName->Show();
                $this->AutDept->Show();
                $this->AutInstance->Show();
                $this->AutEmail->Show();
                $this->lblActive->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
                $ShownRecords++;
                $is_next_record = $this->ds->next_record();
            } while ($is_next_record && $ShownRecords < $this->PageSize);
        }
        else // Show NoRecords block if no records are found
        {
            $Tpl->parse("NoRecords", false);
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->Sorter_AutName->Show();
        $this->Sorter_AutDept->Show();
        $this->Sorter_AutInstance->Show();
        $this->Sorter_AutEmail->Show();
        $this->Link1->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @7-AD58F10A
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->ImageLink1->Errors->ToString();
        $errors .= $this->lblDelete->Errors->ToString();
        $errors .= $this->hdnAutActive->Errors->ToString();
        $errors .= $this->AutName->Errors->ToString();
        $errors .= $this->AutDept->Errors->ToString();
        $errors .= $this->AutInstance->Errors->ToString();
        $errors .= $this->AutEmail->Errors->ToString();
        $errors .= $this->lblActive->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End authors Class @7-FCB6E20C

class clsauthorsDataSource extends clsDBConnection1 {  //authorsDataSource Class @7-16725FCD

//DataSource Variables @7-09566569
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $lblDelete;
    var $hdnAutActive;
    var $AutName;
    var $AutDept;
    var $AutInstance;
    var $AutEmail;
//End DataSource Variables

//Class_Initialize Event @7-AA229075
    function clsauthorsDataSource()
    {
        $this->ErrorBlock = "Grid authors";
        $this->Initialize();
        $this->lblDelete = new clsField("lblDelete", ccsText, "");
        $this->hdnAutActive = new clsField("hdnAutActive", ccsText, "");
        $this->AutName = new clsField("AutName", ccsText, "");
        $this->AutDept = new clsField("AutDept", ccsText, "");
        $this->AutInstance = new clsField("AutInstance", ccsText, "");
        $this->AutEmail = new clsField("AutEmail", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @7-797B335D
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_AutName" => array("AutName", ""), 
            "Sorter_AutDept" => array("AutDept", ""), 
            "Sorter_AutInstance" => array("AutInstance", ""), 
            "Sorter_AutEmail" => array("AutEmail", "")));
    }
//End SetOrder Method

//Prepare Method @7-DFF3DD87
    function Prepare()
    {
    }
//End Prepare Method

//Open Method @7-E7C0293F
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM authors";
        $this->SQL = "SELECT *  " .
        "FROM authors";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @7-59820738
    function SetValues()
    {
        $this->lblDelete->SetDBValue($this->f("AutUsername"));
        $this->hdnAutActive->SetDBValue($this->f("AutActive"));
        $this->AutName->SetDBValue($this->f("AutName"));
        $this->AutDept->SetDBValue($this->f("AutDept"));
        $this->AutInstance->SetDBValue($this->f("AutInstance"));
        $this->AutEmail->SetDBValue($this->f("AutEmail"));
    }
//End SetValues Method

} //End authorsDataSource Class @7-FCB6E20C

//Include Page implementation @3-5CD56755
include_once("./Footer.php");
//End Include Page implementation

//Initialize Page @1-2401CB96
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

$FileName = "AuthorList.php";
$Redirect = "";
$TemplateFileName = "AuthorList.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-6A74A539
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
$authors = new clsGridauthors();
$lblError = new clsControl(ccsLabel, "lblError", "lblError", ccsText, "", CCGetRequestParam("lblError", ccsGet));
$lblError->HTML = true;
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$authors->Initialize();

// Events
include("./AuthorList_events.php");
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

//Execute Components @1-1A7139A9
$Header->Operations();
$MenuAuthor->Operations();
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

//Show Page @1-4C09C6D9
$Header->Show("Header");
$MenuAuthor->Show("MenuAuthor");
$authors->Show();
$Footer->Show("Footer");
$lblTopLink->Show();
$lblError->Show();
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
