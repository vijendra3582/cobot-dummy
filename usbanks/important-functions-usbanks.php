<?php
error_reporting(0); 
//ini_set('display_errors', 1);
require __DIR__ . '/vendor/autoload.php';

use phpseclib\Net\SFTP;

 /*============== Define Database Connection ===================*/
if ( ( $_SERVER["HTTP_HOST"] == "idsweb7" ) || ( $_SERVER["HTTP_HOST"] == "localhost" ) ||  ( $_SERVER['HTTP_HOST'] == "122.160.48.232") || ( trim($_SERVER['HTTP_HOST']) == "192.168.3.112" ) )
{ 
    define("DB_HOST", "localhost");
    define("DB_USERNAME", "root");
    define("DB_PASSWORD", "");
    define("DB_NAME", "truemark");

    $dCON = new PDO("mysql:host=" . DB_HOST . ";port=3306;dbname=" . DB_NAME,DB_USERNAME, DB_PASSWORD); 
    $dCON->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
    $dCON->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
    $dCON->exec('set NAMES  utf8 collate utf8_bin');

}
else
{
    define("DB_HOST", "localhost");
    define("DB_USERNAME", "truemark_user");
    define("DB_PASSWORD", "WQSX)K&e4gsw");
    define("DB_NAME", "truemark_iws");

    $dCON = new PDO("mysql:host=" . DB_HOST . ";port=3306;dbname=" . DB_NAME,DB_USERNAME, DB_PASSWORD);
    $dCON->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
    $dCON->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
    $dCON->exec('set NAMES  utf8 collate utf8_bin');

}
 
////=============== US BANK FILES =======================
 
function validateDate($date)
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

function converToMillion($n) {
    // first strip any formatting;
    $n = (0+str_replace(",", "", $n));

    // is this a number?
    if (!is_numeric($n)) return false;

    if ($n > 1000000) return round(($n/1000000), 2).'m';
    else return $n;

    return number_format($n);
}
 
function executeHoldingsUSBank($FILE_PATH) {
 
    global $dCON;
    $file = fopen($FILE_PATH,"r");
    $ctr = 0;
     
    while(!feof($file))
    {
        $ROW_DATA = fgetcsv($file);
        if($ctr == intval(0))
        {
            $ctr++;
        }
        else
        {
            // print_r(fgetcsv($file));
            $dateRaw = $ROW_DATA[0];
            // convert date ======
            $date_explode = explode("/", $dateRaw); 
            $date = $date_explode[2] . "-" . str_pad($date_explode[0], 2, '0', STR_PAD_LEFT) . "-" . str_pad($date_explode[1], 2, '0', STR_PAD_LEFT);

            if(validateDate($date)) {

                if(intval($ctr) == intval(1))
                {  
                    /// delete data for this current data date =============
                    $stmtDel = $dCON->prepare(" DELETE FROM tbl_fund_holdings_usbanks WHERE `date` = :date ");
                    $stmtDel->bindParam(":date", $date);
                    $stmtDel->execute();
                    $stmtDel->closeCursor();
                }

  
                $account = $ROW_DATA[1];
                $stock_ticker = $ROW_DATA[2];
                $cusip = $ROW_DATA[3];
                $security_description = $ROW_DATA[4];
                $shares = $ROW_DATA[5];
                $price = $ROW_DATA[6];
                $market_value = $ROW_DATA[7];
                $weightings = $ROW_DATA[8];
                $net_assets = $ROW_DATA[9];
                $shares_outstanding = $ROW_DATA[10];
                $creation_units = $ROW_DATA[11];
                $money_market = $ROW_DATA[12];

                $SQL  = ""; 
                $SQL .= " INSERT INTO tbl_fund_holdings_usbanks SET  "; 
                $SQL .= " date = :date,  "; 
                $SQL .= " account = :account, "; 
                $SQL .= " stock_ticker = :stock_ticker, ";  
                $SQL .= " cusip = :cusip,  "; 
                $SQL .= " security_description = :security_description, "; 
                $SQL .= " shares = :shares,  "; 
                $SQL .= " price = :price,  "; 
                $SQL .= " market_value = :market_value,  "; 
                $SQL .= " weightings = :weightings, "; 
                $SQL .= " net_assets = :net_assets,  "; 
                $SQL .= " shares_outstanding = :shares_outstanding, ";  
                $SQL .= " creation_units = :creation_units, ";  
                $SQL .= " money_market = :money_market, "; 
                $SQL .= " insert_time = :insert_time ";

                $stmt = $dCON->prepare($SQL);
                $stmt->bindParam(":date", $date);
                $stmt->bindParam(":account", $account);
                $stmt->bindParam(":stock_ticker", $stock_ticker);
                $stmt->bindParam(":cusip", $cusip);
                $stmt->bindParam(":security_description", $security_description);
                $stmt->bindParam(":shares", $shares);
                $stmt->bindParam(":price", $price);
                $stmt->bindParam(":market_value", $market_value);
                $stmt->bindParam(":weightings", $weightings);
                $stmt->bindParam(":net_assets", $net_assets);
                $stmt->bindParam(":shares_outstanding", $shares_outstanding); 
                $stmt->bindParam(":creation_units", $creation_units); 
                $stmt->bindParam(":money_market", $money_market); 
                $DT = date("Y-m-d H:i:s");
                $stmt->bindParam(":insert_time", $DT); 
                $stmt->execute();
            }

            $ctr++;  
        }
    }

    fclose($file);
}


function executeYieldsUSBank($FILE_PATH, $FUND_TICKER)
{
    global $dCON;
    $file = fopen($FILE_PATH,"r");

    $ctr = 0;
    while(!feof($file))
    {
        $ROW_DATA = fgetcsv($file);
        if(intval($ctr) == intval(0))
        { 
            $ctr++; 
        } 
        else
        { 
            //print_r(fgetcsv($file));
            $dateRaw = $ROW_DATA[2];
            // convert date ======
            $date_explode = explode("/", $dateRaw);
            $date = $date_explode[2] . "-" . str_pad($date_explode[0], 2, '0', STR_PAD_LEFT) . "-" . str_pad($date_explode[1], 2, '0', STR_PAD_LEFT);
            $distribution_yield = $ROW_DATA[0];
            $thirty_day_sec_yield = $ROW_DATA[1]; 
            if(validateDate($date)) {   
                if(intval($ctr) == intval(1))
                { 
                    /// delete data for this current data date =============
                    $stmtDel = $dCON->prepare(" DELETE FROM tbl_fund_yields_usbanks WHERE `date` = :date AND fund_ticker = :fund_ticker ");
                    $stmtDel->bindParam(":date", $date);
                    $stmtDel->bindParam(":fund_ticker", $FUND_TICKER);
                    $stmtDel->execute();
                    $stmtDel->closeCursor();
                }

                $SQL  = "";       
                $SQL .= " INSERT INTO tbl_fund_yields_usbanks SET ";
                $SQL .= " fund_ticker = :fund_ticker, ";  
                $SQL .= " distribution_yield = :distribution_yield, ";         
                $SQL .= " thirty_day_sec_yield = :thirty_day_sec_yield, ";         
                $SQL .= " date = :date, ";         
                $SQL .= " insert_time = :insert_time ";

                $stmt = $dCON->prepare($SQL);
                $stmt->bindParam(":fund_ticker", $FUND_TICKER);
                $stmt->bindParam(":distribution_yield", $distribution_yield);
                $stmt->bindParam(":thirty_day_sec_yield", $thirty_day_sec_yield);
                $stmt->bindParam(":date", $date);
                $DT = date("Y-m-d H:i:s");
                $stmt->bindParam(":insert_time", $DT);
                $stmt->execute();
                $stmt->closeCursor();
            }

            $ctr++;
        }
    }
}

function executeMonthlyPerformanceUSBank($FILE_PATH) 
{
    global $dCON;
    $file = fopen($FILE_PATH,"r");
    //echo "<pre>";
    $ctr = 0;
    while(!feof($file))
    {
        $ROW_DATA = fgetcsv($file);
        if(intval($ctr) == intval(0))
        { 
            $ctr++; 
        } 
        else
        { 
            //print_r(fgetcsv($file)); 
            $dateRaw = $ROW_DATA[11];
            // convert date ======
            $date_explode = explode("/", $dateRaw);
            $date = $date_explode[2] . "-" . str_pad($date_explode[0], 2, '0', STR_PAD_LEFT) . "-" . str_pad($date_explode[1], 2, '0', STR_PAD_LEFT);
             
            if(validateDate($date)) {   
                if(intval($ctr) == intval(1))
                {  
                    /// delete data for this current data date =============
                    $stmtDel = $dCON->prepare(" DELETE FROM tbl_fund_monthly_performance_usbanks WHERE `date` = :date ");
                    $stmtDel->bindParam(":date", $date);
                    $stmtDel->execute();
                    $stmtDel->closeCursor(); 
                }
              
                $fund_name = $ROW_DATA[0];
                $fund_ticker = $ROW_DATA[1];
                $one_month = $ROW_DATA[2];
                $three_month = $ROW_DATA[3];
                $six_month = $ROW_DATA[4];
                $ytd = $ROW_DATA[5];
                $since_inception_cumulative = $ROW_DATA[6];
                $one_year = $ROW_DATA[7];
                $three_year = $ROW_DATA[8];
                $five_year = $ROW_DATA[9];
                $since_inception_annualized = $ROW_DATA[10];
                

                $SQL  = "";
                $SQL .= " INSERT INTO tbl_fund_monthly_performance_usbanks SET ";
                $SQL .= " fund_name = :fund_name,  ";
                $SQL .= " fund_ticker = :fund_ticker,  ";
                $SQL .= " one_month = :one_month,  ";
                $SQL .= " three_month = :three_month,  ";
                $SQL .= " six_month = :six_month, "; 
                $SQL .= " ytd = :ytd,  ";
                $SQL .= " since_inception_cumulative = :since_inception_cumulative, "; 
                $SQL .= " one_year = :one_year,  ";
                $SQL .= " three_year = :three_year,  ";
                $SQL .= " five_year = :five_year,  ";
                $SQL .= " since_inception_annualized = :since_inception_annualized, "; 
                $SQL .= " `date` = :date, ";
                $SQL .= " `insert_time` = :insert_time ";
                
                $stmt = $dCON->prepare($SQL);
                $stmt->bindParam(":fund_name", $fund_name);
                $stmt->bindParam(":fund_ticker", $fund_ticker);
                $stmt->bindParam(":one_month", $one_month);
                $stmt->bindParam(":three_month", $three_month);
                $stmt->bindParam(":six_month", $six_month);
                $stmt->bindParam(":ytd", $ytd);
                $stmt->bindParam(":since_inception_cumulative", $since_inception_cumulative);
                $stmt->bindParam(":one_year", $one_year);
                $stmt->bindParam(":three_year", $three_year);
                $stmt->bindParam(":five_year", $five_year);
                $stmt->bindParam(":since_inception_annualized", $since_inception_annualized); 
                $stmt->bindParam(":date", $date);
                $DT = date("Y-m-d H:i:s");
                $stmt->bindParam(":insert_time", $DT);
                $stmt->execute();
                $stmt->closeCursor();
            }
    
            $ctr++;
        }
    }
}

function executeDailyNavUSBank($FILE_PATH)
{
    global $dCON;
    $file = fopen($FILE_PATH,"r");
    //echo "<pre>";

    $ctr = 0;
    while(!feof($file))
    {
        $ROW_DATA = fgetcsv($file);
        // echo "<pre>";
        // print_r($ROW_DATA);
        if(intval($ctr) == intval(0))
        { 
            $ctr++; 
        } 
        else
        {
            // print_r(fgetcsv($file)); 
            $dateRaw = $ROW_DATA[12];
            // convert date ======
            $date_explode = explode("/", $dateRaw);

            // print_r($date_explode);

            $date = $date_explode[2] . "-" . str_pad($date_explode[0], 2, '0', STR_PAD_LEFT) . "-" . str_pad($date_explode[1], 2, '0', STR_PAD_LEFT);

            if(validateDate($date)) {   
                if(intval($ctr) == intval(1))
                {  
                    /// delete data for this current data date =============
                    $stmtDel = $dCON->prepare(" DELETE FROM tbl_fund_daily_nav_usbanks WHERE `rate_date` = :rate_date ");
                    $stmtDel->bindParam(":rate_date", $date);
                    $stmtDel->execute();
                    $stmtDel->closeCursor(); 
                }

                $fund_name = $ROW_DATA[0];
                $fund_ticker = $ROW_DATA[1]; 
                $cusip = $ROW_DATA[2]; 
                $net_assets = $ROW_DATA[3]; 
                $shares_outstanding = $ROW_DATA[4]; 
                $nav = $ROW_DATA[5]; 
                $nav_change_dollars = $ROW_DATA[6]; 
                $nav_change_percentage = $ROW_DATA[7];
                $market_price = $ROW_DATA[8]; 
                $market_price_change_dollars = $ROW_DATA[9]; 
                $market_price_change_percentage = $ROW_DATA[10]; 
                $premium_discount = $ROW_DATA[11]; 
                $median_30_day_spread_percentage = $ROW_DATA[13];

                $SQL  = "";
                $SQL .= " INSERT INTO tbl_fund_daily_nav_usbanks SET ";
                $SQL .= " fund_name = :fund_name, "; 
                $SQL .= " fund_ticker = :fund_ticker, "; 
                $SQL .= " cusip = :cusip, "; 
                $SQL .= " net_assets = :net_assets, "; 
                $SQL .= " shares_outstanding = :shares_outstanding, "; 
                $SQL .= " nav = :nav, "; 
                $SQL .= " nav_change_dollars = :nav_change_dollars, "; 
                $SQL .= " nav_change_percentage = :nav_change_percentage, ";
                $SQL .= " market_price = :market_price, "; 
                $SQL .= " market_price_change_dollars = :market_price_change_dollars, ";
                $SQL .= " market_price_change_percentage = :market_price_change_percentage, "; 
                $SQL .= " premium_discount = :premium_discount, "; 
                $SQL .= " rate_date = :rate_date, ";
                $SQL .= " median_30_day_spread_percentage = :median_30_day_spread_percentage, ";
                $SQL .= " insert_time = :insert_time ";

                $stmt = $dCON->prepare($SQL);
                $stmt->bindParam(":fund_name", $fund_name);
                $stmt->bindParam(":fund_ticker", $fund_ticker);
                $stmt->bindParam(":cusip", $cusip);
                $stmt->bindParam(":net_assets", $net_assets);
                $stmt->bindParam(":shares_outstanding", $shares_outstanding);
                $stmt->bindParam(":nav", $nav);
                $stmt->bindParam(":nav_change_dollars", $nav_change_dollars);
                $stmt->bindParam(":nav_change_percentage", $nav_change_percentage);
                $stmt->bindParam(":market_price", $market_price);
                $stmt->bindParam(":market_price_change_dollars", $market_price_change_dollars);
                $stmt->bindParam(":market_price_change_percentage", $market_price_change_percentage);
                $stmt->bindParam(":premium_discount", $premium_discount);
                $stmt->bindParam(":median_30_day_spread_percentage", $median_30_day_spread_percentage);
                $stmt->bindParam(":rate_date", $date);
                $DT = date("Y-m-d H:i:s");
                $stmt->bindParam(":insert_time", $DT);
                $stmt->execute();
                $stmt->closeCursor();
            }

            $ctr++;
        }
    }
}

function updateFundDataUSBanks()
{
    global $dCON;
    //Open fund detail table
    //=========== FOR DAILY NAV ====================
    $stk = $dCON->prepare(" SELECT * FROM tbl_fund_data WHERE tags = 'DIFF_TABLE' AND do_not_update = '0' AND tags_cond = '1' order by fund_id ");
    $stk->execute();
    $row_stk = $stk->fetchAll();
    $stk->closeCursor();
    //echo " SELECT * FROM " . FUND_DATA_TBL . " WHERE tags = 'DIFF_TABLE' AND do_not_update = 'NO' AND tags_cond = '1' order by fund_id ";
    //print_r($row_stk);

    foreach($row_stk as $rs_stk)
    {
        $id = "";
        $fund_profile_id = "";
        $tags_cond = "";
        $tags_field = "";

        $id = stripslashes($rs_stk['id']);
        $fund_id = stripslashes($rs_stk['fund_id']);
        $tags_cond = intval($rs_stk['tags_cond']);
        $tags_field = stripslashes($rs_stk['tags_field']);
        $tags_table = stripslashes($rs_stk['tags_table']);

        //Parent id
        $stk_pid = $dCON->prepare(" SELECT fund_profile_id, fund_ticker FROM " . FUND_TBL . " WHERE fund_id = :fund_id ");
        $stk_pid->bindParam(":fund_id", $fund_id);
        $stk_pid->execute();
        $row_stk_pid = $stk_pid->fetchAll();
        $stk_pid->closeCursor();
        $parent_id = intval($row_stk_pid[0]['fund_profile_id']);
        $ticker_symbol = stripslashes($row_stk_pid[0]['fund_ticker']);

        //Get Ticker of fund id
        $ticker = stripslashes($row_stk_pid[0]['fund_ticker']);

        if($tags_table == 'daily_nav_usbanks') //From DAILY NAV
        { 
            $stmt_black = $dCON->prepare(" SELECT `$tags_field` as tfield FROM tbl_fund_daily_nav_usbanks WHERE fund_ticker = :fund_ticker ORDER BY rate_date DESC LIMIT 1 ");
            $stmt_black->bindParam(":fund_ticker",$ticker_symbol);
            $stmt_black->execute();
            $row_stmt_black = $stmt_black->fetchAll();
            $stmt_black->closeCursor();
            
            $tfield = stripslashes($row_stmt_black[0]['tfield']);
            if($tags_field == "net_assets")
            {
                if( trim($tfield) == "") 
                { 
                    $tfield = "---"; 
                }
                else
                {
                    if (floatval($tfield) > 1000000) {
                        $tfield = "$" . converToMillion($tfield);
                    } else {
                        $tfield = "$" . number_format($tfield, 2);    
                    } 
                }
            } 
        }
        

        $stmt = $dCON->prepare(" UPDATE tbl_fund_data SET data_value = :data_value WHERE id = :id AND fund_id = :fund_id ");
        $stmt->bindParam(":data_value",$tfield);
        $stmt->bindParam(":id",$id);
        $stmt->bindParam(":fund_id",$fund_id);
        $stmt->execute();
        //print_r($stmt->errorInfo());
        $stmt->closeCursor();
    } 
    
    /// FOR fund data and pricing table
    $stk = $dCON->prepare(" SELECT * FROM tbl_fund_data_and_pricing WHERE tags = 'DIFF_TABLE' AND do_not_update = '0' AND tags_cond = '1' order by fund_id ");
    $stk->execute();
    $row_stk = $stk->fetchAll();
    $stk->closeCursor();
    //echo " SELECT * FROM " . FUND_DATA_TBL . " WHERE tags = 'DIFF_TABLE' AND do_not_update = 'NO' AND tags_cond = '1' order by fund_id ";
    //print_r($row_stk);

    foreach($row_stk as $rs_stk)
    {
        $id = "";
        $fund_profile_id = "";
        $tags_cond = "";
        $tags_field = "";

        $id = stripslashes($rs_stk['id']);
        $fund_id = stripslashes($rs_stk['fund_id']);
        $tags_cond = intval($rs_stk['tags_cond']);
        $tags_field = stripslashes($rs_stk['tags_field']);
        $tags_table = stripslashes($rs_stk['tags_table']);

        //Parent id
        $stk_pid = $dCON->prepare(" SELECT fund_profile_id, fund_ticker FROM tbl_fund WHERE id = :fund_id ");
        $stk_pid->bindParam(":fund_id", $fund_id);
        $stk_pid->execute();
        $row_stk_pid = $stk_pid->fetchAll();
        $stk_pid->closeCursor();
        $parent_id = intval($row_stk_pid[0]['fund_profile_id']);
        $ticker_symbol = stripslashes($row_stk_pid[0]['fund_ticker']);

        //Get Ticker of fund id
        $ticker = stripslashes($row_stk_pid[0]['fund_ticker']);

        if($tags_table == 'daily_nav_usbanks') //From DAILY NAV
        {  
            $stmt_black = $dCON->prepare(" SELECT `$tags_field` as tfield FROM tbl_fund_daily_nav_usbanks WHERE fund_ticker = :fund_ticker ORDER BY rate_date DESC LIMIT 1 ");
            $stmt_black->bindParam(":fund_ticker",$ticker_symbol);
            $stmt_black->execute();
            $row_stmt_black = $stmt_black->fetchAll();
            $stmt_black->closeCursor();
             
            
            $tfield = stripslashes($row_stmt_black[0]['tfield']);
            if($tags_field == "net_assets")
            {
                if( trim($tfield) == "") 
                { 
                    $tfield = "---"; 
                }
                else
                {
                    if (floatval($tfield) > 1000000) {
                        $tfield = "$" . converToMillion($tfield);
                    } else {
                        $tfield = "$" . number_format($tfield, 2);    
                    } 
                }
            } 
            else if($tags_field == "nav")
            {
                if( trim($tfield) == "") 
                { 
                    $tfield = "---"; 
                }
                else
                { 
                    $tfield = "$" . number_format($tfield, 2);   
                }
            } 
            else if($tags_field == "shares_outstanding")
            {
                if( trim($tfield) == "") 
                { 
                    $tfield = "---"; 
                }
                else
                { 
                    $tfield = number_format($tfield);   
                }
            } 
            else if($tags_field == "premium_discount")
            {
                if( trim($tfield) == "") 
                { 
                    $tfield = "---"; 
                }
                else
                { 
                    $tfield = number_format($tfield, 2) . "%";   
                }
            } 
            else if($tags_field == "market_price")
            {
                if( trim($tfield) == "") 
                { 
                    $tfield = "---"; 
                }
                else
                { 
                    $tfield = "$" . number_format($tfield, 2);   
                }
            } 
            else if($tags_field == "median_30_day_spread_percentage")
            {
                if( trim($tfield) == "") 
                { 
                    $tfield = "---"; 
                }
                else
                { 
                    $tfield = number_format($tfield, 2) . "%";   
                }
            } 
            
            
        }
        
        
        //echo $tfield . "===" . $id . "===" . $fund_id . "<br/>";

        $stmt = $dCON->prepare(" UPDATE tbl_fund_data_and_pricing SET data_value = :data_value WHERE id = :id AND fund_id = :fund_id ");
        $stmt->bindParam(":data_value",$tfield);
        $stmt->bindParam(":id",$id);
        $stmt->bindParam(":fund_id",$fund_id);
        $stmt->execute();
        //print_r($stmt->errorInfo());
        $stmt->closeCursor();
    }
}


function fundasofdate()
{ 
    global $dCON; 
    
    $stmtFund = $dCON->prepare(" SELECT * FROM tbl_fund WHERE `status` = '1' ");
    $stmtFund->execute();
    $rowFund = $stmtFund->fetchAll(PDO::FETCH_OBJ);
    $stmtFund->closeCursor();
    
    foreach($rowFund as $objFund) 
    {
        $ticker_symbol = stripslashes($objFund->fund_ticker);
        $stmt_black = $dCON->prepare(" SELECT rate_date as tfield FROM tbl_fund_daily_nav_usbanks WHERE fund_ticker = :fund_ticker ORDER BY rate_date DESC LIMIT 1 ");
        $stmt_black->bindParam(":fund_ticker",$ticker_symbol);
        $stmt_black->execute();
        $row_stmt_black = $stmt_black->fetchAll();
        $stmt_black->closeCursor();
        $tfield = stripslashes($row_stmt_black[0]['tfield']);
         
        $datahead = date('m/d/Y',strtotime($tfield));
        $data_head = "Net Assets as of " . $datahead;
        
        $stmt = $dCON->prepare(" UPDATE tbl_fund_data_and_pricing SET data_head = :data_head WHERE data_type = 'NET_ASSETS' AND fund_id = :fund_id ");
        $stmt->bindParam(":data_head",$data_head);
        $stmt->bindParam(":fund_id",$objFund->id);
        $stmt->execute();
        $stmt->closeCursor();  
    }
    
    
      
} 

function executeSPUSBank($FILE_PATH) {
 
    global $dCON;
    $file = fopen($FILE_PATH,"r");
    $ctr = 0;
     
    while(!feof($file))
    {
        $ROW_DATA = fgetcsv($file);
        if($ctr == intval(0))
        {
            $ctr++;
        }
        else
        {
            // print_r(fgetcsv($file));
            $dateRaw = $ROW_DATA[2];
            // convert date ======
            $date_explode = explode("/", $dateRaw); 
            $date = $date_explode[2] . "-" . str_pad($date_explode[0], 2, '0', STR_PAD_LEFT) . "-" . str_pad($date_explode[1], 2, '0', STR_PAD_LEFT);

            if(validateDate($date)) {

                if(intval($ctr) == intval(1))
                {  
                    /// delete data for this current data date =============
                    $stmtDel = $dCON->prepare(" DELETE FROM tbl_sp_usbanks WHERE `date` = :date ");
                    $stmtDel->bindParam(":date", $date);
                    $stmtDel->execute();
                    $stmtDel->closeCursor();
                }

  
                $fund_name = $ROW_DATA[0];
                $ytd_return = $ROW_DATA[1];

                $SQL  = ""; 
                $SQL .= " INSERT INTO tbl_sp_usbanks SET  "; 
                $SQL .= " date = :date,  "; 
                $SQL .= " fund_name = :fund_name, "; 
                $SQL .= " ytd_return = :ytd_return, ";  
                $SQL .= " insert_time = :insert_time ";

                $stmt = $dCON->prepare($SQL);
                $stmt->bindParam(":date", $date);
                $stmt->bindParam(":fund_name", $fund_name);
                $stmt->bindParam(":ytd_return", $ytd_return);
                $DT = date("Y-m-d H:i:s");
                $stmt->bindParam(":insert_time", $DT); 
                $stmt->execute();
            }

            $ctr++;  
        }
    }

    fclose($file);
}

 
///////////////////

/**
$FILE_PATH_HOLDINGS = "";
$FILE_PATH_HOLDINGS = dirname(__FILE__) . DIRECTORY_SEPARATOR . "usbanks/fsb0.arro.xf00.YR_Holdings.csv";

$FILE_PATH_MONTHLY_PERFORMANCE = "";
$FILE_PATH_MONTHLY_PERFORMANCE = dirname(__FILE__) . DIRECTORY_SEPARATOR . "usbanks/fs60.arro.xf00.YR_MonthlyPerformance.csv";

$FILE_PATH_DAILY_NAV = "";
$FILE_PATH_DAILY_NAV = dirname(__FILE__) . DIRECTORY_SEPARATOR . "usbanks/fs60.arro.xf00.YR_DailyNAV.csv";

executeHoldingsUSBank($FILE_PATH_HOLDINGS);
//executeYieldsUSBank($FILE_PATH_YEILDS, "");
//executeMonthlyPerformanceUSBank($FILE_PATH_MONTHLY_PERFORMANCE);
executeDailyNavUSBank($FILE_PATH_DAILY_NAV);
updateFundDataUSBanks();
fundasofdate();

exit;
*/

 

/// download files from US BANK TO OUR SERVER ============
$sftpUBank = new SFTP('filegateway.usbank.com', 20022);
if (!$sftpUBank->login('xf004040', 'trimtabs@0123')) {
    echo 'error:';
        print_r($sftpUBank->getSFTPErrors());
    exit("Login Failed\n");
}
else {
    echo "Successfull Logged in";
}
 

if($_SERVER['HTTP_HOST'] == "idsweb7") {
    echo "<br />I'M At Local Server";
    exit;
}


//exit;

function downloadAllFilesFromUSBank()
{
    global $sftpUBank, $dCON;
    
    $stmtSettings = $dCON->prepare(" SELECT T.holdings_file_name, T.dailynav_file_name, T.monthlyperformance_file_name FROM tbl_general_setting AS T LIMIT 1 ");
    $stmtSettings->execute();
    $objSettings = $stmtSettings->fetchObject();
    $stmtSettings->closeCursor();
      
    //echo "I M HERE 1";
    $folder_our_server = "usbanks/";

    $HOLDINGS = stripslashes($objSettings->holdings_file_name); //"fsb0.trimtabs.xf00.XU_Holdings.csv";
    $YIELD = "YR_Yields.csv";
    $MONTHLY_PERFORMANCE = stripslashes($objSettings->monthlyperformance_file_name); //"fs60.trimtabs.xf00.XU_MonthlyPerformance.csv";
    $DAILY_NAV = stripslashes($objSettings->dailynav_file_name); //"fs60.trimtabs.xf00.XU_DailyNAV.csv";
    /* 
    echo $HOLDINGS . "<br/>";
    echo $MONTHLY_PERFORMANCE . "<br/>";
    echo $DAILY_NAV . "<br/>";
    
    exit;
    */

    $ab_file_name = "";
    $ab_file_name = "/Inbox/" . $HOLDINGS;
    if( $sftpUBank->file_exists($ab_file_name))
    { 
        $sftpUBank->get($ab_file_name, $folder_our_server . $HOLDINGS);
           
        //unlink($folder_our_server . $HOLDINGS); 
        executeHoldingsUSBank("usbanks/" . $HOLDINGS); 
		/// make a copy =====
        copy("usbanks/" . $HOLDINGS, "usbanks/copy/" . date("Ymdhis") . "_" . $HOLDINGS);
    }

    $ab_file_name = "";
    $ab_file_name = "/Inbox/" . $YIELD;
    if( $sftpUBank->file_exists($ab_file_name))
    { 
        $sftpUBank->get($ab_file_name, $folder_our_server . $YIELD);
         
        //unlink($folder_our_server . $YIELD); 

        executeYieldsUSBank("usbanks/" . $YIELD, ""); 
		/// make a copy =====
        copy("usbanks/" . $YIELD, "usbanks/copy/" . date("Ymdhis") . "_" . $YIELD);
    }

    $ab_file_name = "";
    $ab_file_name = "/Inbox/" . $MONTHLY_PERFORMANCE;
    if( $sftpUBank->file_exists($ab_file_name))
    { 
        $sftpUBank->get($ab_file_name, $folder_our_server . $MONTHLY_PERFORMANCE);
         
        //unlink($folder_our_server . $MONTHLY_PERFORMANCE); 

        executeMonthlyPerformanceUSBank("usbanks/" . $MONTHLY_PERFORMANCE); 
		/// make a copy =====
        copy("usbanks/" . $MONTHLY_PERFORMANCE, "usbanks/copy/" . date("Ymdhis") . "_" . $MONTHLY_PERFORMANCE);
    }

    
    $ab_file_name = "";
    $ab_file_name = "/Inbox/" . $DAILY_NAV;
    if( $sftpUBank->file_exists($ab_file_name))
    { 
		//echo "222";
        $sftpUBank->get($ab_file_name, $folder_our_server . $DAILY_NAV);
 
        //unlink($folder_our_server . $DAILY_NAV); 

        executeDailyNavUSBank("usbanks/" . $DAILY_NAV); 
		/// make a copy =====
        copy("usbanks/" . $DAILY_NAV, "usbanks/copy/" . date("Ymdhis") . "_" . $DAILY_NAV);
    }
    
    $FILE_NAME_SP = "fs60.arro.xf00.YR_DailyIndexYTD.csv";
    
    $ab_file_name = "";
    $ab_file_name = "/Inbox/" . $FILE_NAME_SP;
    if( $sftpUBank->file_exists($ab_file_name))
    { 
		//echo "222";
        $sftpUBank->get($ab_file_name, $folder_our_server . $FILE_NAME_SP);
 
        //unlink($folder_our_server . $DAILY_NAV); 

        executeSPUSBank("usbanks/" . $FILE_NAME_SP); 
		/// make a copy =====
        copy("usbanks/" . $FILE_NAME_SP, "usbanks/copy/" . date("Ymdhis") . "_" . $FILE_NAME_SP);
    }
}
 
/// copy usbanks file to our server and read data
downloadAllFilesFromUSBank();
//echo "123333";
 
//// UPDATE Fund Data AND Pricing ===============================
updateFundDataUSBanks();
fundasofdate(); 