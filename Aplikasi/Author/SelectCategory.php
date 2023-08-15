<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

class clsGridcategory { //category class @2-68400E65

//Variables @2-673082C9

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
    var $Sorter_CatTitle;
    var $Navigator;
//End Variables

//Class_Initialize Event @2-1ACAE2F8
    function clsGridcategory()
    {
        global $FileName;
        $this->ComponentName = "category";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid category";
        $this->ds = new clscategoryDataSource();
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
        $this->SorterName = CCGetParam("categoryOrder", "");
        $this->SorterDirection = CCGetParam("categoryDir", "");

        $this->lblSelect = new clsControl(ccsLabel, "lblSelect", "lblSelect", ccsText, "", CCGetRequestParam("lblSelect", ccsGet));
        $this->lblSelect->HTML = true;
        $this->CatID = new clsControl(ccsHidden, "CatID", "CatID", ccsText, "", CCGetRequestParam("CatID", ccsGet));
        $this->CatTitle = new clsControl(ccsLabel, "CatTitle", "CatTitle", ccsText, "", CCGetRequestParam("CatTitle", ccsGet));
        $this->Sorter_CatTitle = new clsSorter($this->ComponentName, "Sorter_CatTitle", $FileName);
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

//Show Method @2-E171BC08
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urlCatID"] = CCGetFromGet("CatID", "");
        $this->ds->Parameters["urlModID"] = CCGetFromGet("ModID", "");

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
                $this->CatID->SetValue($this->ds->CatID->GetValue());
                $this->CatTitle->SetValue($this->ds->CatTitle->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->lblSelect->Show();
                $this->CatID->Show();
                $this->CatTitle->Show();
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
        $this->Sorter_CatTitle->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @2-2451EA48
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->lblSelect->Errors->ToString();
        $errors .= $this->CatID->Errors->ToString();
        $errors .= $this->CatTitle->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End category Class @2-FCB6E20C

class clscategoryDataSource extends clsDBConnection1 {  //categoryDataSource Class @2-273C90C9

//DataSource Variables @2-1B09F989
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $CatID;
    var $CatTitle;
//End DataSource Variables

//Class_Initialize Event @2-14D0B2D8
    function clscategoryDataSource()
    {
        $this->ErrorBlock = "Grid category";
        $this->Initialize();
        $this->CatID = new clsField("CatID", ccsText, "");
        $this->CatTitle = new clsField("CatTitle", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @2-63270A27
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_CatTitle" => array("CatTitle", "")));
    }
//End SetOrder Method

//Prepare Method @2-06C60873
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlCatID", ccsInteger, "", "", $this->Parameters["urlCatID"], 0, false);
        $this->wp->AddParameter("2", "urlModID", ccsInteger, "", "", $this->Parameters["urlModID"], 0, false);
        $this->wp->Criterion[1] = $this->wp->Operation(opNotEqual, "CatID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "ModID", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),false);
        $this->Where = $this->wp->opAND(false, $this->wp->Criterion[1], $this->wp->Criterion[2]);
    }
//End Prepare Method

//Open Method @2-365B3486
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM category";
        $this->SQL = "SELECT *  " .
        "FROM category";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-C7B64D24
    function SetValues()
    {
        $this->CatID->SetDBValue($this->f("CatID"));
        $this->CatTitle->SetDBValue($this->f("CatTitle"));
    }
//End SetValues Method

} //End categoryDataSource Class @2-FCB6E20C

//Initialize Page @1-210483A0
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

$FileName = "SelectCategory.php";
$Redirect = "";
$TemplateFileName = "SelectCategory.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-2CA2054A
$DBConnection1 = new clsDBConnection1();

// Controls
$category = new clsGridcategory();
$category->Initialize();

// Events
include("./SelectCategory_events.php");
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

//Go to destination page @1-6F9FD7CC
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBConnection1->close();
    header("Location: " . $Redirect);
    exit;
}
//End Go to destination page

//Show Page @1-A33DAE05
$category->Show();
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
