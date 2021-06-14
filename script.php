<html>
<head>
	<link href="css/default.css" rel="stylesheet" type="text/css"/>
	<title>Чемпионат Италии по сезонам</title>
</head>
<body>
<?
include_once('./modeles/simple_html_dom.php');
$url = 'https://terrikon.com';
$er_mes = '<div class="all"><div class="main">Не верно заполнили поле "Команда"</div><a href="./">НАЗАД</a></div>'; 
if(isset($_POST['team']) && !empty($_POST['team'])):?>
<?
	$htmlteam = file_get_html($url.'/football/italy/championship');
	$team = [];
	foreach($htmlteam->find('div#champs-table td.team') as $em){
		$team[] = str_replace(' ','',$em->plaintext);
	}
	if(!in_array($_POST['team'],$team)){
		echo $er_mes;
		//есть ли такая команда вообще и стоит ли дальше делать запрос
		return;
	}
	$html = file_get_html($url.'/football/italy/championship/archive');
?>
<div class="all">
	<div class="main">Чемпионат Италии, Серия А, Занятые места команды <strong><?echo $_POST['team'];?></strong> по сезонам</div>
	<div class="container">
		<table>
	<?
	$tr1 = '<thead><tr>';
	$tr2 = '<tr>';
	$tr1.= '<td><strong>Сезон</strong></td>';
	$tr2.= '<td><strong>Место</strong></td>';
	foreach($html->find('div.tab div.news a') as $e){
		$season = str_replace(', Чемпионат Италии','',$e->innertext);
		$season = str_replace('. Чемпионат Италии','',$season);
		$tr1.= '<td>'.$season.'</td>';
		$html2 = file_get_html($url.$e->href);
		foreach($html2->find('div.tab tr') as $el){
			if($el->find('td', 1)->plaintext == $_POST['team']){
				$tr2.='<td>'.$season = str_replace('.','',$el->find('td', 0)->plaintext).'</td>';
			}
		}
		$html2->clear();
		unset($html2);
	}
	$html->clear();
	unset($html);
	$tr1.= '</tr></thead>';
	$tr2.= '</tr>';
	echo $tr1;
	echo $tr2;
	?>
		</table>
	</div>
<a href="./">НАЗАД</a>
</div>
<?else:?>
<?echo $er_mes?>
<?endif;?>
</body>
</html>