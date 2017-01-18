<?php

	class FacebookWriter {

        public function __construct()
        {
            $this->fb = new \Facebook\Facebook($this->config);
        }

        private $fb;
		private $affiliateLink = '&at=11ldhi';
		private $config = array(
				'app_id' => '1352185481479730',
				'app_secret' => '420ef6602b8c9a73e709553e303c9ffa',
				'default_graph_version' => 'v2.8'
			  );

		public function PostFeeds ($feeds, $pageID, $accessToken) {

			$campaignString = '&ct=' . $pageID;

			foreach ($feeds as $feed){

				$songRank = $feed->newRank;
				$link = (string)$feed->id;
				$link .= $this->affiliateLink;
				$link .= $campaignString;

				$title = "#$songRank. " . (string)$feed->title;
				if ($feed->oldRank) {
					$title = "↑ " . $title;
				}
				else
				{
					$title .= "\nNew to the Top 10";
				}
				$title .= "\nClick the link to view in iTunes.";
				$ret_obj = $this->fb->post("/$pageID/feed", [
					  'link' => $link,
					  'message' => $title
					],
                    $accessToken
				);
				echo "Title: $title\nLink: $link\n";
				print_r($ret_obj);
			}
		}

		public function SetDescription ($entries, $pageID, $accessToken) {

			$campaignString = '&ct=' . $pageID . "D";

			$newDescription = "Current iTunes Top 10 - Click the links to view in iTunes:\n\n";

			foreach ($entries as $entry){
				$songRank = $entry->newRank;
				$link = (string)$entry->id;
				$link .= $this->affiliateLink;
				$link .= $campaignString;

				$title = "#$songRank. " . (string)$entry->title;
				$newDescription .= $title . "\n";
				$newDescription .= $link . "\n\n";
			}

			$ret_obj = $this->fb->post("/$pageID/", [
				  'description' => $newDescription
				],
                $accessToken
			);
		}
	}

