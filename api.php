<?php
echo '<pre>';

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';
require 'plugins/Mongostic/Mrongos.php';

use Mongostic\Mrongos as Mrongos;

$dbname       = 'shelby_v1';
$collection   = 'keywords';

$q    = 'kopet idea kopet silit';
$data = [
  '_id'       => 'keyword_'.str_replace(' ','-',$q),
  'title'     => $q,
  'date'      => date('Y-m-d H:i:s'),
  'tgl'       => strtotime(date('Y-m-d H:i:s')),
  'type'      => 'keyword',
  'category'  => 'kopet',
  'subcat'    => 'kopet ideas',
  'status'    => 'available'
];

$mos  = new Mrongos;
$me   = $mos->insert($dbname,$collection,$data);
print_r($me);
die();
