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

//Include Page implementation @21-281214C7
include_once("./MyTree.php");
//End Include Page implementation

class clsGridcategory { //category class @7-68400E65

//Variables @7-0B3A0FB0

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

//Class_Initialize Event @7-7FABAC06
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
            $this->PageSize = 10;
        else if ($this->PageSize > 100)
            $this->PageSize = 100;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->category1_CatTitle = new clsControl(ccsLabel, "category1_CatTitle", "category1_CatTitle", ccsText, "", CCGetRequestParam("category1_CatTitle", ccsGet));
        $this->category1_CatTitle->HTML = true;
        $this->Cat_CatID = new clsControl(ccsHidden, "Cat_CatID", "Cat_CatID", ccsText, "", CCGetRequestParam("Cat_CatID", ccsGet));
        $this->CatDesc = new clsControl(ccsLabel, "CatDesc", "CatDesc", ccsMemo, "", CCGetRequestParam("CatDesc", ccsGet));
        $this->CatDesc->HTML = true;
        $this->CatTitle = new clsControl(ccsLabel, "CatTitle", "CatTitle", ccsText, "", CCGetRequestParam("CatTitle", ccsGet));
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

//Show Method @7-3130684E
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urlCatID"] = CCGetFromGet("CatID", "");

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
                $this->category1_CatTitle->SetValue($this->ds->category1_CatTitle->GetValue());
                $this->Cat_CatID->SetValue($this->ds->Cat_CatID->GetValue());
                $this->CatDesc->SetValue($this->ds->CatDesc->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->category1_CatTitle->Show();
                $this->Cat_CatID->Show();
                $this->CatDesc->Show();
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
        $this->CatTitle->SetValue($this->ds->CatTitle->GetValue());
        $this->CatTitle->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @7-E6ACC3C8
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->category1_CatTitle->Errors->ToString();
        $errors .= $this->Cat_CatID->Errors->ToString();
        $errors .= $this->CatDesc->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End category Class @7-FCB6E20C

class clscategoryDataSource extends clsDBConnection1 {  //categoryDataSource Class @7-273C90C9

//DataSource Variables @7-5BC37191
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $CatTitle;
    var $category1_CatTitle;
    var $Cat_CatID;
    var $CatDesc;
//End DataSource Variables

//Class_Initialize Event @7-C42240ED
    function clscategoryDataSource()
    {
        $this->ErrorBlock = "Grid category";
        $this->Initialize();
        $this->CatTitle = new clsField("CatTitle", ccsText, "");
        $this->category1_CatTitle = new clsField("category1_CatTitle", ccsText, "");
        $this->Cat_CatID = new clsField("Cat_CatID", ccsText, "");
        $this->CatDesc = new clsField("CatDesc", ccsMemo, "");

    }
//End Class_Initialize Event

//SetOrder Method @7-9E1383D1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @7-73CB67B8
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlCatID", ccsInteger, "", "", $this->Parameters["urlCatID"], 0, false);
    }
//End Prepare Method

//Open Method @7-53C5723F
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*) FROM category LEFT JOIN category category1 ON " .
        "category.Cat_CatID = category1.CatID " .
        "WHERE category.CatID = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . "";
        $this->SQL = "SELECT category.*, category1.CatTitle AS category1_CatTitle  " .
        "FROM category LEFT JOIN category category1 ON " .
        "category.Cat_CatID = category1.CatID " .
        "WHERE category.CatID = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue($this->CountSQL, $this);
        $this->query(CCBuildSQL($this->SQL, "", $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @7-F5E80870
    function SetValues()
    {
        $this->CatTitle->SetDBValue($this->f("CatTitle"));
        $this->category1_CatTitle->SetDBValue($this->f("category1_CatTitle"));
        $this->Cat_CatID->SetDBValue($this->f("Cat_CatID"));
        $this->CatDesc->SetDBValue($this->f("CatDesc"));
    }
//End SetValues Method

} //End categoryDataSource Class @7-FCB6E20C

//Include Page implementation @3-5CD56755
include_once("./Footer.php");
//End Include Page implementation

//Initialize Page @1-C9ABB019
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

$FileName = "Category.php";
$Redirect = "";
$TemplateFileName = "Category.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-31D108E9
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
$category = new clsGridcategory();
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$category->Initialize();

// Events
include("./Category_events.php");
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

//Show Page @1-EB522880
$Header->Show("Header");
$MyTree->Show("MyTree");
$category->Show();
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
