<?php

	class iTunesRSS {
		private $genreListURL = 'http://itunes.apple.com/WebObjects/MZStoreServices.woa/ws/genres';
		private $feedURL = 'https://itunes.apple.com/au/rss/topsongs/limit=10/explicit=true/';
		private $feedFormat = 'xml';

		function GetFeedForGenre ($genreID = null) {
			$feedForGenre = $this->feedURL . ( $genreID ? "genre=$genreID/" : "" ) . $this->feedFormat;

			$rssFeed = file_get_contents($feedForGenre);

			$xmlObject = simplexml_load_string($rssFeed);
			// json doesn't have the 'name' attribute that has the "song - artist" format. doesn't have artist
			// as its own field either. have to go with xml for now.

			return $xmlObject->entry;
		}

		function GetGenreListFromiTunes() {
			$genreList = file_get_contents($this->genreListURL);

			$jsonObject = json_decode($genreList, true);
			// json doesn't have the 'name' attribute that has the "song - artist" format. doesn't have artist
			// as its own field either. have to go with xml for now.

			return $jsonObject;
		}
	}
?>