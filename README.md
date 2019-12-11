# TaxcomOFD_php
TaxCom OFD PHP API Class

	https://lk-ofd.taxcom.ru/ApiHelp/
	Для получения ID интегратора, требующегося для доступа к методам API, 
	необходимо обратиться с соответствующим запросом в техническую поддержку компании "Такском".
  
**Quick start:**

``` 
<?php
require('TaxcomOFD.class.php');
$API = new TaxComOFD(); 
```

### The first way to get a sessionToken is to call auth with a direct indication of the data from the account.

  `$TaxcomSessionToken = $API->TaxcomAuth('Login@name.domain','Password','IntegratorID');`

Now that you have a token, you can use it to call functions without re-authorization within 5 minutes from the moment you receive or the last request. (This is stated in the service documentation, but in some places it is at variance with reality.)

If you have several accounts for access to the API with different access levels - just use authorization as many times as needed with different data and store tokens separately.

### The second way is to simply uncomment and specify the login information in the class file, multiple authorization will not be available, proceed according to circumstances.

in ***TaxcomOFD.class.php***
```
//	var $Login = 'login@name.domain';
//	var $Password = 'YoUpAsSwOrD';
//	var $IntegratorID = 'Get_You_Individual_Id_From_Taxcom_Support_Service';
```
***and in your php code***

`$TaxcomSessionToken = $API->TaxcomAuth();`

The whole list of API methods up to version 2.7 is available on the official documentation page. 

[https://lk-ofd.taxcom.ru/ApiHelp/](https://lk-ofd.taxcom.ru/ApiHelp/)


## An example of obtaining a list of stores

```
$TaxcomOutletList = $API->TaxcomOutletList($TaxcomSessionToken);
```
