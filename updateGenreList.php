<?php

    require_once 'bootstrap.php';

	$iTunes = new iTunesRSS();
	$genreList = $iTunes->GetGenreListFromiTunes();

	HandleGenreList($genreList);

	function HandleGenreList ($genreList) {
        global $container;

		foreach ($genreList as $genreID => $genreData){
			echo "GenreID: $genreID - Genre: " . $genreData['name'] . "\n";
			// do what we need to here with the genre.
			$genreEntity = new \itunes\Genre($genreID, $genreData['name']);

            $container['em']->merge($genreEntity);

			if (isset($genreData['subgenres'])) {
				// update all the sub genres.
				HandleGenreList($genreData['subgenres']);
			}
		}

		$container['em']->flush();
	}
?>