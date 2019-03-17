<?php
set_time_limit(0);
ob_implicit_flush();
error_reporting(E_ERROR);

///////////////////////////////////////Additional functions/////////////////////////////////////////////////
/**
  * Get Report Action Sample
  * The GetReport operation returns the contents of a report. Reports can potentially be
  * very large (>100MB) which is why we only return one report at a time, and in a
  * streaming fashion.
  *   
  * @param MarketplaceWebService_Interface $service instance of MarketplaceWebService_Interface
  * @param mixed $request MarketplaceWebService_Model_GetReport or array of parameters
  */
  function invokeGetReport($mysqlidb,$ReportId,$taskID,MarketplaceWebService_Interface $service, $request) 
  {
       try {
              $response = $service->getReport($request);
              
                //echo ("Service Response<br>");
                //echo ("=============================================================================<br>");

                //echo("        GetReportResponse<br>");
                if ($response->isSetGetReportResult()) {
                  $getReportResult = $response->getGetReportResult(); 
                  //echo ("            GetReport");
                  
                  if ($getReportResult->isSetContentMd5()) {
                    //echo ("                ContentMd5");
                    //echo ("                " . $getReportResult->getContentMd5() . "<br>");
                  }
                }
                if ($response->isSetResponseMetadata()) { 
                    //echo("            ResponseMetadata<br>");
                    $responseMetadata = $response->getResponseMetadata();
                    if ($responseMetadata->isSetRequestId()) 
                    {
                        //echo("                RequestId<br>");
                        //echo("                    " . $responseMetadata->getRequestId() . "<br>");
                    }
                }
                
                //echo ("        Report Contents<br>");
                //echo (stream_get_contents($request->getReport()) . "<br>");
                $fileName="tempRepricerItems".time().".tmp";
                file_put_contents($fileName,stream_get_contents($request->getReport()));

     } catch (MarketplaceWebService_Exception $ex) {
         $error_msg="Caught Exception: " . $ex->getMessage() . "\n"
         ."Response Status Code: " . $ex->getStatusCode() 
         . "\nError Code: " . $ex->getErrorCode() . "\n"
         ."Error Type: " . $ex->getErrorType() . "\n"
         ."Request ID: " . $ex->getRequestId() . "\n"
         ."XML: " . $ex->getXML() . "\n";
         $mysqlidb->UpdateStatusOfTask($taskID,"Error",$error_msg."\n ReportId=[$ReportId]. [Cron-works .1.]");
         exit;
     }
     return $fileName;
 }
 
 /**
  * Get Report List Action Sample
  * returns a list of reports; by default the most recent ten reports,
  * regardless of their acknowledgement status
  *   
  * @param MarketplaceWebService_Interface $service instance of MarketplaceWebService_Interface
  * @param mixed $request MarketplaceWebService_Model_GetReportList or array of parameters
  */
  function invokeGetReportRequestList($mysqlidb,$taskID,MarketplaceWebService_Interface $service, $request) 
  {
      try {
              $response = $service->getReportRequestList($request);
              
                //echo ("Service Response\n");
                //echo ("=============================================================================\n");

                //echo("        GetReportRequestListResponse\n");
                if ($response->isSetGetReportRequestListResult()) { 
                    //echo("            GetReportRequestListResult\n");
                    $getReportRequestListResult = $response->getGetReportRequestListResult();
                    if ($getReportRequestListResult->isSetNextToken()) 
                    {
                        //echo("                NextToken\n");
                        //echo("                    " . $getReportRequestListResult->getNextToken() . "\n");
                    }
                    if ($getReportRequestListResult->isSetHasNext()) 
                    {
                        //echo("                HasNext\n");
                        //echo("                    " . $getReportRequestListResult->getHasNext() . "\n");
                    }
                    $reportRequestInfoList = $getReportRequestListResult->getReportRequestInfoList();
                    foreach ($reportRequestInfoList as $reportRequestInfo) {
                        //echo("                ReportRequestInfo\n");
                    if ($reportRequestInfo->isSetReportRequestId()) 
                          {
                              //echo("                    ReportRequestId\n");
                              //echo("                        " . $reportRequestInfo->getReportRequestId() . "\n");
                          }
                          if ($reportRequestInfo->isSetReportType()) 
                          {
                              //echo("                    ReportType\n");
                              //echo("                        " . $reportRequestInfo->getReportType() . "\n");
                          }
                          if ($reportRequestInfo->isSetStartDate()) 
                          {
                              //echo("                    StartDate\n");
                              //echo("                        " . $reportRequestInfo->getStartDate()->format(DATE_FORMAT) . "\n");
                          }
                          if ($reportRequestInfo->isSetEndDate()) 
                          {
                              //echo("                    EndDate\n");
                              //echo("                        " . $reportRequestInfo->getEndDate()->format(DATE_FORMAT) . "\n");
                          }
                          if ($reportRequestInfo->isSetSubmittedDate()) 
                          {
                              //echo("                    SubmittedDate\n");
                              //echo("                        " . $reportRequestInfo->getSubmittedDate()->format(DATE_FORMAT) . "\n");
                          }
                          if ($reportRequestInfo->isSetSubmittedDate()) 
                          {
                              //echo("                    SubmittedDate\n");
                              //echo("                        " . $reportRequestInfo->getSubmittedDate()->format(DATE_FORMAT) . "\n");
                          }
                          if ($reportRequestInfo->isSetReportProcessingStatus()) 
                          {
                              //echo("                    ReportProcessingStatus\n");
                              //echo("                        " . $reportRequestInfo->getReportProcessingStatus() . "\n");
                              $reportStatus=$reportRequestInfo->getReportProcessingStatus();
                          }
                          if ($reportRequestInfo->isSetGeneratedReportId()) 
                          {
                              $reportStatus=$reportStatus."[".$reportRequestInfo->getGeneratedReportId()."]";
                          }
                    }
                } 
                if ($response->isSetResponseMetadata()) { 
                    //echo("            ResponseMetadata\n");
                    $responseMetadata = $response->getResponseMetadata();
                    if ($responseMetadata->isSetRequestId()) 
                    {
                        //echo("                RequestId\n");
                        //echo("                    " . $responseMetadata->getRequestId() . "\n");
                    }
                } 

     } catch (MarketplaceWebService_Exception $ex) {
         $error_msg="Caught Exception: " . $ex->getMessage() . "\n"
         ."Response Status Code: " . $ex->getStatusCode() 
         . "\nError Code: " . $ex->getErrorCode() . "\n"
         ."Error Type: " . $ex->getErrorType() . "\n"
         ."Request ID: " . $ex->getRequestId() . "\n"
         ."XML: " . $ex->getXML() . "\n";
         $mysqlidb->UpdateStatusOfTask($taskID,"Error","invokeGetReportRequestList\n\n".$error_msg." [Cron-works .1.]");
         exit;
     }
     return $reportStatus;
 }
 
 /**
  * Get Report List Action Sample
  * returns a list of reports; by default the most recent ten reports,
  * regardless of their acknowledgement status
  *   
  * @param MarketplaceWebService_Interface $service instance of MarketplaceWebService_Interface
  * @param mixed $request MarketplaceWebService_Model_GetReportList or array of parameters
  */
  function invokeRequestReport($mysqlidb,$taskID,MarketplaceWebService_Interface $service, $request) 
  {
      try {
              $response = $service->requestReport($request);
              
                //echo ("Service Response<br>");
                //echo ("=============================================================================<br>");

                //echo("        RequestReportResponse<br>");
                if ($response->isSetRequestReportResult()) { 
                    //echo("            RequestReportResult<br>");
                    $requestReportResult = $response->getRequestReportResult();
                    
                    if ($requestReportResult->isSetReportRequestInfo()) {
                        
                        $reportRequestInfo = $requestReportResult->getReportRequestInfo();
                          //echo("                ReportRequestInfo<br>");
                          if ($reportRequestInfo->isSetReportRequestId()) 
                          {
                              //echo("                    ReportRequestId<br>");
                              //echo("                        " . $reportRequestInfo->getReportRequestId() . "<br>");
                              $ReportRequestId=$reportRequestInfo->getReportRequestId();
                              
                          }else{
								//here if we don't got the $reportRequestInfo->getReportRequestId()
								$mysqlidb->UpdateStatusOfTask($taskID,"Error","[Cron-works .1.] Didn't got the ReportRequestId, exit!");
								die("Didn't got the ReportRequestId, exit!");
							   }
                          if ($reportRequestInfo->isSetReportType()) 
                          {
                              //echo("                    ReportType<br>");
                              //echo("                        " . $reportRequestInfo->getReportType() . "<br>");
                          }
                          if ($reportRequestInfo->isSetStartDate()) 
                          {
                              //echo("                    StartDate<br>");
                              //echo("                        " . $reportRequestInfo->getStartDate()->format(DATE_FORMAT) . "<br>");
                          }
                          if ($reportRequestInfo->isSetEndDate()) 
                          {
                              //echo("                    EndDate<br>");
                              //echo("                        " . $reportRequestInfo->getEndDate()->format(DATE_FORMAT) . "<br>");
                          }
                          if ($reportRequestInfo->isSetSubmittedDate()) 
                          {
                              //echo("                    SubmittedDate<br>");
                              //echo("                        " . $reportRequestInfo->getSubmittedDate()->format(DATE_FORMAT) . "<br>");
                          }
                          if ($reportRequestInfo->isSetReportProcessingStatus()) 
                          {
                              //echo("                    ReportProcessingStatus<br>");
                              //echo("                        " . $reportRequestInfo->getReportProcessingStatus() . "<br>");
                          }
                      }
                } 
                if ($response->isSetResponseMetadata()) { 
                    //echo("            ResponseMetadata<br>");
                    $responseMetadata = $response->getResponseMetadata();
                    if ($responseMetadata->isSetRequestId()) 
                    {
                        //echo("                RequestId<br>");
                        //echo("                    " . $responseMetadata->getRequestId() . "<br>");
                    }
                } 

     } catch (MarketplaceWebService_Exception $ex) {
         $error_msg="Caught Exception: " . $ex->getMessage() . "\n"
         ."Response Status Code: " . $ex->getStatusCode() 
         . "\nError Code: " . $ex->getErrorCode() . "\n"
         ."Error Type: " . $ex->getErrorType() . "\n"
         ."Request ID: " . $ex->getRequestId() . "\n"
         ."XML: " . $ex->getXML() . "\n";
         $mysqlidb->UpdateStatusOfTask($taskID,"Error",$error_msg." [Cron-works .1.]");
         exit;
     }
     return $ReportRequestId;
 }


   function invokeGetReportList($mysqlidb,$taskID,MarketplaceWebService_Interface $service, $request) 
  {
	  $reportID="-1";
      try {
              $response = $service->getReportList($request);
              
                //echo ("Service Response\n");
                //echo ("=============================================================================\n");

                //echo("        GetReportListResponse\n");
                if ($response->isSetGetReportListResult()) { 
                    //echo("            GetReportListResult\n");
                    $getReportListResult = $response->getGetReportListResult();
                    if ($getReportListResult->isSetNextToken()) 
                    {
                        //echo("                NextToken\n");
                        //echo("                    " . $getReportListResult->getNextToken() . "\n");
                    }
                    if ($getReportListResult->isSetHasNext()) 
                    {
                        //echo("                HasNext\n");
                        //echo("                    " . $getReportListResult->getHasNext() . "\n");
                    }
                    $reportInfoList = $getReportListResult->getReportInfoList();
                    foreach ($reportInfoList as $reportInfo) {
                        //echo("                ReportInfo\n");
                        if ($reportInfo->isSetReportId()) 
                        {
                            //echo("                    ReportId\n");
                            //echo("                        " . $reportInfo->getReportId() . "\n");
                            $reportID=$reportInfo->getReportId();
                        }
                        if ($reportInfo->isSetReportType()) 
                        {
                            //echo("                    ReportType\n");
                            //echo("                        " . $reportInfo->getReportType() . "\n");
                        }
                        if ($reportInfo->isSetReportRequestId()) 
                        {
                            //echo("                    ReportRequestId\n");
                            //echo("                        " . $reportInfo->getReportRequestId() . "\n");
                        }
                        if ($reportInfo->isSetAvailableDate()) 
                        {
                            //echo("                    AvailableDate\n");
                            //echo("                        " . $reportInfo->getAvailableDate()->format(DATE_FORMAT) . "\n");
                        }
                        if ($reportInfo->isSetAcknowledged()) 
                        {
                            //echo("                    Acknowledged\n");
                            //echo("                        " . $reportInfo->getAcknowledged() . "\n");
                        }
                        if ($reportInfo->isSetAcknowledgedDate()) 
                        {
                            //echo("                    AcknowledgedDate\n");
                            //echo("                        " . $reportInfo->getAcknowledgedDate()->format(DATE_FORMAT) . "\n");
                        }
                    }
                } 
                if ($response->isSetResponseMetadata()) { 
                    //echo("            ResponseMetadata\n");
                    $responseMetadata = $response->getResponseMetadata();
                    if ($responseMetadata->isSetRequestId()) 
                    {
                        //echo("                RequestId\n");
                        //echo("                    " . $responseMetadata->getRequestId() . "\n");
                    }
                }
     } catch (MarketplaceWebService_Exception $ex) {
         $error_msg="Caught Exception: " . $ex->getMessage() . "\n"
         ."Response Status Code: " . $ex->getStatusCode() 
         . "\nError Code: " . $ex->getErrorCode() . "\n"
         ."Error Type: " . $ex->getErrorType() . "\n"
         ."Request ID: " . $ex->getRequestId() . "\n"
         ."XML: " . $ex->getXML() . "\n";
         $mysqlidb->UpdateStatusOfTask($taskID,"Error, when \"get report list request\" :",$error_msg."\n");
         exit;
     }
     return $reportID;
 }
///////////////////////////////////////End of Additional functions/////////////////////////////////////////////////

/************************************************************************
* Uncomment to configure the client instance. Configuration settings
* are:
*
* - MWS endpoint URL
* - Proxy host and port.
* - MaxErrorRetry.
***********************************************************************/

$filesrc=file_get_contents("settings.ini");
if(preg_match("{db_name=\"(.*?)\";(?:\s+)?host=\"(.*?)\";(?:\s+)?login=\"(.*?)\";(?:\s+)?pass=\"(.*?)\";}si",$filesrc,$arrparams))
 {
	 $dbname=$arrparams[1];
	 $host=$arrparams[2];
	 $login=$arrparams[3];
	 $pass=$arrparams[4];
	 if(preg_match("{path=\"(.*?)\";}si",$filesrc,$arrparams1)){
		$path=$arrparams1[1];
	 }else{
		 die("path not found!");
	 }
	 
	 if(preg_match("{userID=\"(.*?)\";}si",$filesrc,$arrparams2)){
		$userID=$arrparams2[1];
	 }else{
		 die("userID not found!");
	 }
 }else{
	    echo "Can't getting parameters from file. exit. 1.";
	    exit;
	  }

chdir("$path/amazon_files/");

include_once '../settings.php';
include_once '../class.db.php';
include_once '../curl_funcs.php';

include_once ('Model1/Model.php');
include_once ('Model1/Client.php');
include_once ('Model1/ContentType.php');
include_once ('Model1/Error.php');
include_once ('Model1/ErrorResponse.php');
include_once ('Model1/Exception.php');
include_once ('Model1/IdList.php');
include_once ('Model1/Interface.php');
include_once ('Model1/ReportInfo.php');
include_once ('Model1/ReportRequestInfo.php');
include_once ('Model1/ReportSchedule.php');
include_once ('Model1/RequestReportRequest.php');
include_once ('Model1/RequestReportResponse.php');
include_once ('Model1/RequestReportResult.php');
include_once ('Model1/RequestType.php');
include_once ('Model1/ResponseMetadata.php');
include_once ('Model1/StatusList.php');
include_once ('Model1/SubmitFeedResult.php');
include_once ('Model1/TypeList.php');
include_once ('Model1/SubmitFeedRequest.php');
include_once ('Model1/SubmitFeedResponse.php');
include_once ('Model1/FeedSubmissionInfo.php');
include_once ('Model1/GetReportRequest.php');
include_once ('Model1/GetReportRequest.php');
include_once ('Model1/GetReportResponse.php');
include_once ('Model1/GetReportResult.php');
include_once ('Model1/GetReportRequestListRequest.php');
include_once ('Model1/GetReportRequestListResult.php');
include_once ('Model1/GetReportRequestListResponse.php');

///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
/////very important thing//////////////////////////////////////
////get seller name from command line parameters
///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
$options = getopt("a:");
$sellerName=$options['a'];
///////////////////////////////////////////////////////////////
////////////////check if script working/////////////////////////////////
$src=shell_exec("ps -Af | egrep .php | egrep $userID");
$countOfProcess=0;
$CurDate=date("F j, Y, g:i a");
if(preg_match_all("{CronRepricer\.php -a ($sellerName)}si",$src,$matches,PREG_SET_ORDER)){
	foreach($matches as $match){
		$countOfProcess++;
	}
	if($countOfProcess>2){
			//echo "$CurDate, stoped CronRepricer, detected more than 1 process, exit";
			//mail("yurik205@ya.ru","CronRepricer","$CurDate, stoped CronRepricer, detected more than 1 process, exit");
			exit;
		}else{
			//echo "$CurDate, CronRepricer checks and it's no working, continue of repricer works";
			//mail("yurik205@ya.ru","CronRepricer","$CurDate, CronRepricer checks and it's no working, continue of repricer works");
		}
}else{
	//echo "$CurDate, not found CronRepricer.php, start the repricer works \n[$sellerName]\n $src \n";
	//mail("yurik205@ya.ru","CronRepricer","$CurDate, not found CronRepricer, start the repricer works");
}
///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
//connect to database
$mysqlidb = new mysqli_mysql(DBHOST, DBUSER, DBPASS, DBNAME, DBPORT);
$mysqlidb->set_charset("utf8");

//defaultPrices
$defaultPrices=0;

///////////////////////////////////////ADD NEW TASK INTO TASKS LISTS////////////////////////////////////////////////////////
		//first,
		//check if the task in the progress now
		if(
			($mysqlidb->checkTheTask($sellerName,"Amazon","Get all items for the Repricer, seller=$sellerName.")=="In Progress")
			OR
			($mysqlidb->checkTheTask($sellerName,"Amazon","Get all prices for the Repricer, seller=$sellerName.")=="In Progress")
			OR
			($mysqlidb->checkTheTask($sellerName,"Amazon","Change all prices for the Repricer, seller=$sellerName.")=="In Progress")
			OR
			($mysqlidb->checkTheTask($sellerName,"Amazon","Change one price for the Repricer, seller=$sellerName.")=="In Progress")
			){
			die("seller = $sellerName , platform = <font color=\"purple\">Amazon</font>, <font color=\"purple\">some task for the Repricer</font>, currently <font color=\"red\">in progress</font>, please wait.");
		}
		
		$taskID=$mysqlidb->makeNewTask($sellerName,"Amazon","Get all items for the Repricer, seller=$sellerName.","[Cron-works .1.], no more details");
///////////////////////////////////////END OF ADD NEW TASK INTO TASKS LISTS/////////////////////////////////////////////////

		////get necessary IDs 
		$query="SELECT * FROM `amazon_sellers` WHERE sellerName=\"".$mysqlidb->real_escape_string($sellerName)."\"";
		$result=$mysqlidb->query($query);
		if(!$result)
		{
			echo "error in sql query!";
			$mysqlidb->UpdateStatusOfTask($taskID,"Error","error in sql query! [Cron-works .1.]");
			exit;
		}
		if(mysqli_num_rows($result)>0)
		 {
			while($row=$result->fetch_assoc())
			 {
				 $merchantID=$row['merchantID'];
				 $marketplaceID=$row['marketplaceID'];
				 $accessKey=$row['accessKey'];
				 $secretKey=$row['secretKey'];
				 $RepricerFloor=$row['RepricerFloor'];
				 $minusIfEqual=$row['minusIfEqual'];
				 if($row['countryToSell']=="USA"){
						$GLOBALS['serviceUrl'] = "https://mws.amazonservices.com";
						$GLOBALS['serviceUrlProduct'] = "https://mws.amazonservices.com/Products/2011-10-01";
					}
				if($row['countryToSell']=="UK"){
						$GLOBALS['serviceUrl'] = "https://mws.amazonservices.co.uk";
						$GLOBALS['serviceUrlProduct'] = "https://mws-eu.amazonservices.com/Products/2011-10-01";
					}
				if($row['countryToSell']=="DE"){
						$GLOBALS['serviceUrl'] = "https://mws.amazonservices.de";
						$GLOBALS['serviceUrlProduct'] = "https://mws-eu.amazonservices.com/Products/2011-10-01";
					}
				if($row['countryToSell']=="FR"){
						$GLOBALS['serviceUrl'] = "https://mws.amazonservices.fr";
						$GLOBALS['serviceUrlProduct'] = "https://mws-eu.amazonservices.com/Products/2011-10-01";
					}
				if($row['countryToSell']=="JP"){
						$GLOBALS['serviceUrl'] = "https://mws.amazonservices.jp";
						$GLOBALS['serviceUrlProduct'] = "https://mws.amazonservices.jp/Products/2011-10-01";
					}
				if($row['countryToSell']=="CN"){
						$GLOBALS['serviceUrl'] = "https://mws.amazonservices.com.cn";
						$GLOBALS['serviceUrlProduct'] = "https://mws.amazonservices.com.cn/Products/2011-10-01";
					}
				if($row['countryToSell']=="CA"){
						$GLOBALS['serviceUrl'] = "https://mws.amazonservices.ca";
						$GLOBALS['serviceUrlProduct'] = "https://mws.amazonservices.ca/Products/2011-10-01";
					}
				if($row['countryToSell']=="MX"){
						$GLOBALS['serviceUrl'] = "https://mws.amazonservices.com.mx";
						$GLOBALS['serviceUrlProduct'] = "https://mws.amazonservices.com.mx/Products/2011-10-01";
					}
			 }
		 }else{
				echo "Please select a seller!";
				$mysqlidb->UpdateStatusOfTask($taskID,"Error","Please select a seller! check the script and probably a DB. [Cron-works .1.]");
				exit;
			   }
		 
$config = array (
  'ServiceURL' => $GLOBALS['serviceUrl'],
  'ProxyHost' => null,
  'ProxyPort' => -1,
  'MaxErrorRetry' => 3,
);

/************************************************************************
 * Instantiate Implementation of MarketplaceWebService
 * 
 * AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY constants 
 * are defined in the .config.inc.php located in the same 
 * directory as this sample
 ***********************************************************************/
 $service = new MarketplaceWebService_Client(
     $accessKey, 
     $secretKey, 
     $config,
     "TheInterface",
     "1.0.0");

 $parameters = array (
   'Marketplace' => $marketplaceID,
   'Merchant' => $merchantID,
   'ReportType' => '_GET_FLAT_FILE_OPEN_LISTINGS_DATA_',
 );
// 
 $request = new MarketplaceWebService_Model_RequestReportRequest($parameters);
 
 $request = new MarketplaceWebService_Model_RequestReportRequest();
 $request->setMarketplace($marketplaceID);
 $request->setMerchant($merchantID);
 $request->setReportType('_GET_FLAT_FILE_OPEN_LISTINGS_DATA_');
 
 $ReportRequestId=invokeRequestReport($mysqlidb,$taskID,$service, $request);
//echo "$ReportRequestId<br>";

///////////////////get report request list
$idList=new MarketplaceWebService_Model_IdList();
 $parameters = array (
   'Marketplace' => $marketplaceID,
   'Merchant' => $merchantID,
   'ReportRequestIdList' => array ('Id' => "$ReportRequestId"),
 );
 $request1 = new MarketplaceWebService_Model_GetReportRequestListRequest($parameters);
 
$request1 = new MarketplaceWebService_Model_GetReportRequestListRequest();
$request1->setMarketplace($marketplaceID);
$request1->setMerchant($merchantID);
$request1->setReportRequestIdList($idList->withId($ReportRequestId));
 
/////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
$reportStatus=invokeGetReportRequestList($mysqlidb,$taskID,$service,$request1);
//echo $reportStatus." wait 50 seconds<br>";
sleep(50);
if((stristr($reportStatus,"DONE")===false)AND(stristr($reportStatus,"SUBMITTED")===false)){
	$mysqlidb->UpdateStatusOfTask($taskID,"Error","error when get list of request report! check script... [Cron-works .1.]");
	die("error when get list of request report!");
}

if(stristr($reportStatus,"SUBMITTED")!==false)
	{
		$count=0;
		while(stristr($reportStatus,"DONE")===false){
			$reportStatus=invokeGetReportRequestList($mysqlidb,$taskID,$service, $request1);
			if(stristr($reportStatus,"DONE")===false){
						//echo $reportStatus." wait 50 seconds<br>";
						sleep(50);
					}else{
								//echo $reportStatus."status=DONE, break for now<br>";
								break;
							}
			$count++;
			if($count>600){
							$mysqlidb->UpdateStatusOfTask($taskID,"Error","time while wait a long, exit! probably Amazon out of service or something with internet connection or ..., etc. [Cron-works .1.]");
							die("time > 65 minutes, exit!");
						  }
		}
	}
	//echo "next";
if(preg_match("{\[([0-9]+)\]}si",$reportStatus,$arrVal)){
		//echo "count for while=".$count."<br>";
		$ReportId=$arrVal[1];
	}else{
			$mysqlidb->UpdateStatusOfTask($taskID,"Error","error when preg_ for reportID! check script... [Cron-works .1.]");
			die("error when preg for reportID!");
		 }
 
/////////////////////get report
	 $parameters = array (
	   'Marketplace' => $marketplaceID,
	   'Merchant' => $merchantID,
	   'Report' => @fopen('php://memory', 'rw+'),
	   'ReportId' => $ReportId,
	 );
	 $request2 = new MarketplaceWebService_Model_GetReportRequest($parameters);
	 
	 $request2 = new MarketplaceWebService_Model_GetReportRequest();
	 $request2->setMarketplace($marketplaceID);
	 $request2->setMerchant($merchantID);
	 $request2->setReport(@fopen('php://memory', 'rw+'));
	 $request2->setReportId($ReportId);
	 
//////////////clear table with lists data for the repricer//////////////////////////////////////////
	 $query="UPDATE `amazon_repricer_report` SET status=\"undefined\" WHERE sellerName=\"$sellerName\"";
			 $result=$mysqlidb->query($query);
			 if(!$result){
				 $mysqlidb->UpdateStatusOfTask($taskID,"Error","error when UPDATE query(set status=undefined)! check sctipt, it's delete query from \"amazon_repricer_report\" table before request for items for the Repricer. [Cron-works .1.]");
				 die("error when delete query!");
			 }
///////////////////////////////////////////////////////////////////////////////////////////////////////


///////////get lists data into table////////////////////////////////////////////////////////////////////////	 
	 $fileName=invokeGetReport($mysqlidb,$ReportId,$taskID,$service, $request2);
	 if(preg_match_all("{([a-z0-9\-\_\@\:]+)	([a-z0-9]+)	([0-9\.]+)	([0-9]+)}si",file_get_contents($fileName),$arrayData,PREG_SET_ORDER)){
		 foreach($arrayData as $itemData){
			 $my_shipping_price=4.49;
			 $my_full_price=0;
			 $my_full_price=$my_shipping_price+$itemData[3];
			 
			 ///////check if defaults prices
			 if($defaultPrices==0){
				 /////check if item exist
				 $ifExistQuery="SELECT * FROM amazon_repricer_report WHERE sellerName=\"$sellerName\" AND SKU=\"$itemData[1]\"";
				 $ifExistResult=$mysqlidb->query($ifExistQuery);
				 if(!$ifExistResult){
					 $mysqlidb->UpdateStatusOfTask($taskID,"Error","[Cron-works .1.] error when check for exist SKU!.");
					 echo "error when check for exist SKU!";
					 exit;
				 }
				 if(mysqli_num_rows($ifExistResult)<=0){
					 $query="INSERT INTO `amazon_repricer_report`(`sellerName`,`SKU`,`my_item_price`,my_shipping_price,my_full_price,`default_item_price`,default_shipping_price,default_full_price,`itemsASIN`) ".
					 "VALUES(\"$sellerName\",\"$itemData[1]\",$itemData[3],$my_shipping_price,$my_full_price,$itemData[3],$my_shipping_price,$my_full_price,\"$itemData[2]\")";
				 }else{
					 $query="UPDATE `amazon_repricer_report` SET my_item_price=".$itemData[3].",".
					 "my_shipping_price=$my_shipping_price,".
					 "my_full_price=$my_full_price,".
					 "itemsASIN=\"$itemData[2]\",".
					 "status=\"compared\"".
					 " WHERE sellerName=\"$sellerName\" AND SKU=\"".$itemData[1]."\"";
				 }
			 }
			 if($defaultPrices==1){
				 /////check if item exist
				 $ifExistQuery="SELECT * FROM amazon_repricer_report WHERE sellerName=\"$sellerName\" AND SKU=\"$itemData[1]\"";
				 $ifExistResult=$mysqlidb->query($ifExistQuery);
				 if(!$ifExistResult){
					 $mysqlidb->UpdateStatusOfTask($taskID,"Error","[Cron-works .1.] error when check for exist SKU!.");
					 echo "error when check for exist SKU!";
					 exit;
				 }
				 if(mysqli_num_rows($ifExistResult)<=0){
					 $query="INSERT INTO `amazon_repricer_report`(`sellerName`,`SKU`,`my_item_price`,my_shipping_price,my_full_price,`default_item_price`,default_shipping_price,default_full_price,`itemsASIN`) ".
					 "VALUES(\"$sellerName\",\"$itemData[1]\",$itemData[3],$my_shipping_price,$my_full_price,$itemData[3],$my_shipping_price,$my_full_price,\"$itemData[4]\")";
				 }else{
					 $query="UPDATE `amazon_repricer_report` SET ".
					 "my_item_price=".$itemData[3].",".
					 "my_shipping_price=$my_shipping_price,".
					 "my_full_price=$my_full_price,".
					 "default_item_price=".$itemData[3].",".
					 "default_shipping_price=$my_shipping_price,".
					 "default_full_price=$my_full_price,".
					 "itemsASIN=\"$itemData[4]\",".
					 "status=\"compared\"".
					 " WHERE sellerName=\"$sellerName\" AND SKU=\"".$itemData[1]."\"";
				 }
			 }
			 ///end of check for default prices
			 
			 $result=$mysqlidb->query($query);
			 if(!$result){
				 $mysqlidb->UpdateStatusOfTask($taskID,"Error","error when insert or update query! it's last query to insert new data about items from response of Amazon API, check script and probably news from Amazon, etc. [Cron-works .1.]");
				 die("error when insert query!");
			 }
		 }
	 }else{
			$mysqlidb->UpdateStatusOfTask($taskID,"Error","error when preg_match_all! it's last thing, when response from amazon got, and false when preg_ for items from csv data of reponse [Cron-works .1.]");
			die("error when preg_match_all!");
		   }
//////////////////////////////////////////////////end of get lists data into table//////////////////////////////
unlink($fileName);

//delete undefined items
$query="DELETE FROM amazon_repricer_report WHERE sellerName=\"$sellerName\" AND status=\"undefined\"";
$result=$mysqlidb->query($query);
if(!$result){
	$mysqlidb->UpdateStatusOfTask($taskID,"Error","[Cron-works .1.] Error when DELETE FROM amazon_repricer_report, exit!");
	die("error when delete \'undefined\' items!");
}

$theTime=$count*50;
$mysqlidb->UpdateStatusOfTask($taskID,"Success.","Success. [Cron-works .1.]");
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////Part number 2///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
include_once('ModelProducts/Client.php');
include_once('ModelProducts/Error.php');
include_once('ModelProducts/ErrorResponse.php');
include_once('ModelProducts/Exception.php');
include_once('ModelProducts/Interface.php');
include_once('ModelProducts/IdListType.php');
include_once('ModelProducts/ASINIdentifier.php');
include_once('ModelProducts/ASINListType.php');
include_once('ModelProducts/ASINs.php');
include_once('ModelProducts/AttributeSetList.php');
include_once('ModelProducts/Categories.php');
include_once('ModelProducts/GetLowestOfferListingsForASINRequest.php');
include_once('ModelProducts/GetLowestOfferListingsForASINResponse.php');
include_once('ModelProducts/GetLowestOfferListingsForASINResult.php');
include_once('ModelProducts/GetLowestOfferListingsForSKURequest.php');
include_once('ModelProducts/GetLowestOfferListingsForSKUResponse.php');
include_once('ModelProducts/GetLowestOfferListingsForSKUResult.php');
include_once('ModelProducts/GetMyPriceForASINRequest.php');
include_once('ModelProducts/GetMyPriceForASINResponse.php');
include_once('ModelProducts/GetMyPriceForASINResult.php');
include_once('ModelProducts/GetMyPriceForSKURequest.php');
include_once('ModelProducts/GetMyPriceForSKUResponse.php');
include_once('ModelProducts/GetMyPriceForSKUResult.php');
include_once('ModelProducts/GetProductCategoriesForASINRequest.php');
include_once('ModelProducts/GetProductCategoriesForASINResponse.php');
include_once('ModelProducts/GetProductCategoriesForASINResult.php');
include_once('ModelProducts/GetProductCategoriesForSKURequest.php');
include_once('ModelProducts/GetProductCategoriesForSKUResponse.php');
include_once('ModelProducts/GetProductCategoriesForSKUResult.php');
include_once('ModelProducts/GetServiceStatusRequest.php');
include_once('ModelProducts/GetServiceStatusResponse.php');
include_once('ModelProducts/GetServiceStatusResult.php');
include_once('ModelProducts/IdentifierType.php');
include_once('ModelProducts/LowestOfferListingList.php');
include_once('ModelProducts/LowestOfferListingType.php');
include_once('ModelProducts/Message.php');
include_once('ModelProducts/MessageList.php');
include_once('ModelProducts/MoneyType.php');
include_once('ModelProducts/NumberOfOfferListingsList.php');
include_once('ModelProducts/NumberOfOfferListingsType.php');
include_once('ModelProducts/OfferCountType.php');
include_once('ModelProducts/OfferListingCountType.php');
include_once('ModelProducts/OffersList.php');
include_once('ModelProducts/OfferType.php');
include_once('ModelProducts/PriceType.php');
include_once('ModelProducts/Product.php');
include_once('ModelProducts/ProductList.php');
include_once('ModelProducts/QualifiersType.php');
include_once('ModelProducts/RelationshipList.php');
include_once('ModelProducts/ResponseHeaderMetadata.php');
include_once('ModelProducts/SalesRankList.php');
include_once('ModelProducts/SalesRankType.php');
include_once('ModelProducts/SellerSKUIdentifier.php');
include_once('ModelProducts/SellerSKUListType.php');
include_once('ModelProducts/ShippingTimeType.php');
include_once('ModelProducts/ResponseMetadata.php');




///////////////////////////////////////Additional functions/////////////////////////////////////////////////
  function invokeGetLowestOfferListingsForASIN($mysqlidb,$taskID,$sellerName,MarketplaceWebServiceProducts_Interface $service, $request) 
  {
      try {
              $response = $service->getLowestOfferListingsForASIN($request);
                $getLowestOfferListingsForASINResultList = $response->getGetLowestOfferListingsForASINResult();
                foreach ($getLowestOfferListingsForASINResultList as $getLowestOfferListingsForASINResult) {
					////////////////////////////////////////////////////////////////////////
					////////////////////very important//////////////////////////////////////
					////////each item must be get the default value -> isFoundSeller=false
					////////////////////////////////////////////////////////////////////////
					$isFoundSeller=false;
                    if ($getLowestOfferListingsForASINResult->isSetProduct()) { 
                        $product = $getLowestOfferListingsForASINResult->getProduct();
                        if ($product->isSetIdentifiers()) { 
                            $identifiers = $product->getIdentifiers();
                            if ($identifiers->isSetMarketplaceASIN()) {
                                $marketplaceASIN = $identifiers->getMarketplaceASIN();
                                if ($marketplaceASIN->isSetASIN()) 
                                {
                                    $CurrentASIN=$marketplaceASIN->getASIN();
                                }
                            }else{
									$mysqlidb->UpdateStatusOfTask($taskID,"Error","[Cron-works .2.] Error when function invokeGetLowestOfferListingsForASIN, ASIN not found check the script, section of [lowestOffer], exit!");
									die("ASIN not found check the script, section of [lowestOffer]");
								 }
                            if ($identifiers->isSetSKUIdentifier()) { 
                                $SKUIdentifier = $identifiers->getSKUIdentifier();
                                if ($SKUIdentifier->isSetMarketplaceId()) 
                                if ($SKUIdentifier->isSetSellerId()) 
                                {
                                    echo("                            SellerId\n");
                                    echo("                                " . $SKUIdentifier->getSellerId() . "\n");
                                }
                            } 
                        } 
                        if ($product->isSetLowestOfferListings()) { 
							$lowestOfferListings = $product->getLowestOfferListings();
                            $lowestOfferListingList = $lowestOfferListings->getLowestOfferListing();
                            foreach ($lowestOfferListingList as $lowestOfferListing) {
                            if ($lowestOfferListing->isSetQualifiers()) { 
                                if ($lowestOfferListing->isSetPrice()) { 
                                    $price1 = $lowestOfferListing->getPrice();
                                    if ($price1->isSetLandedPrice()) { 
                                        $landedPrice1 = $price1->getLandedPrice();
                                        if ($landedPrice1->isSetAmount()) 
                                        {
                                            $FullPrice=$landedPrice1->getAmount();
                                            $isFoundSeller=true;
                                        }
                                    } 
                                    if ($price1->isSetListingPrice()) { 
                                        $listingPrice1 = $price1->getListingPrice();
                                        if ($listingPrice1->isSetAmount()) 
                                        {
                                            $Price=$listingPrice1->getAmount();
                                        }
                                    } 
                                    if ($price1->isSetShipping()) { 
                                        $shipping1 = $price1->getShipping();
                                        if ($shipping1->isSetAmount()) 
                                        {
                                            $ShippingPrice=$shipping1->getAmount();
                                        }
                                    } 
									break;
                                }
                            }
						}}
						/////update with new values into DB
						////for items where lowes offer exist
						if($isFoundSeller){
							/////first of all, we need to calculate a new_item_price
								$query="UPDATE `amazon_repricer_report` SET ".
								"min_full_price=$FullPrice, ".
								"min_item_price=$Price, ".
								"min_shipping_price=$ShippingPrice, ".
								"new_item_price=$Price-(my_shipping_price-$ShippingPrice), ".
								"details=\"new price found.\"".
								"WHERE sellerName=\"$sellerName\" AND itemsASIN=\"$CurrentASIN\"";
							 }else{
								$query="UPDATE `amazon_repricer_report` SET my_full_price=default_full_price, new_item_price=default_item_price, min_full_price=default_full_price, min_item_price=default_item_price, min_shipping_price=default_shipping_price, details=\"back to default price.\" WHERE sellerName=\"$sellerName\" AND itemsASIN=\"$CurrentASIN\"";
							 }
							$result=$mysqlidb->query($query);
							if(!$result){
								$mysqlidb->UpdateStatusOfTask($taskID,"Error","[Cron-works .2.] error when update table with prices for the Repricer! if (lowestOfferListing->isSetPrice()), exit!");
								die("error when update table with prices for the Repricer! if (lowestOfferListing->isSetPrice())");
							}
                        }
                    }
                    if ($getLowestOfferListingsForASINResult->isSetError()) { 
                        //echo("                Error\n");
                        $error = $getLowestOfferListingsForASINResult->getError();
                        if ($error->isSetType()) 
                        {
                            //echo("                    Type\n");
                            //echo("                        " . $error->getType() . "\n");
                        }
                        if ($error->isSetCode()) 
                        {
                            //echo("                    Code\n");
                            //echo("                        " . $error->getCode() . "\n");
                        }
                        if ($error->isSetMessage()) 
                        {
                            //echo("                    Message\n");
                            //echo("                        " . $error->getMessage() . "\n");
                        }
                    } 
     } catch (MarketplaceWebServiceProducts_Exception $ex) {
         $error_msg="LowestOffer Caught Exception: " . $ex->getMessage() . "\n".
         "Response Status Code: " . $ex->getStatusCode() . "\n".
         "Error Code: " . $ex->getErrorCode() . "\n".
         "Error Type: " . $ex->getErrorType() . "\n".
         "Request ID: " . $ex->getRequestId() . "\n".
         "XML: " . $ex->getXML() . "\n".
         "ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n";
         if(
				(stristr($error_msg,"Unable to establish connection to host mws.amazonservices.com")!==false)
				OR
				(stristr($error_msg,"Unable to construct MarketplaceWebServiceProducts_Model_ErrorResponse from provided XML")!==false)
		   ){
			return "bad connection";
		 }elseif(stristr($error_msg,"Found duplicate value for ASINList")!==false){
			 $mysqlidb->UpdateStatusOfTask($taskID,"In Progress","duplicate found, item $CurrentASIN");
			 return "duplicate found";
		 }else{
			 $mysqlidb->UpdateStatusOfTask($taskID,"Error",$error_msg." [Cron-works .2.]");
			 exit;
		 }
     }
     return "ok";
 }
 
/**
  * Get My Price For ASIN Action Sample
  
  * @param MarketplaceWebServiceProducts_Interface $service instance of MarketplaceWebServiceProducts_Interface
  * @param mixed $request MarketplaceWebServiceProducts_Model_GetMyPriceForASIN or array of parameters
  */
  function invokeGetMyPriceForASIN($mysqlidb,$taskID,$sellerName,MarketplaceWebServiceProducts_Interface $service, $request) 
  {
      try {
              $response = $service->getMyPriceForASIN($request);
                $getMyPriceForASINResultList = $response->getGetMyPriceForASINResult();
                foreach ($getMyPriceForASINResultList as $getMyPriceForASINResult) {
                if ($getMyPriceForASINResult->isSetASIN()) {
                    $CurrentASIN=$getMyPriceForASINResult->getASIN();
                }else{
						$mysqlidb->UpdateStatusOfTask($taskID,"Error","[Cron-works .2.] error - can't get current ASIN!, function invokeGetMyPriceForASIN, exit!");
						die("can't get current ASIN!");
					 }
                if ($getMyPriceForASINResult->isSetProduct()) { 
					$product = $getMyPriceForASINResult->getProduct();    
                        if ($product->isSetOffers()) { 
                            $offers = $product->getOffers();
                            $offerList = $offers->getOffer();
                            foreach ($offerList as $offer) {
                                if ($offer->isSetBuyingPrice()) {
										$FullPrice=0;
										$Price=0;
										$ShippingPrice=0;
										$buyingPrice = $offer->getBuyingPrice();
									if ($buyingPrice->isSetLandedPrice()) { 
                                        $landedPrice2 = $buyingPrice->getLandedPrice();
                                        if ($landedPrice2->isSetAmount()) 
                                        {
                                            $FullPrice=$landedPrice2->getAmount();
                                        }
									}
									if ($buyingPrice->isSetListingPrice()) { 
                                        $listingPrice2 = $buyingPrice->getListingPrice();
                                        if ($listingPrice2->isSetAmount()) 
                                        {
                                            $Price=$listingPrice2->getAmount();
                                        }
									}
									if ($buyingPrice->isSetShipping()) { 
                                        $shipping2=$buyingPrice->getShipping();
                                        if ($shipping2->isSetAmount()) 
                                        {
                                            $ShippingPrice=$shipping2->getAmount();
                                        }
									}
                                        /////update with new values into DB
                                        if($FullPrice>0){
											$query="UPDATE `amazon_repricer_report` SET my_full_price=$FullPrice, my_item_price=$Price, my_shipping_price=$ShippingPrice WHERE sellerName=\"$sellerName\" AND itemsASIN=\"$CurrentASIN\"";
											$result=$mysqlidb->query($query);
											if(!$result){
												$mysqlidb->UpdateStatusOfTask($taskID,"Error","[Cron-works .2.] error when update table with prices for the Repricer!, function invokeGetMyPriceForASIN, exit!");
												die("error when update table with prices for the Repricer!");
											}
										}
                                    }
                                } 
                            }
                        }
                    
                    if ($getMyPriceForASINResult->isSetError()) { 
                        //echo("                Error\n");
                        $error = $getMyPriceForASINResult->getError();
                        if ($error->isSetType()) 
                        {
                            //echo("                    Type\n");
                            //echo("                        " . $error->getType() . "\n");
                        }
                        if ($error->isSetCode()) 
                        {
                            //echo("                    Code\n");
                            //echo("                        " . $error->getCode() . "\n");
                        }
                        if ($error->isSetMessage()) 
                        {
                            //echo("                    Message\n");
                            //echo("                        " . $error->getMessage() . "\n");
                        }
                    } 
                }
                if ($response->isSetResponseMetadata()) { 
                    //echo("            ResponseMetadata\n");
                    $responseMetadata = $response->getResponseMetadata();
                    if ($responseMetadata->isSetRequestId()) 
                    {
                        //echo("                RequestId\n");
                        //echo("                    " . $responseMetadata->getRequestId() . "\n");
                    }
                } 

              //echo("            ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n");
     } catch (MarketplaceWebServiceProducts_Exception $ex) {
         $error_msg="MyPrice Caught Exception: " . $ex->getMessage() . "\n".
         "Response Status Code: " . $ex->getStatusCode() . "\n".
         "Error Code: " . $ex->getErrorCode() . "\n".
         "Error Type: " . $ex->getErrorType() . "\n".
         "Request ID: " . $ex->getRequestId() . "\n".
         "XML: " . $ex->getXML() . "\n".
         "ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n";
         $mysqlidb->UpdateStatusOfTask($taskID,"Error",$error_msg." [Cron-works .2.]");
         exit;
     }
 }
//////////////////////////////////////End of Additional functions/////////////////////////////////////////////////

///////////////////////////////////////ADD NEW TASK INTO TASKS LISTS////////////////////////////////////////////////////////
		//first,
		//check if the task in the progress now
		if(
			($mysqlidb->checkTheTask($sellerName,"Amazon","Get all items for the Repricer, seller=$sellerName.")=="In Progress")
			OR
			($mysqlidb->checkTheTask($sellerName,"Amazon","Get all prices for the Repricer, seller=$sellerName.")=="In Progress")
			OR
			($mysqlidb->checkTheTask($sellerName,"Amazon","Change all prices for the Repricer, seller=$sellerName.")=="In Progress")
			OR
			($mysqlidb->checkTheTask($sellerName,"Amazon","Change one price for the Repricer, seller=$sellerName.")=="In Progress")
			){
			die("seller = $sellerName , platform = <font color=\"purple\">Amazon</font>, <font color=\"purple\">some task for the Repricer</font>, currently <font color=\"red\">in progress</font>, please wait.");
		}
		
		$taskID=$mysqlidb->makeNewTask($sellerName,"Amazon","Get all prices for the Repricer, seller=$sellerName.","[Cron-works .2.], no more details");
		
///////////////////////////////////////END OF ADD NEW TASK INTO TASKS LISTS/////////////////////////////////////////////////

////first update all values for default
$query="UPDATE `amazon_repricer_report` SET new_item_price=default_item_price, min_full_price=default_full_price, min_item_price=default_item_price, min_shipping_price=default_shipping_price, details=\"not processed yet.\" WHERE sellerName=\"$sellerName\"";
$result=$mysqlidb->query($query);
if(!$result){
	$mysqlidb->UpdateStatusOfTask($taskID,"Error","[Cron-works .2.] error when update table with prices for initial values -2., exit!");
	die("error when update table with prices for initial values -2.");
}


 $config = array (
   'ServiceURL' => $GLOBALS['serviceUrlProduct'], ///here another url
   'ProxyHost' => null,
   'ProxyPort' => -1,
   'MaxErrorRetry' => 3,
 );

 $service = new MarketplaceWebServiceProducts_Client(
     $accessKey, 
     $secretKey, 
     "TheInterface",
     "1.0.0",
     $config);
 
 
 
/************************************************************************
 * Uncomment to try out Mock Service that simulates MarketplaceWebServiceProducts
 * responses without calling MarketplaceWebServiceProducts service.
 *
 * Responses are loaded from local XML files. You can tweak XML files to
 * experiment with various outputs during development
 *
 * XML files available under Mock tree
 *
 ***********************************************************************/
 // $service = new MarketplaceWebServiceProducts_Mock();

/************************************************************************
 * Setup request parameters and uncomment invoke to try out 
 * sample for Get My Price For ASIN Action
 ***********************************************************************/
 // @TODO: set request. Action can be passed as MarketplaceWebServiceProducts_Model_GetMyPriceForASINRequest
 $ASINsLowest=new MarketplaceWebServiceProducts_Model_ASINs();
 $requestLowest = new MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForASINRequest();
 $requestLowest->setSellerId($merchantID);
 $requestLowest->setMarketplaceId($marketplaceID);
 $requestLowest->setItemCondition("new");
 $requestLowest->withExcludeMe("true");
 
 /////////////make a list with ASINs from a table with items for the RepriceR
 $query="SELECT itemsASIN FROM amazon_repricer_report WHERE sellerName=\"$sellerName\"";
 $result=$mysqlidb->query($query);
 if(!$result){
	 $mysqlidb->UpdateStatusOfTask($taskID,"Error","[Cron-works .2.] error when select items from repricers table!, exit!");
	 die("error when select items from repricers table!");
 }
 if(mysqli_num_rows($result)<=0){
	 $mysqlidb->UpdateStatusOfTask($taskID,"Error","[Cron-works .2.] no ASINs in the table!, exit!");
	 die("no ASINs in the table!");
 }
 $countOfItems=0;
 $countOfQueries=0;
 while($row=$result->fetch_assoc()){
	 $countOfItems++;
	 //////add ASIN into ASINs List
	 $requestLowest->setASINList($ASINsLowest->withASIN(($row['itemsASIN'])));
	 if($countOfItems==19){
		 $countOfQueries++;
		 ////////get prices for ASINs if there 20 items in the list(Amazon API limitation = 20 items in one request)
		 if(//bad connection detected
				invokeGetLowestOfferListingsForASIN($mysqlidb,$taskID,$sellerName,$service, $requestLowest)==="bad connection"
			){
				////just wait 2 sec. and try again
				sleep(2);
				 if(//bad connection detected
						invokeGetLowestOfferListingsForASIN($mysqlidb,$taskID,$sellerName,$service, $requestLowest)==="bad connection"
					){
						////just wait 5 sec. and try again
						sleep(5);
						 if(//bad connection detected
								invokeGetLowestOfferListingsForASIN($mysqlidb,$taskID,$sellerName,$service, $requestLowest)==="bad connection"
								){
									$mysqlidb->UpdateStatusOfTask($taskID,"Error","Unable to establish connection to host mws.amazonservices.com AFTER ALL WAITINGS! [Cron-works .2.]");
									exit;
								 }
					 }
			 }
		 unset($requestLowest);
		 unset($ASINsLowest);
			$ASINsLowest=new MarketplaceWebServiceProducts_Model_ASINs();;
			//
			$requestLowest = new MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForASINRequest();
			$requestLowest->setSellerId($merchantID);
			$requestLowest->setMarketplaceId($marketplaceID);
			$requestLowest->setItemCondition("new");
			$requestLowest->withExcludeMe("true");
			///set current count of items into 0
			$countOfItems=0;
		 /////////////////wait
		 //here -> http://earn-using-api.blogspot.com/2012/08/amazon-mws-api-throttling-limits-of.html
			sleep(2);
	 }
 }

if(($countOfItems<19)AND(($countOfItems!=0))){
			 if(//bad connection detected
				invokeGetLowestOfferListingsForASIN($mysqlidb,$taskID,$sellerName,$service, $requestLowest)==="bad connection"
			){
				////just wait 2 sec. and try again
				sleep(2);
				 if(//bad connection detected
						invokeGetLowestOfferListingsForASIN($mysqlidb,$taskID,$sellerName,$service, $requestLowest)==="bad connection"
					){
						////just wait 5 sec. and try again
						sleep(5);
						 if(//bad connection detected
								invokeGetLowestOfferListingsForASIN($mysqlidb,$taskID,$sellerName,$service, $requestLowest)==="bad connection"
								){
									$mysqlidb->UpdateStatusOfTask($taskID,"Error","Unable to establish connection to host mws.amazonservices.com AFTER ALL WAITINGS! [Cron-works .2.]");
									exit;
								 }
					 }
			 }
}

/////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////
///////////////second time, get all items where status = not processed yet
/////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////
 unset($requestLowest);
 unset($ASINsLowest);
 $ASINsLowest=new MarketplaceWebServiceProducts_Model_ASINs();
 $requestLowest = new MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForASINRequest();
	$requestLowest->setSellerId($merchantID);
	$requestLowest->setMarketplaceId($marketplaceID);
	$requestLowest->setItemCondition("new");
	$requestLowest->withExcludeMe("true");
 $ASINsLowest=new MarketplaceWebServiceProducts_Model_ASINs();;
	//
 $query="SELECT itemsASIN FROM amazon_repricer_report WHERE sellerName=\"$sellerName\" AND details=\"not processed yet.\"";
 $result=$mysqlidb->query($query);
 if(!$result){
	 $mysqlidb->UpdateStatusOfTask($taskID,"Error","[Cron-works .2.] error when select items from repricers table!, exit!");
	 die("error when select items from repricers table!");
 }
 
 $countOfItems=0;
 $countOfQueries=0;
 while($row=$result->fetch_assoc()){
	 $countOfItems++;
	 //////add ASIN into ASINs List
	 $requestLowest->setASINList($ASINsLowest->withASIN(($row['itemsASIN'])));
	 if($countOfItems==19){
		 $countOfQueries++;
		 ////////get prices for ASINs if there 20 items in the list(Amazon API limitation = 20 items in one request)
		 if(//bad connection detected
				invokeGetLowestOfferListingsForASIN($mysqlidb,$taskID,$sellerName,$service, $requestLowest)==="bad connection"
			){
				////just wait 2 sec. and try again
				sleep(2);
				 if(//bad connection detected
						invokeGetLowestOfferListingsForASIN($mysqlidb,$taskID,$sellerName,$service, $requestLowest)==="bad connection"
					){
						////just wait 5 sec. and try again
						sleep(5);
						 if(//bad connection detected
								invokeGetLowestOfferListingsForASIN($mysqlidb,$taskID,$sellerName,$service, $requestLowest)==="bad connection"
								){
									$mysqlidb->UpdateStatusOfTask($taskID,"Error","Unable to establish connection to host mws.amazonservices.com AFTER ALL WAITINGS! [Cron-works .2.]");
									exit;
								 }
					 }
			 }
		 unset($requestLowest);
		 unset($ASINsLowest);
			$ASINsLowest=new MarketplaceWebServiceProducts_Model_ASINs();;
			//
			$requestLowest = new MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForASINRequest();
			$requestLowest->setSellerId($merchantID);
			$requestLowest->setMarketplaceId($marketplaceID);
			$requestLowest->setItemCondition("new");
			$requestLowest->withExcludeMe("true");
			///set current count of items into 0
			$countOfItems=0;
		 /////////////////wait
		 //here -> http://earn-using-api.blogspot.com/2012/08/amazon-mws-api-throttling-limits-of.html
			sleep(2);
	 }
 }
/////////////////////////////////////////////////////////////////////////
///////////second time if has some items after main cycle//////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////
if(($countOfItems<19)AND(($countOfItems!=0))){
			 if(//bad connection detected
				invokeGetLowestOfferListingsForASIN($mysqlidb,$taskID,$sellerName,$service, $requestLowest)==="bad connection"
			){
				////just wait 2 sec. and try again
				sleep(2);
				 if(//bad connection detected
						invokeGetLowestOfferListingsForASIN($mysqlidb,$taskID,$sellerName,$service, $requestLowest)==="bad connection"
					){
						////just wait 5 sec. and try again
						sleep(5);
						 if(//bad connection detected
								invokeGetLowestOfferListingsForASIN($mysqlidb,$taskID,$sellerName,$service, $requestLowest)==="bad connection"
								){
									$mysqlidb->UpdateStatusOfTask($taskID,"Error","Unable to establish connection to host mws.amazonservices.com AFTER ALL WAITINGS! [Cron-works .2.]");
									exit;
								 }
					 }
			 }
} 
/////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////

$mysqlidb->UpdateStatusOfTask($taskID,"Success.","Success. All prices were recived. [Cron-works .2.]");
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////Part number 3///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////ADD NEW TASK INTO TASKS LISTS////////////////////////////////////////////////////////
		//first,
		//check if the task in the progress now
		if(
			($mysqlidb->checkTheTask($sellerName,"Amazon","Get all items for the Repricer, seller=$sellerName.")=="In Progress")
			OR
			($mysqlidb->checkTheTask($sellerName,"Amazon","Get all prices for the Repricer, seller=$sellerName.")=="In Progress")
			OR
			($mysqlidb->checkTheTask($sellerName,"Amazon","Change all prices for the Repricer, seller=$sellerName.")=="In Progress")
			OR
			($mysqlidb->checkTheTask($sellerName,"Amazon","Change one price for the Repricer, seller=$sellerName.")=="In Progress")
			){
			die("seller = $sellerName , platform = <font color=\"purple\">Amazon</font>, <font color=\"purple\">some task for the Repricer</font>, currently <font color=\"red\">in progress</font>, please wait. [Cron-works .3.]");
		}
		
		$taskID=$mysqlidb->makeNewTask($sellerName,"Amazon","Change all prices for the Repricer, seller=$sellerName.","[Cron-works .3.], no more details");
		
///////////////////////////////////////END OF ADD NEW TASK INTO TASKS LISTS/////////////////////////////////////////////////

///////////////////
    //try to send it to amazon
///////////////////
$today=getdate();
$tmpFile=$today[mday].$today[mon].$today[year].$today[hours].$today[minutes].$today[seconds].".txt";
$fhandle=fopen($tmpFile,'w');
if(!$fhandle)
 {
	 echo "faild create file";
	 $mysqlidb->UpdateStatusOfTask($taskID,"Error","faild create file, UpdatePicesToAmazon! [Cron-works .3.]");
	 exit;
 }
 

	/////////////////////make an .xml request
	//begin of .xml
		$strtosave="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
		<AmazonEnvelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"amzn-envelope.xsd\">
		<Header>
		<DocumentVersion>1.01</DocumentVersion>
		<MerchantIdentifier>$merchantID</MerchantIdentifier>
		</Header>
		<MessageType>Price</MessageType>";
		
		$query="SELECT * FROM `amazon_repricer_report` WHERE sellerName=\"".$mysqlidb->real_escape_string($sellerName)."\"";
		$result=$mysqlidb->query($query);
		if(!$result)
		{
			echo "error in sql query, when select all items from repricer report!";
			$mysqlidb->UpdateStatusOfTask($taskID,"Error","error in sql query, when select all items from repricer report! UpdatePicesToAmazon! [Cron-works .3.]");
			exit;
		}
		if(mysqli_num_rows($result)>0)
		 {
			 $countOfMessages=1;
			while($row=$result->fetch_assoc())
			 {
				if(
					(($row["new_item_price"]>$row["my_item_price"])AND($row["details"]!="back to default price."))
					OR
					(($row["details"]=="back to default price.")AND($row["default_item_price"]==$row["my_item_price"]))
					OR
					(($row["new_item_price"]<=0)AND($row["details"]!="back to default price."))
					OR
					 //here calculate Floor from settings of Amazon profile
					(($row["new_item_price"]<(($row["default_item_price"]*$RepricerFloor)/100))AND($row["details"]!="back to default price."))
				  ){
					///////////////////////////////////////
					////////do nothing/////////////////////
					///////in these cases/////////////////
					///////////////////////////////////////
				}else{
						$priceToSend=$row["new_item_price"];
					///////if prices are equal, then minus value from amazon sellers profile
					if(($row["min_full_price"]===$row["my_full_price"])AND(($row["my_item_price"]-$minusIfEqual)>(($row["default_item_price"]*$RepricerFloor)/100))AND($row["details"]!="back to default price.")){
							$priceToSend=$row["my_item_price"]-$minusIfEqual;
						}
						$strtosave=$strtosave.
						"<Message>".
							"<MessageID>$countOfMessages</MessageID>".
							"<Price>".
							"<SKU><![CDATA[".$row["SKU"]."]]></SKU>".
							"<StandardPrice currency=\"USD\"><![CDATA[".number_format(preg_replace("{\,}si","",$priceToSend),2)."]]></StandardPrice>".
							"</Price>".
							"</Message>";
						$countOfMessages++;
				}
			 }
		 }else{
				echo "no items there!";
				$mysqlidb->UpdateStatusOfTask($taskID,"Error","no items there! UpdatePicesToAmazon! [Cron-works .3.]");
				exit;
			  }
		$strtosave=$strtosave."</AmazonEnvelope>";
		////end of .xml
	fwrite($fhandle,$strtosave);
	fclose($fhandle);
	
						$config = array (
						  'ServiceURL' => $GLOBALS['serviceUrl'],
						  'ProxyHost' => null,
						  'ProxyPort' => -1,
						  'MaxErrorRetry' => 3,
						);
						/************************************************************************
						 * Instantiate Implementation of MarketplaceWebService
						 * 
						 * AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY constants 
						 * are defined in the .config.inc.php located in the same 
						 * directory as this sample
						 ***********************************************************************/
						 $service = new MarketplaceWebService_Client(
							 $accessKey/*AWS_ACCESS_KEY_ID*/, 
							 $secretKey/*AWS_SECRET_ACCESS_KEY*/, 
							 $config,
							 "TheInterface",
							 "1.0.0");
						$feed = file_get_contents($tmpFile);
						$feedHandle = @fopen('php://temp', 'rw+');
						fwrite($feedHandle, $feed);
						rewind($feedHandle);
						$marketplaceIdArray = array("Id" => array($marketplaceID));
						$parameters = array (							  
						  'Merchant' => $merchantID/*MERCHANT_ID*/,
						  'MarketplaceIdList' => $marketplaceIdArray,
						  'FeedType' => '_POST_PRODUCT_PRICING_DATA_',
						  'FeedContent' => $feedHandle,
						  'PurgeAndReplace' => false,
						  'ContentMd5' => base64_encode(md5(stream_get_contents($feedHandle), true)),
						);
						rewind($feedHandle);
						$request = new MarketplaceWebService_Model_SubmitFeedRequest($parameters);
						/**
						  * Submit Feed Action Sample
						  * Uploads a file for processing together with the necessary
						  * metadata to process the file, such as which type of feed it is.
						  * PurgeAndReplace if true means that your existing e.g. inventory is
						  * wiped out and replace with the contents of this feed - use with
						  * caution (the default is false).
						  *   
						  * @param MarketplaceWebService_Interface $service instance of MarketplaceWebService_Interface
						  * @param mixed $request MarketplaceWebService_Model_SubmitFeed or array of parameters
						  */
							  try {
									  $response = $service->submitFeed($request);
										//echo ("Service Response\n");
										//echo ("=============================================================================\n");

										//echo("        SubmitFeedResponse\n");
										if ($response->isSetSubmitFeedResult()) { 
											//echo("            SubmitFeedResult\n");
											$submitFeedResult = $response->getSubmitFeedResult();
											if ($submitFeedResult->isSetFeedSubmissionInfo()) { 
												//echo("                FeedSubmissionInfo\n");
												$feedSubmissionInfo = $submitFeedResult->getFeedSubmissionInfo();
												if ($feedSubmissionInfo->isSetFeedSubmissionId()) 
												{
													//echo("                    FeedSubmissionId\n");
													//echo("                        " . $feedSubmissionInfo->getFeedSubmissionId() . "\n");
													$FeedSubmissionId=$feedSubmissionInfo->getFeedSubmissionId();
												}
												if ($feedSubmissionInfo->isSetFeedType()) 
												{
													//echo("                    FeedType\n");
													//echo("                        " . $feedSubmissionInfo->getFeedType() . "\n");
												}
												if ($feedSubmissionInfo->isSetSubmittedDate()) 
												{
													//echo("                    SubmittedDate\n");
													//echo("                        " . $feedSubmissionInfo->getSubmittedDate()->format(DATE_FORMAT) . "\n");
												}
												if ($feedSubmissionInfo->isSetFeedProcessingStatus()) 
												{
													//echo("                    FeedProcessingStatus\n");
													//echo("                        " . $feedSubmissionInfo->getFeedProcessingStatus() . "\n");
													$FeedProcessingStatus=$feedSubmissionInfo->getFeedProcessingStatus();
												}
												if ($feedSubmissionInfo->isSetStartedProcessingDate()) 
												{
													//echo("                    StartedProcessingDate\n");
													//echo("                        " . $feedSubmissionInfo->getStartedProcessingDate()->format(DATE_FORMAT) . "\n");
												}
												if ($feedSubmissionInfo->isSetCompletedProcessingDate()) 
												{
													//echo("                    CompletedProcessingDate\n");
													//echo("                        " . $feedSubmissionInfo->getCompletedProcessingDate()->format(DATE_FORMAT) . "\n");
												}
											} 
										} 
										if ($response->isSetResponseMetadata()) { 
											//echo("            ResponseMetadata\n");
											$responseMetadata = $response->getResponseMetadata();
											if ($responseMetadata->isSetRequestId()) 
											{
												//echo("                RequestId\n");
												//echo("                    " . $responseMetadata->getRequestId() . "\n");
											}
										} 
							 } catch (MarketplaceWebService_Exception $ex) {
								 $error_msg="MyPrice Caught Exception: " . $ex->getMessage() . "\n".
								 "Response Status Code: " . $ex->getStatusCode() . "\n".
								 "Error Code: " . $ex->getErrorCode() . "\n".
								 "Error Type: " . $ex->getErrorType() . "\n".
								 "Request ID: " . $ex->getRequestId() . "\n".
								 "XML: " . $ex->getXML() . "\n".
								 "ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n";
								 $mysqlidb->UpdateStatusOfTask($taskID,"Error",$error_msg." UpdatePicesToAmazon! [Cron-works .3.]");
								 exit;
							 }
							 @fclose($feedHandle);

unlink($tmpFile);

$operation="change prices Repricer";

//insert info into log
$query="INSERT INTO amazon_logs(operation,FeedSubmissionId,dateOfOperation,StatusOfOperation,sellerName) ".
	"VALUES(\"$operation\",\"$FeedSubmissionId\",\"".
	$today[year]."-".$today[mon]."-".$today[mday]."T".$today[hours].":".$today[minutes].":".$today[seconds]."\",".
	"\"unknown yet, please [ Get_Result ] first\",".
	"\"$sellerName\")";
$result=$mysqlidb->query($query);
if(!$result){
	echo "error when write log!";
	$mysqlidb->UpdateStatusOfTask($taskID,"Error","error when write log into Amazon_Feeds_Log! [Cron-works .3.]");
	exit;
}
$mysqlidb->UpdateStatusOfTask($taskID,"Success.","Success. Feed was submitted to Amazon, go to Amazon_Feeds_Log to see a results. [Cron-works .3.]");
?>
