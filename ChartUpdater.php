<?php
	global $db;
	require_once('iTunesRSS.php');
	require_once('FacebookWriter.php');
	require_once('DataBroker.php');

	class ChartUpdater {

		public function run ($postToWall = true) {
			global $db;

			$db = new DataBroker();
			if (!$db->Connect()){
				echo "cannot connect to database, exiting.\n";
				return;
			}

			$pageArray = $db->GetPages();

			foreach ($pageArray as $page){
				//echo "\nupdating page: " . $page['name'] . "\n";
				$this->HandlePageUpdate ($page, $postToWall);
			}
		}

		private function HandlePageUpdate ($page, $postToWall) {
			global $db;
			$entriesToPost = null;
			$idArray;

			// get the feeds
			$iTunes = new iTunesRSS();
			$genreID = $page['id_genre'];
			$pageID = $page['id'];
			$accessToken = $page['access_token'];

			$feedEntries = $iTunes->GetFeedForGenre($genreID);

			// for each feed item, check if it is in the database using its id
			$rank = 1;
			foreach ($feedEntries as $entry) {

				//echo "\nTitle: " . (string)$entry->title;


				$entryID = (string)$entry->id[0]->attributes("im", TRUE)->id;
				$idArray[] = $entryID;
				$entry->newRank = $rank;

				$song = $db->GetSong($entryID, $genreID);

				if($song)
				{
					// if the new rank is less than the old rank (higher in the charts where 1 is the highest)
					if ($rank < $song['rank'])
					{
						$entry->oldRank = $song['rank'];
						$entriesToPost[] = $entry;
					}
				}
				else
				{
					// new song, gotta post it
					$entriesToPost[] = $entry;
				}
				$db->InsertSong($entryID, $genreID, $rank);

				$rank++;
			}

			// wipe database of all old things
			$db->RemoveSongsWhereIDNotIn($idArray, $genreID);

			if (Count($entriesToPost))
			{
				$fbWriter = new FacebookWriter();
				//echo "We have new entries to post :)\n";
				if ($postToWall) {
					$fbWriter->PostFeeds($entriesToPost, $pageID, $accessToken);
				}

				// only need to update the description if we have new entries to post
				// signifying a change in the top 10.
				$fbWriter->SetDescription($feedEntries, $pageID, $accessToken);
			}
			else
			{
				//echo "no new entries to post :(\n";
			}
		}
	}

?>
