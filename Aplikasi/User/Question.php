<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files
$MyData="";
//Include Page implementation @3-39DC296A
include_once("./Header.php");
//End Include Page implementation

//Include Page implementation @52-281214C7
include_once("./MyTree.php");
//End Include Page implementation

class clsGridtest { //test class @40-062E0013

//Variables @40-0B3A0FB0

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
//End Variables

//Class_Initialize Event @40-7BE2F1C2
    function clsGridtest()
    {
        global $FileName;
        $this->ComponentName = "test";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid test";
        $this->ds = new clstestDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 10;
        else if ($this->PageSize > 100)
            $this->PageSize = 100;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->TestValue = new clsControl(ccsLabel, "TestValue", "TestValue", ccsFloat, "", CCGetRequestParam("TestValue", ccsGet));
        $this->TestDateTime = new clsControl(ccsLabel, "TestDateTime", "TestDateTime", ccsDate, Array("d", " ", "mmmm", "  ", "yyyy"), CCGetRequestParam("TestDateTime", ccsGet));
    }
//End Class_Initialize Event

//Initialize Method @40-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @40-105F1FD2
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
                $this->TestValue->SetValue($this->ds->TestValue->GetValue());
                $this->TestDateTime->SetValue($this->ds->TestDateTime->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->TestValue->Show();
                $this->TestDateTime->Show();
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
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @40-15D8DB89
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->TestValue->Errors->ToString();
        $errors .= $this->TestDateTime->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End test Class @40-FCB6E20C

class clstestDataSource extends clsDBConnection1 {  //testDataSource Class @40-2C90771A

//DataSource Variables @40-B05B2686
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $TestValue;
    var $TestDateTime;
//End DataSource Variables

//Class_Initialize Event @40-599F3C49
    function clstestDataSource()
    {
        $this->ErrorBlock = "Grid test";
        $this->Initialize();
        $this->TestValue = new clsField("TestValue", ccsFloat, "");
        $this->TestDateTime = new clsField("TestDateTime", ccsDate, Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));

    }
//End Class_Initialize Event

//SetOrder Method @40-9E1383D1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @40-DFF3DD87
    function Prepare()
    {
    }
//End Prepare Method

//Open Method @40-FCEA1922
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM test";
        $this->SQL = "SELECT *  " .
        "FROM test";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @40-CB9B1689
    function SetValues()
    {
        $this->TestValue->SetDBValue(trim($this->f("TestValue")));
        $this->TestDateTime->SetDBValue(trim($this->f("TestDateTime")));
    }
//End SetValues Method

} //End testDataSource Class @40-FCB6E20C













//Include Page implementation @4-5CD56755
include_once("./Footer.php");
//End Include Page implementation

//Initialize Page @1-B309130C
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

$FileName = "Question.php";
$Redirect = "";
$TemplateFileName = "Question.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-D45813E3
$DBConnection1 = new clsDBConnection1();

// Controls
$Header = new clsHeader();
$Header->BindEvents();
$Header->TemplatePath = "./";
$Header->Initialize();
$MyTree = new clsMyTree();
$MyTree->BindEvents();
$MyTree->TemplatePath = "./";
$MyTree->Initialize();
$lblTopLink = new clsControl(ccsLabel, "lblTopLink", "lblTopLink", ccsText, "", CCGetRequestParam("lblTopLink", ccsGet));
$lblTopLink->HTML = true;
$lblQueIDArray = new clsControl(ccsLabel, "lblQueIDArray", "lblQueIDArray", ccsText, "", CCGetRequestParam("lblQueIDArray", ccsGet));
$lblQueIDArray->HTML = true;
$test = new clsGridtest();
$lblStart = new clsControl(ccsLabel, "lblStart", "lblStart", ccsText, "", CCGetRequestParam("lblStart", ccsGet));
$lblStart->HTML = true;
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$test->Initialize();

// Events
include("./Question_events.php");
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

//Execute Components @1-1C3660D4
$Header->Operations();
$MyTree->Operations();
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

//Show Page @1-D036AB30
$Header->Show("Header");
$MyTree->Show("MyTree");
$test->Show();
$Footer->Show("Footer");
$lblTopLink->Show();
$lblQueIDArray->Show();
$lblStart->Show();
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
