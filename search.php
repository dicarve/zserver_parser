<?php
/**
 * Parser XML untuk zserver
 * Arie Nugraha 2014
 * dicarve@gmail.com
 *
 * Simple XML parser untuk zserver
 **/
// $zserver_url = 'http://lx2.loc.gov:210/lcdb?version=1.1&operation=searchRetrieve&query=dinosaur';
$keywords = 'Indonesia';
$max_records = 1;
$zserver_url = 'http://lx2.loc.gov:210/lcdb';
$zserver_url = $zserver_url.'?version=1.1&operation=searchRetrieve&query='.$keywords.'&maximumRecords='.$max_records;

// jalankan CURL
$ch = curl_init();

// apply setting curl
curl_setopt($ch, CURLOPT_URL, $zserver_url);
// curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: text/xml'));
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0');
curl_setopt($ch, CURLOPT_FAILONERROR, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// ambil string XML dari server
$xml_string = curl_exec($ch);

// echo $xml_string;

// $xml = new SimpleXMLElement($xml_string);
$xml = new SimpleXMLElement($xml_string);
$zs = $xml->children('http://www.loc.gov/zing/srw/');

// print_r($zs);
echo "Versi protokol yang digunakan adalah: ".$zs->version."\n";
echo "Jumlah record yang ditemukan adalah: ".$zs->numberOfRecords."\n";
echo "Record bibliografi: \n";
$records = $zs->records->record;

foreach ($records as $record) {
  // print_r($record);
  $tipe_metadata = $record->recordSchema;
  $metadata_data = $record->recordData->children();
  echo "Record :\n";
  // print_r($metadata_data);
  foreach ($metadata_data as $data) {
    // print_r($data);
    echo 'Leader: '.$data->leader."\n";
	foreach ($data->datafield as $datafield) {
	  echo "  MARC Tag : ".$datafield['tag']."\n";
	  foreach ($datafield->subfield as $subfield) {
	    echo "    Subfield ".$subfield['code'].": ".$subfield." \n";
	  }
	}
  }
  echo "-------------------------------- \n";
}

curl_close($ch);