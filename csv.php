<?php

ini_set("error_reporting", E_ERROR);

echo "Version 1.1\n";
echo "=======================\n";

echo "csv.php [index]";

require 'vendor/autoload.php';

require 'Sherlock/Sherlock.php';
\Sherlock\Sherlock::registerAutoloader();

include 'includes/functions.core.php';
include 'includes/class.wp-error.php';
include 'includes/functions.bp-options.php';
include 'includes/functions.plugin-api.php';
include 'includes/class.wp-http.php';

$sherlock = new \Sherlock\Sherlock();
$sherlock->addNode("elastic.flowcom.se", "80");

echo "Sherlock started to elastic.flowcom.se...\n";

$index_name = $argv[1];

if( empty( $index_name ) ) $index_name = "mo-iuware";
$index = $sherlock->index( $index_name );
echo "Index " . $index_name . " created...\n";

$result = "";
$headline = "";
$paper = "";

if (($handle = fopen("./papernet_engelska_nyheter.csv", "r")) !== FALSE)
{
	$length = 1000;
	$delimiter = ",";

	while ( ( $data = fgetcsv( $handle, $length, $delimiter ) ) !== FALSE )
	{
		$date = date( 'Y-m-d', $data[0] );
		$headline = $data[1];
		$preamble = $data[2];
		$body = $data[3];

		if( !empty( $headline ) ){
			//check if already exists
			//Build a new search request
			$request = $sherlock->search();

			//populate a Term query to start
			$termQuery = \Sherlock\Sherlock::queryBuilder()->Term()->field("headline")
					->term( $headline );

			//Set the index, type and from/to parameters of the request.
			$request->index( $index_name )
					->type("article")
					->from(0)
					->to(10)
					->query($termQuery);

			//Execute the search and return results
			$response = $request->execute();

			if( count($response) && $argv[5]!=1 ){
				echo $headline . " - already in index, no save!\n";
			}
			else{

				$doc = array(
					"ssoid" => '-1',
					"paper" => 'papernet.se',
					"headline" => $headline,
					"date" => $date,
					"preamble" => $preamble,
					"body" => $body
				);
				echo $headline . " [" . $date . "]\n";

				$doc = $sherlock->document()->index( $index_name )->type( 'article' )->document( $doc );
				$doc->execute();
			}

		}

	}

	// Close the file pointed to by $handle
	fclose($handle);
}