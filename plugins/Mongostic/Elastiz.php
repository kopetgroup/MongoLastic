<?php
use \Elasticsearch\ClientBuilder as Elastic;

class Elastiz {

  function elastic_insert($index,$type,$data){

    $elastik  = new Elastic;

    //normalisasi elastik
    $id = $data['_id'];
    unset($data['_id']);

    $params = [
      'index' => $index,
      'type'  => $type,
      'id'    => $id,
      'body'  => $data
    ];
    $res = (object) $elastik->create()->build()->index($params);
    return $res;

  }

  function elastic_update($index,$type,$data){
    $elastik  = new Elastic;

    $params = [
      'index' => $index,
      'type'  => $type,
      'id'    => $data['id'],
      'body' => [
        'doc' => $data
      ]
    ];
    $response = $elastik->create()->build()->update($params);
    return $response;
  }

  function elastic_delete($index,$type,$id){

    //echo shell_exec('curl -X DELETE localhost:9200/'.$index.'/'.$type.'/'.$id);

    try {

      $elastik  = new Elastic;
      $params = [
          'index' => $index,
          'type' => $type,
          'id' => $id
      ];
      $res = $elastik->build()->delete($params);
      $st  = 'success';

    }catch(Exception $res){

      $st  = 'failed';
      $res = false;

    }

    return (object) [
      'status' => $st,
      'result' => $res
    ];
  }

  function elastic_drop($index){

    $res = shell_exec('curl -X DELETE http://localhost:9200/'.$index);
    return json_decode($res);

  }

  function reindex($index,$collection){

    //create map
    elastic_mapping($index);

    //get data from mongo rest
    $mg = 'http://localhost:28017/'.$index.'/'.$collection.'/?skip=0';
    $mg = shell_exec('curl -X GET '.$mg);
    $mg = json_decode($mg);

    echo $mg->total_rows;

  }

  function elastic_map($index,$field,$term){

    $r = 'curl -XGET \'localhost:9200/'.$index.'/_search\' -H \'Content-Type: application/json\' -d\'
    {
      "query": {
        "match": {
          "'.$field.'": "'.$term.'"
        }
      },
      "sort": {
        "_score": "desc"
      },
      "aggs": {
        "'.str_replace(' ','_',$field).'": {
          "terms": {
            "field": "'.$field.'.raw"
          }
        }
      }
    }
    \'';
    $r = shell_exec($r);
    $r = json_decode($r);
    return $r;

  }

  function elastic_mapping($index){
    try {
      $map = str_replace('{koleksi}',$index,file_get_contents('elastic_map.json'));
      return shell_exec('curl -X PUT \'localhost:9200/'.$index.'?pretty\' -H \'Content-Type: application/json\' -d\''.$map.'\'');
    }catch(Exception $map){
      return false;
    }
  }

}
