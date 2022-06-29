<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


// require_once $_SERVER["DOCUMENT_ROOT"] . $this->GetPath() . "/SoapConfig.php";
// require_once $_SERVER["DOCUMENT_ROOT"] . $this->GetPath() . "/SoapClient.php";

// use Volsu\Soap\SoapConfig;
// use Volsu\Soap\SoapClient;

//$cacheFileName = "cache";

$cacheFileName = __DIR__ . "/cache";

$cache = (array)json_decode(file_get_contents($cacheFileName) ?? []);
$directions = $cache['directions'];

$kafedras = [];

for ($i = 0; $i <= count($directions);  $i++)
{
	$kafedras[]= $directions[$i]->Kafedra;
}

$kafedras = array_diff($kafedras, array(''));

$kafedras = array_unique($kafedras);


echo "<pre>" . print_r($kafedras, true). "</pre>";

$arComponentParameters = array(
		"GROUPS" => array(),
		
		"PARAMETERS" => array(

			"DEPARTMENTS"   =>  array(
				"PARENT"    =>  "LIST",
				"NAME"      =>  "Список",
				"TYPE"      =>  "LIST",
				"VALUES"    =>  $kafedras,
				"MULTIPLE"  =>  "N",
				"ADDITIONAL_VALUES" => "Y",
			),

			"FULL_NAME" => array(
				"PARENT" => "BASE",
				"NAME" => "Фамилия, имя, отчество заведующего кафедрой",
				"TYPE" => "STRING",
				"MULTIPLE" => "N",
				"DEFAULT" => "Y-m-d",
				),

			"PHOTO_OF_THE_HEAD_OF_THE_DEPARTMENTS" => array(
				"PARENT" => "BASE",
				"NAME" => "Загрузить фото заведующего кафедрой",
				"TYPE" => "FILE",
				"FD_TARGET" => "F",
				"FD_EXT" => $ext,
				"FD_UPLOAD" => true,
				"FD_USE_MEDIALIB" => true,
				"FD_MEDIALIB_TYPES" => Array('image')
				),
				
			"ACADEMIC_DEGREE" => array(
				"PARENT" => "BASE",
				"NAME" => "Ученая степень",
				"TYPE" => "STRING",
				"MULTIPLE" => "N",
				"DEFAULT" => "Y-m-d",  
				),
			
			"ADDRESS_OF_THE_DEPARTMENT" => array(
				"PARENT" => "BASE",
				"NAME" => "Адрес кафедры",
				"TYPE" => "STRING",
				"MULTIPLE" => "N",    
				),
			"DEPARTMENT_TELEPHONE" => array(
				"PARENT" => "BASE",
				"NAME" => "Телефон кафедры",
				"TYPE" => "STRING",
				"MULTIPLE" => "N", 
				),
			"DEPARTMENT_EMAIL" => array(
				"PARENT" => "BASE",
				"NAME" => "Электронная почта кафедры",
				"TYPE" => "STRING",
				"MULTIPLE" => "N",   
				),
			"SITE_OF_THE_DEPARTMENT" => array(
				"PARENT" => "BASE",
				"NAME" => "Сайт кафедры",
				"TYPE" => "STRING",
				"MULTIPLE" => "N",  
				),
			"POSITION" => array(
				"PARENT" => "BASE",
				"NAME" => "Загрузить файл положения",
				"TYPE" => "FILE",
				"FD_TARGET" => "F",
				"FD_EXT" => $ext,
				"FD_UPLOAD" => true,
				"FD_USE_MEDIALIB" => true,
				"FD_MEDIALIB_TYPES" => Array('image')
				),
			"GENERAL_INFORMATION" => array(
				"PARENT" => "BASE",
				"NAME" => "Общая информация",
				"TYPE" => "STRING",
				"MULTIPLE" => "N",   
				),

			"GENERAL_PAGE" => array(
				"PARENT" => "BASE",
				"NAME" => "Ссылка на подробный состав кафедры",
				"TYPE" => "STRING",
				"MULTIPLE" => "N",  
				),
			"RESPONSIBLE" => array(
				"PARENT" => "BASE",
				"NAME" => "Ответственный за модерирование раздела кафедры",
				"TYPE" => "STRING",
				"MULTIPLE" => "N",   
				),
			"DEPARTMENT"   => Array(
				"PARENT"    => "BASE",
				"NAME"      => "Номер подразделения", //TODO сделать автоматическую подгрузку списка подразделений
				"TYPE"      => "STRING",
				"MULTIPLE"  => "N"
			),
			"DETAIL"        => Array(
				"PARENT"    => "BASE",
				"NAME"      => "Страница с детальным описанием",
				"TYPE"      => "STRING",
				"MULTIPLE"  => "N"
			),
			"NUMBER_BEGIN"	=>Array(
				"PARENT"    => "BASE",
				"NAME"      => "Номер начальной строки",
				"TYPE"      => "STRING",
				"MULTIPLE"  => "N"
				),
			"NUMBER_END"	=>Array(
				"PARENT"    => "BASE",
				"NAME"      => "Номер последней строки",
				"TYPE"      => "STRING",
				"MULTIPLE"  => "N"
				),
			"TYPE"	=>Array(
				"PARENT"    => "BASE",
				"NAME"      => "Тип списка",
				"TYPE"      => "LIST",
				"VALUES"	=> array(
						'full' 	=> "Полный (с аватарами)",
						'short'	=> "Краткий"
					),
				),

			),
);



?>

