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

//Include Page implementation @37-281214C7
include_once("./MyTree.php");
//End Include Page implementation



class clsGridknowledgeitem { //knowledgeitem class @5-C568B058

//Variables @5-0B3A0FB0

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

//Class_Initialize Event @5-5E3815FD
    function clsGridknowledgeitem()
    {
        global $FileName;
        $this->ComponentName = "knowledgeitem";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid knowledgeitem";
        $this->ds = new clsknowledgeitemDataSource();
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

        $this->KnowItemContent = new clsControl(ccsLabel, "KnowItemContent", "KnowItemContent", ccsText, "", CCGetRequestParam("KnowItemContent", ccsGet));
        $this->KnowItemContent->HTML = true;
        $this->lblTitle = new clsControl(ccsLabel, "lblTitle", "lblTitle", ccsText, "", CCGetRequestParam("lblTitle", ccsGet));
        $this->lblTitle->HTML = true;
        $this->KnowItemTitle = new clsControl(ccsLabel, "KnowItemTitle", "KnowItemTitle", ccsText, "", CCGetRequestParam("KnowItemTitle", ccsGet));
    }
//End Class_Initialize Event

//Initialize Method @5-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @5-E734C85A
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urlKnowItemID"] = CCGetFromGet("KnowItemID", "");

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
                $this->KnowItemContent->SetValue($this->ds->KnowItemContent->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->KnowItemContent->Show();
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
        $this->KnowItemTitle->SetValue($this->ds->KnowItemTitle->GetValue());
        $this->lblTitle->Show();
        $this->KnowItemTitle->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @5-10AF07DF
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->KnowItemContent->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End knowledgeitem Class @5-FCB6E20C

class clsknowledgeitemDataSource extends clsDBConnection1 {  //knowledgeitemDataSource Class @5-B8FDAC3F

//DataSource Variables @5-F0906DB4
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $KnowItemTitle;
    var $KnowItemContent;
//End DataSource Variables

//Class_Initialize Event @5-CE9DA6CB
    function clsknowledgeitemDataSource()
    {
        $this->ErrorBlock = "Grid knowledgeitem";
        $this->Initialize();
        $this->KnowItemTitle = new clsField("KnowItemTitle", ccsText, "");
        $this->KnowItemContent = new clsField("KnowItemContent", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @5-9E1383D1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @5-68E09E63
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlKnowItemID", ccsInteger, "", "", $this->Parameters["urlKnowItemID"], 0, false);
    }
//End Prepare Method

//Open Method @5-14F8DFC2
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*) FROM knowledgeitem " .
        "WHERE KnowItemID = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . "";
        $this->SQL = "SELECT KnowItemTitle as Title,KnowItemContent as Content  " .
        "FROM knowledgeitem " .
        "WHERE KnowItemID = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue($this->CountSQL, $this);
        $this->query(CCBuildSQL($this->SQL, "", $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @5-BA1C2CE6
    function SetValues()
    {
        $this->KnowItemTitle->SetDBValue($this->f("Title"));
        $this->KnowItemContent->SetDBValue($this->f("Content"));
    }
//End SetValues Method

} //End knowledgeitemDataSource Class @5-FCB6E20C

class clsGridglossary { //glossary class @22-6B40257B

//Variables @22-6E150288

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
    var $Sorter_GlosTitle;
    var $Navigator;
//End Variables

//Class_Initialize Event @22-B26B9D27
    function clsGridglossary()
    {
        global $FileName;
        $this->ComponentName = "glossary";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid glossary";
        $this->ds = new clsglossaryDataSource();
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
        $this->SorterName = CCGetParam("glossaryOrder", "");
        $this->SorterDirection = CCGetParam("glossaryDir", "");

        $this->GlosTitle = new clsControl(ccsLabel, "GlosTitle", "GlosTitle", ccsText, "", CCGetRequestParam("GlosTitle", ccsGet));
        $this->GlosDesc = new clsControl(ccsLabel, "GlosDesc", "GlosDesc", ccsMemo, "", CCGetRequestParam("GlosDesc", ccsGet));
        $this->Sorter_GlosTitle = new clsSorter($this->ComponentName, "Sorter_GlosTitle", $FileName);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
    }
//End Class_Initialize Event

//Initialize Method @22-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @22-74B70DD9
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urlSubKnowItemID"] = CCGetFromGet("SubKnowItemID", "");

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
                $this->GlosTitle->SetValue($this->ds->GlosTitle->GetValue());
                $this->GlosDesc->SetValue($this->ds->GlosDesc->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->GlosTitle->Show();
                $this->GlosDesc->Show();
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
        $this->Sorter_GlosTitle->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @22-F82AAF9A
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->GlosTitle->Errors->ToString();
        $errors .= $this->GlosDesc->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End glossary Class @22-FCB6E20C

class clsglossaryDataSource extends clsDBConnection1 {  //glossaryDataSource Class @22-7025B4A9

//DataSource Variables @22-712BF6F8
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $GlosTitle;
    var $GlosDesc;
//End DataSource Variables

//Class_Initialize Event @22-0583F9FB
    function clsglossaryDataSource()
    {
        $this->ErrorBlock = "Grid glossary";
        $this->Initialize();
        $this->GlosTitle = new clsField("GlosTitle", ccsText, "");
        $this->GlosDesc = new clsField("GlosDesc", ccsMemo, "");

    }
//End Class_Initialize Event

//SetOrder Method @22-41BAA3ED
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "GlosTitle";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_GlosTitle" => array("GlosTitle", "")));
    }
//End SetOrder Method

//Prepare Method @22-48F932CE
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlSubKnowItemID", ccsInteger, "", "", $this->Parameters["urlSubKnowItemID"], 0, false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "SubKnowItemID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @22-9F4B33A7
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM glossary";
        $this->SQL = "SELECT *  " .
        "FROM glossary";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @22-1E9E08FE
    function SetValues()
    {
        $this->GlosTitle->SetDBValue($this->f("GlosTitle"));
        $this->GlosDesc->SetDBValue($this->f("GlosDesc"));
    }
//End SetValues Method

} //End glossaryDataSource Class @22-FCB6E20C

class clsGridknowledgeitem1 { //knowledgeitem1 class @31-7A3651C0

//Variables @31-0B3A0FB0

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

//Class_Initialize Event @31-71C5FF3C
    function clsGridknowledgeitem1()
    {
        global $FileName;
        $this->ComponentName = "knowledgeitem1";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid knowledgeitem1";
        $this->ds = new clsknowledgeitem1DataSource();
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

        $this->KnowItemTitle = new clsControl(ccsLabel, "KnowItemTitle", "KnowItemTitle", ccsText, "", CCGetRequestParam("KnowItemTitle", ccsGet));
    }
//End Class_Initialize Event

//Initialize Method @31-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @31-4397EDC6
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urlKnowItemID"] = CCGetFromGet("KnowItemID", "");

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
                $this->KnowItemTitle->SetValue($this->ds->KnowItemTitle->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->KnowItemTitle->Show();
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

//GetErrors Method @31-75D8CC1D
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->KnowItemTitle->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End knowledgeitem1 Class @31-FCB6E20C

class clsknowledgeitem1DataSource extends clsDBConnection1 {  //knowledgeitem1DataSource Class @31-4B9CA946

//DataSource Variables @31-7BC13E7B
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $KnowItemTitle;
//End DataSource Variables

//Class_Initialize Event @31-24376352
    function clsknowledgeitem1DataSource()
    {
        $this->ErrorBlock = "Grid knowledgeitem1";
        $this->Initialize();
        $this->KnowItemTitle = new clsField("KnowItemTitle", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @31-9E1383D1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @31-3A535BC5
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlKnowItemID", ccsInteger, "", "", $this->Parameters["urlKnowItemID"], 0, false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "KnowItemID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @31-252EFB82
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM knowledgeitem";
        $this->SQL = "SELECT *  " .
        "FROM knowledgeitem";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @31-C698D3F9
    function SetValues()
    {
        $this->KnowItemTitle->SetDBValue($this->f("KnowItemTitle"));
    }
//End SetValues Method

} //End knowledgeitem1DataSource Class @31-FCB6E20C

class clsGridsubknowledgeitem { //subknowledgeitem class @28-BB616816

//Variables @28-0B3A0FB0

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

//Class_Initialize Event @28-CC6CF4C3
    function clsGridsubknowledgeitem()
    {
        global $FileName;
        $this->ComponentName = "subknowledgeitem";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid subknowledgeitem";
        $this->ds = new clssubknowledgeitemDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 100;
        else if ($this->PageSize > 100)
            $this->PageSize = 100;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->SubKnowlItemTitle = new clsControl(ccsLink, "SubKnowlItemTitle", "SubKnowlItemTitle", ccsText, "", CCGetRequestParam("SubKnowlItemTitle", ccsGet));
    }
//End Class_Initialize Event

//Initialize Method @28-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @28-2B39F91E
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urlKnowItemID"] = CCGetFromGet("KnowItemID", "");

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
                $this->SubKnowlItemTitle->SetValue($this->ds->SubKnowlItemTitle->GetValue());
                $this->SubKnowlItemTitle->Parameters = CCGetQueryString("QueryString", Array("SubKnowItemID", "ccsForm"));
                $this->SubKnowlItemTitle->Parameters = CCAddParam($this->SubKnowlItemTitle->Parameters, "SubKnowItemID", $this->ds->f("SubKnowItemID"));
                $this->SubKnowlItemTitle->Page = "KnowledgeItem.php";
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->SubKnowlItemTitle->Show();
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

//GetErrors Method @28-AF809B24
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->SubKnowlItemTitle->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End subknowledgeitem Class @28-FCB6E20C

class clssubknowledgeitemDataSource extends clsDBConnection1 {  //subknowledgeitemDataSource Class @28-AE58F599

//DataSource Variables @28-7F1F82E8
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $SubKnowlItemTitle;
//End DataSource Variables

//Class_Initialize Event @28-B8CAD8FA
    function clssubknowledgeitemDataSource()
    {
        $this->ErrorBlock = "Grid subknowledgeitem";
        $this->Initialize();
        $this->SubKnowlItemTitle = new clsField("SubKnowlItemTitle", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @28-6713BC1A
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "SubKnowItemID";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @28-3A535BC5
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlKnowItemID", ccsInteger, "", "", $this->Parameters["urlKnowItemID"], 0, false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "KnowItemID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @28-0B1FD354
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM subknowledgeitem";
        $this->SQL = "SELECT *  " .
        "FROM subknowledgeitem";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @28-0EB2B5D8
    function SetValues()
    {
        $this->SubKnowlItemTitle->SetDBValue($this->f("SubKnowlItemTitle"));
    }
//End SetValues Method

} //End subknowledgeitemDataSource Class @28-FCB6E20C





//Include Page implementation @3-5CD56755
include_once("./Footer.php");
//End Include Page implementation

//Initialize Page @1-5745C5B6
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

$FileName = "KnowledgeItem.php";
$Redirect = "";
$TemplateFileName = "KnowledgeItem.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-C285C8CE
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
$knowledgeitem = new clsGridknowledgeitem();
$glossary = new clsGridglossary();
$knowledgeitem1 = new clsGridknowledgeitem1();
$subknowledgeitem = new clsGridsubknowledgeitem();
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$knowledgeitem->Initialize();
$glossary->Initialize();
$knowledgeitem1->Initialize();
$subknowledgeitem->Initialize();

// Events
include("./KnowledgeItem_events.php");
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

//Show Page @1-CACFED75
$Header->Show("Header");
$MyTree->Show("MyTree");
$knowledgeitem->Show();
$glossary->Show();
$knowledgeitem1->Show();
$subknowledgeitem->Show();
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
