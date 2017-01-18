<?php

	require_once("iTunesRSS.php");
	require_once("DataBroker.php");

	global $db;

	$iTunes = new iTunesRSS();
	$genreList = $iTunes->GetGenreListFromiTunes();

	$db = new DataBroker();
	if (!$db->Connect()){
		echo "cannot connect to database, exiting.\n";
		return;
	}

	HandleGenreList($genreList);

	function HandleGenreList ($genreList) {
		global $db;

		foreach ($genreList as $genreID => $genre){
			echo "GenreID: $genreID - Genre: " . $genre['name'] . "\n";
			// do what we need to here with the genre.
			$db->InsertGenre($genreID, $genre['name']);

			if (isset($genre['subgenres'])) {
				// update all the sub genres.
				HandleGenreList($genre['subgenres']);
			}
		}
	}
?>