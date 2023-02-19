
<?php

require_once __DIR__ . '/autoloader.php';

use Classes\ViewEngine;
use Classes\QueryBuilder;

$strGruppi = '';
$strPrivati = '';
$courses = new QueryBuilder('courses, users');
$courses = $courses->where('courses.user_id=users.id')->project(['title', 'description', 'max_partecipants', 'name', 'surname'])->select();

foreach ($courses as $corso) {
  $str = "<tr><th scope=\"row\">{$corso->title}</th><td>{$corso->description}</td><td>{$corso->name} {$corso->surname}</td></tr>";
  if ($corso->max_partecipants > 1) $strGruppi .= $str;
  else $strPrivati .= $str;
}
$strHtml=  (new ViewEngine(basename(__FILE__, '.php')))->build();
$strHtml= str_replace("<data-group></data-group>", $strGruppi, $strHtml);
echo str_replace("<data-private></data-private>", $strPrivati, $strHtml);
