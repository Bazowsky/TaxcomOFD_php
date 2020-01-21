<?php
/*****************************
 *
 * TaxCom OFD PHP API class v2.0
 * Author: Alex Bazowsky
 * alexbazowsky@gmail.com
 *
 * https://github.com/Bazowsky/TaxcomOFD_php
 * https://lk-ofd.taxcom.ru
 *
 ******************************/

	/*
	https://lk-ofd.taxcom.ru/ApiHelp/
	Для получения ID интегратора, требующегося для доступа к методам API,
	необходимо обратиться с соответствующим запросом в техническую поддержку компании "Такском".
	*/
	// nope
class TaxComOFD {

	/*
	Declare Login, Password and IntegratorID inside class variables,
	or use put it in the initial auth function every time you need to get session token.
	This may be useful if you have multiplie logins with rights management, i.e. for view only, for administration etc.
	*/
//	var $Login = 'login@name.domain';
//	var $Password = 'YoUpAsSwOrD';
//	var $IntegratorID = 'Get_You_Individual_Id_From_Taxcom_Support_Service';

/*
Debug function returns printed arrays of each function.
*/
	var $Debug = false;
	var $Decode = true;
	    public function TaxcomDebug($message) {
        if ($this->Debug) {
            print_r($message);
            //echo '<br>';
        }
    }

    public function TaxcomCurl($url, $TaxcomSessionToken) {
    $ch = curl_init($url);
	  curl_setopt($ch, CURLOPT_HEADER, false);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //echo off
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Session-Token:'.$TaxcomSessionToken));
    $result = curl_exec($ch);
    curl_close($ch);
    if ($this->Decode)
    $result = json_decode($result, true);
    return $result;
    }

    /*
    Only login/pass method implemented yet.
    On https://lk-ofd.taxcom.ru/ApiHelp/index.html?2_1_2____.htm it says:
    "При вызове метода новый маркер доступа не создаётся, если не закончился срок действия предыдущего маркера."
   	But on current version api v2.7 this info is bullshit. TaxcomSessionToken returns as new EVERY time you call this function.
   	Just remember this, if you store it somewhere...
    */
//2.1.2. Логин и пароль
	public function TaxcomAuth($Login=false, $Password=false, $IntegratorID=false) {
    $url = 'https://api-lk-ofd.taxcom.ru/API/v2/Login';
    $jsonData='{"login": "'.(!$Login?$this->Login:$Login).'", "password" : "'.(!$Password?$this->Password:$Password).'" }';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //echo off
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Integrator-ID:'.(!$IntegratorID?$this->IntegratorID:$IntegratorID).''));
    $TaxcomSessionToken=curl_exec($ch);
    curl_close($ch);
	  $TaxcomSessionToken = json_decode($TaxcomSessionToken, true);
	  $this->TaxcomDebug($TaxcomSessionToken);
		return $TaxcomSessionToken['sessionToken'];
}
//2.2. Личные кабинеты
	public function TaxcomAccountList($TaxcomSessionToken) {
		$url = 'https://api-lk-ofd.taxcom.ru/API/v2/AccountList';
    $result = $this->TaxcomCurl($url, $TaxcomSessionToken);
    $this->TaxcomDebug($result);
  	return ($result);
}
//2.3.1. Список подразделений ЛК
	public function TaxcomDepartmentList($TaxcomSessionToken, $np=false, $pn=false, $ps=false) {
		$url = 'https://api-lk-ofd.taxcom.ru/API/v2/DepartmentList?'.(!$np?'':'&np='.$np).(!$pn?'':'&pn='.$pn).(!$ps?'':'&ps='.$ps);
    $result = $this->TaxcomCurl($url, $TaxcomSessionToken);
    $this->TaxcomDebug($result);
  	return ($result);
}
//2.3.2. Сводные данные по подразделению
	public function TaxcomDepartmentInfo($TaxcomSessionToken, $id) {
		$url = 'https://api-lk-ofd.taxcom.ru/API/v2/DepartmentInfo?id='.$id;
    $result = $this->TaxcomCurl($url, $TaxcomSessionToken);
    $this->TaxcomDebug($result);
  	return ($result);
}
//2.4.1. Список торговых точек
	public function TaxcomOutletList($TaxcomSessionToken, $id=false, $np=false, $pn=false, $ps=false) {
		$url = 'https://api-lk-ofd.taxcom.ru/API/v2/OutletList?'.(!$id?'':'&id='.$id).(!$np?'':'&np='.$np).(!$pn?'':'&pn='.$pn).(!$ps?'':'&ps='.$ps);
    $result = $this->TaxcomCurl($url, $TaxcomSessionToken);
    $this->TaxcomDebug($result);
  	return ($result);
}
//2.4.2. Сводные данные по торговой точке
	public function TaxcomOutletInfo($TaxcomSessionToken, $id) {
		$url = 'https://api-lk-ofd.taxcom.ru/API/v2/OutletInfo?id='.$id;
    $result = $this->TaxcomCurl($url, $TaxcomSessionToken);
    $this->TaxcomDebug($result);
  	return ($result);
}
//2.5.1. Список ККТ по торговой точке
	public function TaxcomKKTList($TaxcomSessionToken, $id, $np=false, $pn=false, $ps=false) {
		$url = 'https://api-lk-ofd.taxcom.ru/API/v2/KKTList?id='.$id.(!$np?'':'&np='.$np).(!$pn?'':'&pn='.$pn).(!$ps?'':'&ps='.$ps);
    $result = $this->TaxcomCurl($url, $TaxcomSessionToken);
    $this->TaxcomDebug($result);
  	return ($result);
}
//2.5.2. Список фискальных накопителей по ККТ
	public function TaxcomFnHistory($TaxcomSessionToken, $kktRegNumber, $np=false) {
		$url = 'https://api-lk-ofd.taxcom.ru/API/v2/FnHistory?kktRegNumber='.$kktRegNumber.(!$np?'':'&np='.$np);
    $result = $this->TaxcomCurl($url, $TaxcomSessionToken);
    $this->TaxcomDebug($result);
  	return ($result);
}
//2.5.3. Сводные данные по ККТ
	public function TaxcomKKTInfo($TaxcomSessionToken, $fn) {
		$url = 'https://api-lk-ofd.taxcom.ru/API/v2/KKTInfo?fn='.$fn;
    $result = $this->TaxcomCurl($url, $TaxcomSessionToken);
    $this->TaxcomDebug($result);
  	return ($result);
}
//2.6.1. Список смен за период по ККТ
	public function TaxcomShiftList($TaxcomSessionToken, $fn, $begin, $end, $pn=false, $ps=false) {
		$url = 'https://api-lk-ofd.taxcom.ru/API/v2/ShiftList?fn='.$fn.'&begin='.$begin.'&end='.$end.(!$pn?'':'&pn='.$pn).(!$ps?'':'&ps='.$ps);
    $result = $this->TaxcomCurl($url, $TaxcomSessionToken);
    $this->TaxcomDebug($result);
  	return ($result);
}
//2.6.2. Сводные данные по смене
	public function TaxcomShiftInfo($TaxcomSessionToken, $fn, $shift) {
		$url = 'https://api-lk-ofd.taxcom.ru/API/v2/ShiftInfo?fn='.$fn.'&shift='.$shift;
    $result = $this->TaxcomCurl($url, $TaxcomSessionToken);
    $this->TaxcomDebug($result);
  	return ($result);
}
//2.7.1. Список документов по смене
	public function TaxcomDocumentList($TaxcomSessionToken, $fn, $shift, $type=false, $pn=false, $ps=false) {
		$url = 'https://api-lk-ofd.taxcom.ru/API/v2/DocumentList?fn='.$fn.'&shift='.$shift.(!$type?'':'&type='.$type).(!$pn?'':'&pn='.$pn).(!$ps?'':'&ps='.$ps);
    $result = $this->TaxcomCurl($url, $TaxcomSessionToken);
    $this->TaxcomDebug($result);
  	return ($result);
}
//2.7.2. Данные документа
	public function TaxcomDocumentInfo($TaxcomSessionToken, $fn, $fd) {
		$url = 'https://api-lk-ofd.taxcom.ru/API/v2/DocumentInfo?fn='.$fn.'&fd='.$fd;
    $result = $this->TaxcomCurl($url, $TaxcomSessionToken);
    $this->TaxcomDebug($result);
  	return ($result);
}
//2.7.3. Ссылка на документ
	public function TaxcomDocumentURL($TaxcomSessionToken, $fn, $fd) {
		$url = 'https://api-lk-ofd.taxcom.ru/API/v2/DocumentURL?fn='.$fn.'&fd='.$fd;
    $result = $this->TaxcomCurl($url, $TaxcomSessionToken);
    $this->TaxcomDebug($result);
  	return ($result);
}
//2.7.4.1. Печать документа в HTML-формате
	public function TaxcomPrintDocumenthtml($TaxcomSessionToken, $kktId, $fp) {
		$url = 'https://api-lk-ofd.taxcom.ru/API/v2/PrintDocument/html?kktId='.$kktId.'&fp='.$fp;
    $result = $this->TaxcomCurl($url, $TaxcomSessionToken);
    $this->TaxcomDebug($result);
  	return ($result);
}
//2.7.4.2. Печать документа в PDF-формате
	public function TaxcomPrintDocumentpdf($TaxcomSessionToken, $kktId, $fp) {
		$url = 'https://api-lk-ofd.taxcom.ru/API/v2/PrintDocument/pdf?kktId='.$kktId.'&fp='.$fp;
		$result = $this->TaxcomCurl($url, $TaxcomSessionToken);
    $this->TaxcomDebug($result);
  	return ($result);
}
/*
2.8, 2.9, 2.10
Not implemented, due to security risk.
*/

//4.1 Новые документы
	public function TaxcomNewDocuments($TaxcomSessionToken, $an=false, $id=false) {
		$url = 'https://api-lk-ofd.taxcom.ru/API/v2/NewDocuments?'.(!$an?'':'&an='.$an).(!$id?'':'&id='.$id);
    $result = $this->TaxcomCurl($url, $TaxcomSessionToken);
    $this->TaxcomDebug($result);
  	return ($result);
}
}
