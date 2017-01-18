<?php

	class ChartUpdater {

		protected $em;

		public function __construct(\Doctrine\ORM\EntityManager $em)
		{
			$this->em = $em;
		}

		public function run ($postToWall = true) {
			$pageArray = $this->em->getRepository('itunes\Page')->findAll();

			foreach ($pageArray as $page){
				echo "\nupdating page: " . $page->getName() . "\n";
				$this->HandlePageUpdate ($page, $postToWall);
			}
		}

		private function HandlePageUpdate ($page, $postToWall) {
			global $db;
			$entriesToPost = null;
			$idArray = [];

			// get the feeds
			$iTunes = new iTunesRSS();
			$genreID = $page->getGenre()->getId();
			$pageID = $page->getId();
			$accessToken = $page->getAccessToken();

			$feedEntries = $iTunes->GetFeedForGenre($genreID);

			// for each feed item, check if it is in the database using its id
			$rank = 1;
			foreach ($feedEntries as $entry) {

				//echo "\nTitle: " . (string)$entry->title;


				$entryID = (string)$entry->id[0]->attributes("im", TRUE)->id;
				$idArray[] = $entryID;
				$entry->newRank = $rank;

				$repo = $this->em->getRepository('itunes\Song');
				$songEntity = $repo->findOneBy([
					'id' => $entryID,
					'genre' => $genreID
				]);

				if($songEntity)
				{
					// if the new rank is less than the old rank (higher in the charts where 1 is the highest)
					if ($rank < $songEntity->getRank())
					{
						$entry->oldRank = $songEntity->getRank();
						$entriesToPost[] = $entry;
					}
					$songEntity->setRank($rank);
					$this->em->persist($songEntity);
				}
				else
				{
					$genre = $this->em->find('itunes\Genre', $genreID);
					$songEntity = new \itunes\Song($entryID, $genre, $rank);
					// new song, gotta post it
					echo "\nNew Song Gotta post it\n";
					$entriesToPost[] = $entry;
					$this->em->persist($songEntity);
				}


				$rank++;
			}

			// wipe database of all old things
			$qb = $this->em->getRepository('itunes\Song')->createQueryBuilder('s');

			$qb
				->where($qb->expr()->eq('s.genre', $genreID))
				->andWhere($qb->expr()->notIn('s.id', $idArray))
			;

			$songsToDelete = $qb->getQuery()->execute();

			foreach($songsToDelete as $songToDelete)
			{
				$this->em->remove($songsToDelete);
			}

			$this->em->flush();

			if (count($entriesToPost))
			{
				$fbWriter = new FacebookWriter();
				echo "We have new entries to post :)\n";
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
