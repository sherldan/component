<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$institutes = $arResult['INSTITUTES'];
$original = $arResult['DIRECTIONS'];
//$places = $arResult['PLACES'];
//$exams = $arResult['EXAMS'];

$currentLevel = $_GET['level'] ?? "Бакалавриат и специалитет";

/*echo "<html>" . "<head>" . "</head>" . "<body>";
echo '<div class"wrapper">';

// вывод названия кафедры
$num_kaf = $arResult['DEPARTMENTS'];
$selected = $original[$num_kaf]->Kafedra;
echo '<div class"department-name">' . $selected . "</div>";

$tabu = 'внутр.';*/

$num_kaf = $arResult['DEPARTMENTS'];
$selected = $original[$num_kaf]->Kafedra;
$tabu = 'внутр.';

?>

<html>
	<head>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&family=Philosopher&display=swap" rel="stylesheet">

		<title> <? echo $selected; ?> </title>
	</head>

	<body>
		<div class"wrapper">
			<H1> <? echo $selected; ?> </H1>

			<table width="100%" cellspacing="0" cellpadding="5">
			<tr> 
				<td width="200" valign="top">  

				<img  src="<?php echo $arResult["PHOTO_OF_THE_HEAD_OF_THE_DEPARTMENTS"]; ?>"  width = "200px" height=”250px”> 
				</td>
				<td valign="top"> 
					<p><h3> Заведующий кафедрой:  <? echo $arResult['FULL_NAME']; ?> </h3> </p>
					<p><b> Ученая степень: </b> <? echo $arResult['ACADEMIC_DEGREE']; ?>  </p>
					<p><b> Адрес кафедры: </b> <? echo $arResult['ADDRESS_OF_THE_DEPARTMENT']; ?> </p>
					<p><b> Телефон:  </b> <? echo $arResult['DEPARTMENT_TELEPHONE']; ?> </p>
					<p><b> E-mail: </b> <? echo $arResult['DEPARTMENT_EMAIL']; ?> </p>
					<p><b> Сайт кафедры: </b> <a href=" <? echo $arResult['SITE_OF_THE_DEPARTMENT']; ?>"> <? echo $arResult['SITE_OF_THE_DEPARTMENT'] ?> </a> </p>
					<p> <b> Положение о кафедре: </b> <a href="<?php echo $arResult["POSITION"]; ?>" > Открыть PDF </a>
			</p>
					</td>
			</tr>
			</table>
			</html>
			
			<?

			echo "<p>". "<h2>" . "Общая информация о кафедре: ". "</h2>" . $arResult['GENERAL_INFORMATION']. "</h2>". "</p>";

			?>
				
			</div>


			<div class="tab">
			<button class="tablinks" onclick="openTab(event, 'Directions')" id="defaultOpen">Направления</button>
			<button class="tablinks" onclick="openTab(event, 'Compound')">Состав кафедры</button>
			<button class="tablinks" onclick="openTab(event, 'Additionally')">Дополнительная информация</button>
			</div>



			<div id="Directions" class="tabcontent">
			

			<p> <? for ( $i = 0; $i <= count($original); $i++)
			{
				if ($original[$i]->Kafedra == $selected)
				{

					$code = $original[$i]->SpecialityCodeOKSO;

					echo '<div class="card">'.
					'<div class="container">' .
						"<b-1>" . "<p>" . ($original[$i]->SpecialityName) . " " . '<div class="color-text">'.($original[$i]->SpecialityCodeOKSO)  ."</div>". "</b-1>";


							if ($original[$i]->ProfilName != "")
										{
											echo "<p>" . "Профиль: " .$original[$i]->ProfilName . "\n";
										} 
								
							echo	"<p>" . "Возможные формы обучения: " .$original[$i]->EducationFormName. "\n". 
									"<p>" . "Квалификация: " . $original[$i]->EducationLevelRef->ReferenceName . "\n".
									"<p>" . "Форма набора: " .$original[$i]->FinanceName . "\n".
									"</p>".
							"</div>".
							"</div>";
				}
			}
			?></p>



			</div>


			<div id="Compound" class="tabcontent">
				
				<? for($m=1; $m < count($arResult['EMPLOY']); $m++)
						{   echo '<div class="card-1">'. '<div class="container">'; 
							echo '<b-1>'. ($m)  . '. ' . $arResult['EMPLOY'][$m]->Наименование . " , ";
							echo $arResult['EMPLOY'][$m]->Должность . "<br>\r\n";
							echo '<div class="color-text-1">'. $arResult['EMPLOY'][$m]->Email . "<br>\r\n </div>"; 
							$pos = strpos($arResult['EMPLOY'][$m]->КонтактныеДанные, $tabu);
							
				
							if ($pos === false) {
								echo $arResult['EMPLOY'][$m]->КонтактныеДанные . "<br>\r\n";}
							if ($m < count($arResult['EMPLOY']) - 1)
									{
										echo "<br>\r\n";
									}
									echo '</b-1>  </div>
									</div>';
						} 

					
			?>

			<p><a href="<?php echo $arResult["GENERAL_PAGE"]; ?>" > Подробнее </a> </p>
			</p>

			</div>

			<div id="Additionally" class="tabcontent">
			<h2>Дополнительная информация о кафедре</h2>
			<p>Ответственный за модерирование раздела кафедры <?php echo $arResult["RESPONSIBLE"]; ?></p>
			</div>

	
				
			<script>
			function openTab(evt, tabName) {
				var i, tabcontent, tablinks;
				tabcontent = document.getElementsByClassName("tabcontent");
				for (i = 0; i < tabcontent.length; i++) {
					tabcontent[i].style.display = "none";
				}
				tablinks = document.getElementsByClassName("tablinks");
				for (i = 0; i < tablinks.length; i++) {
					tablinks[i].className = tablinks[i].className.replace(" active", "");
				}
				document.getElementById(tabName).style.display = "block";
				evt.currentTarget.className += " active";
			}

			// Get the element with id="defaultOpen" and click on it
			document.getElementById("defaultOpen").click();
			</script>

</body>
</html> 


