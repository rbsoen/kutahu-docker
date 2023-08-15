<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @22-39DC296A
include_once("./Header.php");
//End Include Page implementation

//Include Page implementation @24-D20A616D
include_once("./MenuAuthor.php");
//End Include Page implementation

class clsGridmodule { //module class @2-DFA3016C

//Variables @2-7335A9AB

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
    var $Sorter_ModTitle;
    var $Sorter_AutName;
    var $Sorter_ModModify;
    var $Sorter_ModCreated;
    var $Navigator;
//End Variables

//Class_Initialize Event @2-BFED159E
    function clsGridmodule()
    {
        global $FileName;
        $this->ComponentName = "module";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid module";
        $this->ds = new clsmoduleDataSource();
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
        $this->SorterName = CCGetParam("moduleOrder", "");
        $this->SorterDirection = CCGetParam("moduleDir", "");

        $this->imgEdit = new clsControl(ccsImageLink, "imgEdit", "imgEdit", ccsText, "", CCGetRequestParam("imgEdit", ccsGet));
        $this->lblDelete = new clsControl(ccsLabel, "lblDelete", "lblDelete", ccsText, "", CCGetRequestParam("lblDelete", ccsGet));
        $this->lblDelete->HTML = true;
        $this->ModTitle = new clsControl(ccsLabel, "ModTitle", "ModTitle", ccsText, "", CCGetRequestParam("ModTitle", ccsGet));
        $this->AutName = new clsControl(ccsLabel, "AutName", "AutName", ccsText, "", CCGetRequestParam("AutName", ccsGet));
        $this->AutUsername = new clsControl(ccsHidden, "AutUsername", "AutUsername", ccsText, "", CCGetRequestParam("AutUsername", ccsGet));
        $this->ModModify = new clsControl(ccsLabel, "ModModify", "ModModify", ccsDate, Array("dd", " ", "mmmm", " ", "yyyy"), CCGetRequestParam("ModModify", ccsGet));
        $this->ModCreated = new clsControl(ccsLabel, "ModCreated", "ModCreated", ccsDate, Array("dd", " ", "mmmm", " ", "yyyy"), CCGetRequestParam("ModCreated", ccsGet));
        $this->Sorter_ModTitle = new clsSorter($this->ComponentName, "Sorter_ModTitle", $FileName);
        $this->Sorter_AutName = new clsSorter($this->ComponentName, "Sorter_AutName", $FileName);
        $this->Sorter_ModModify = new clsSorter($this->ComponentName, "Sorter_ModModify", $FileName);
        $this->Sorter_ModCreated = new clsSorter($this->ComponentName, "Sorter_ModCreated", $FileName);
        $this->linkAdd = new clsControl(ccsLink, "linkAdd", "linkAdd", ccsText, "", CCGetRequestParam("linkAdd", ccsGet));
        $this->linkAdd->Parameters = CCGetQueryString("QueryString", Array("ModID", "del_modul", "ccsForm"));
        $this->linkAdd->Page = "ModuleMaint.php";
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
    }
//End Class_Initialize Event

//Initialize Method @2-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @2-E02B9DF1
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
                $this->imgEdit->Parameters = CCGetQueryString("QueryString", Array("del_modul", "ccsForm"));
                $this->imgEdit->Parameters = CCAddParam($this->imgEdit->Parameters, "ModID", $this->ds->f("ModID"));
                $this->imgEdit->Page = "ModuleMaint.php";
                $this->lblDelete->SetValue($this->ds->lblDelete->GetValue());
                $this->ModTitle->SetValue($this->ds->ModTitle->GetValue());
                $this->AutName->SetValue($this->ds->AutName->GetValue());
                $this->AutUsername->SetValue($this->ds->AutUsername->GetValue());
                $this->ModModify->SetValue($this->ds->ModModify->GetValue());
                $this->ModCreated->SetValue($this->ds->ModCreated->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->imgEdit->Show();
                $this->lblDelete->Show();
                $this->ModTitle->Show();
                $this->AutName->Show();
                $this->AutUsername->Show();
                $this->ModModify->Show();
                $this->ModCreated->Show();
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
        $this->Sorter_ModTitle->Show();
        $this->Sorter_AutName->Show();
        $this->Sorter_ModModify->Show();
        $this->Sorter_ModCreated->Show();
        $this->linkAdd->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @2-BDD01BF6
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->imgEdit->Errors->ToString();
        $errors .= $this->lblDelete->Errors->ToString();
        $errors .= $this->ModTitle->Errors->ToString();
        $errors .= $this->AutName->Errors->ToString();
        $errors .= $this->AutUsername->Errors->ToString();
        $errors .= $this->ModModify->Errors->ToString();
        $errors .= $this->ModCreated->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End module Class @2-FCB6E20C

class clsmoduleDataSource extends clsDBConnection1 {  //moduleDataSource Class @2-3199DDCA

//DataSource Variables @2-7966F372
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $lblDelete;
    var $ModTitle;
    var $AutName;
    var $AutUsername;
    var $ModModify;
    var $ModCreated;
//End DataSource Variables

//Class_Initialize Event @2-4E0031F3
    function clsmoduleDataSource()
    {
        $this->ErrorBlock = "Grid module";
        $this->Initialize();
        $this->lblDelete = new clsField("lblDelete", ccsText, "");
        $this->ModTitle = new clsField("ModTitle", ccsText, "");
        $this->AutName = new clsField("AutName", ccsText, "");
        $this->AutUsername = new clsField("AutUsername", ccsText, "");
        $this->ModModify = new clsField("ModModify", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        $this->ModCreated = new clsField("ModCreated", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss", "GMT"));

    }
//End Class_Initialize Event

//SetOrder Method @2-FB1A90D8
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "ModID";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_ModTitle" => array("ModTitle", ""), 
            "Sorter_AutName" => array("AutName", ""), 
            "Sorter_ModModify" => array("ModModify", ""), 
            "Sorter_ModCreated" => array("ModCreated", "")));
    }
//End SetOrder Method

//Prepare Method @2-DFF3DD87
    function Prepare()
    {
    }
//End Prepare Method

//Open Method @2-F98FC883
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*) FROM `module` INNER JOIN authors ON " .
        "`module`.AutUsername = authors.AutUsername " .
        "";
        $this->SQL = "SELECT `module`.*, AutName  " .
        "FROM `module` INNER JOIN authors ON " .
        "`module`.AutUsername = authors.AutUsername " .
        "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue($this->CountSQL, $this);
        $this->query(CCBuildSQL($this->SQL, "", $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-2F828615
    function SetValues()
    {
        $this->lblDelete->SetDBValue($this->f("ModID"));
        $this->ModTitle->SetDBValue($this->f("ModTitle"));
        $this->AutName->SetDBValue($this->f("AutName"));
        $this->AutUsername->SetDBValue($this->f("AutUsername"));
        $this->ModModify->SetDBValue(trim($this->f("ModModify")));
        $this->ModCreated->SetDBValue(trim($this->f("ModCreated")));
    }
//End SetValues Method

} //End moduleDataSource Class @2-FCB6E20C

//Include Page implementation @23-5CD56755
include_once("./Footer.php");
//End Include Page implementation

//Initialize Page @1-EFE60741
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

$FileName = "ModuleList.php";
$Redirect = "";
$TemplateFileName = "ModuleList.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-4F2A3DF3
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
$module = new clsGridmodule();
$lblError = new clsControl(ccsLabel, "lblError", "lblError", ccsText, "", CCGetRequestParam("lblError", ccsGet));
$lblError->HTML = true;
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$module->Initialize();

// Events
include("./ModuleList_events.php");
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

//Show Page @1-1D796ED3
$Header->Show("Header");
$MenuAuthor->Show("MenuAuthor");
$module->Show();
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
