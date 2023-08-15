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

class clsGridmodule { //module class @4-DFA3016C

//Variables @4-7B8CE2D6

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
    var $Sorter_ModCreated;
    var $Navigator;
//End Variables

//Class_Initialize Event @4-0C620063
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

        $this->ModTitle = new clsControl(ccsLink, "ModTitle", "ModTitle", ccsText, "", CCGetRequestParam("ModTitle", ccsGet));
        $this->AutName = new clsControl(ccsLabel, "AutName", "AutName", ccsText, "", CCGetRequestParam("AutName", ccsGet));
        $this->AutName->HTML = true;
        $this->AutUsername = new clsControl(ccsHidden, "AutUsername", "AutUsername", ccsText, "", CCGetRequestParam("AutUsername", ccsGet));
        $this->ModCreated = new clsControl(ccsLabel, "ModCreated", "ModCreated", ccsDate, Array("dd", " ", "mmmm", " ", "yyyy"), CCGetRequestParam("ModCreated", ccsGet));
        $this->Sorter_ModTitle = new clsSorter($this->ComponentName, "Sorter_ModTitle", $FileName);
        $this->Sorter_AutName = new clsSorter($this->ComponentName, "Sorter_AutName", $FileName);
        $this->Sorter_ModCreated = new clsSorter($this->ComponentName, "Sorter_ModCreated", $FileName);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
    }
//End Class_Initialize Event

//Initialize Method @4-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @4-5D5978B1
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
                $this->ModTitle->SetValue($this->ds->ModTitle->GetValue());
                $this->ModTitle->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                $this->ModTitle->Parameters = CCAddParam($this->ModTitle->Parameters, "ModID", $this->ds->f("ModID"));
                $this->ModTitle->Page = "Module.php";
                $this->AutName->SetValue($this->ds->AutName->GetValue());
                $this->AutUsername->SetValue($this->ds->AutUsername->GetValue());
                $this->ModCreated->SetValue($this->ds->ModCreated->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->ModTitle->Show();
                $this->AutName->Show();
                $this->AutUsername->Show();
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
        $this->Sorter_ModCreated->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @4-81B4D631
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->ModTitle->Errors->ToString();
        $errors .= $this->AutName->Errors->ToString();
        $errors .= $this->AutUsername->Errors->ToString();
        $errors .= $this->ModCreated->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End module Class @4-FCB6E20C

class clsmoduleDataSource extends clsDBConnection1 {  //moduleDataSource Class @4-3199DDCA

//DataSource Variables @4-60741AF1
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $ModTitle;
    var $AutName;
    var $AutUsername;
    var $ModCreated;
//End DataSource Variables

//Class_Initialize Event @4-495FB263
    function clsmoduleDataSource()
    {
        $this->ErrorBlock = "Grid module";
        $this->Initialize();
        $this->ModTitle = new clsField("ModTitle", ccsText, "");
        $this->AutName = new clsField("AutName", ccsText, "");
        $this->AutUsername = new clsField("AutUsername", ccsText, "");
        $this->ModCreated = new clsField("ModCreated", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss", "GMT"));

    }
//End Class_Initialize Event

//SetOrder Method @4-18EFE0F0
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "ModID";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_ModTitle" => array("ModTitle", ""), 
            "Sorter_AutName" => array("AutName", ""), 
            "Sorter_ModCreated" => array("ModCreated", "")));
    }
//End SetOrder Method

//Prepare Method @4-DFF3DD87
    function Prepare()
    {
    }
//End Prepare Method

//Open Method @4-8C02C5AF
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM `module` INNER JOIN authors ON " .
        "`module`.AutUsername = authors.AutUsername";
        $this->SQL = "SELECT `module`.*, AutName  " .
        "FROM `module` INNER JOIN authors ON " .
        "`module`.AutUsername = authors.AutUsername";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @4-49D6C823
    function SetValues()
    {
        $this->ModTitle->SetDBValue($this->f("ModTitle"));
        $this->AutName->SetDBValue($this->f("AutName"));
        $this->AutUsername->SetDBValue($this->f("AutUsername"));
        $this->ModCreated->SetDBValue(trim($this->f("ModCreated")));
    }
//End SetValues Method

} //End moduleDataSource Class @4-FCB6E20C

//Include Page implementation @3-5CD56755
include_once("./Footer.php");
//End Include Page implementation

//Initialize Page @1-3EC19317
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

$FileName = "HomeUser.php";
$Redirect = "";
$TemplateFileName = "HomeUser.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-89D7975F
$DBConnection1 = new clsDBConnection1();

// Controls
$Header = new clsHeader();
$Header->BindEvents();
$Header->TemplatePath = "./";
$Header->Initialize();
$lblTopLink = new clsControl(ccsLabel, "lblTopLink", "lblTopLink", ccsText, "", CCGetRequestParam("lblTopLink", ccsGet));
$lblTopLink->HTML = true;
$module = new clsGridmodule();
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$module->Initialize();

// Events
include("./HomeUser_events.php");
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

//Execute Components @1-351F985C
$Header->Operations();
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

//Show Page @1-5BDDFCA4
$Header->Show("Header");
$module->Show();
$Footer->Show("Footer");
$lblTopLink->Show();
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
