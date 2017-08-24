<?php
namespace Mongostic;

require 'Elastiz.php';
require 'Mongoz.php';

use Elastiz as Elastik;
use Mongoz as Mongos;

class Mrongos {

  function insert($dbname,$collection,$data){

    $ela = new Elastik;
    $mon = new Mongos;

    if($data['_id']){

      $m = $mon->mongo_insert($dbname,$collection,$data);
      $e = (object) ['status'=>'error'];
      if($m->status=='success'){
        $e = $ela->elastic_insert($dbname,$collection,$data);
      }
      return (object) [
        'elastic' => $e,
        'mongo'   => $m
      ];

    }
  }

  function update($dbname,$collection,$data){

    $ela = new Elastik;
    $mon = new Mongos;

    $m = $mon->mongo_update($dbname,$collection,$data);
    $e = $ela->elastic_update($dbname,$collection,$data);
    return (object) [
      'elastic' => $e,
      'mongo'   => $m
    ];
  }

  function delete($dbname,$collection,$id){

    $ela = new Elastik;
    $mon = new Mongos;

    $m = $mon->mongo_delete($dbname,$collection,$id);
    $e = $ela->elastic_delete($dbname,$collection,$id);
    return (object) [
      'elastic' => $e,
      'mongo'   => $m
    ];
  }

}
