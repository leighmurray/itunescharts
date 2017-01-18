<?php
	require_once('facebook-php-sdk/src/facebook.php');

	class FacebookWriter {

		private $fb;
		private $affiliateLink = '&at=11ldhi';
		private $config = array(
				'appId' => '1352185481479730',
				'secret' => '420ef6602b8c9a73e709553e303c9ffa',
			  );

		private function initialise () {
			if (!isset($this->fb)){
				$this->fb = new Facebook($this->config);
			}
		}

		public function PostFeeds ($feeds, $pageID, $accessToken) {

			$this->initialise();
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
				$ret_obj = $this->fb->api("/$pageID/feed", 'POST',
					array(
					  'link' => $link,
					  'message' => $title,
					  'access_token' => $accessToken
					)
				);
				echo "Title: $title\nLink: $link\n";
				print_r($ret_obj);
			}
		}

		public function SetDescription ($entries, $pageID, $accessToken) {
			$this->initialise();
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

			$ret_obj = $this->fb->api("/$pageID/", 'POST',
				array(
				  'description' => $newDescription,
				  'access_token' => $accessToken
				)
			);
		}
	}
?>
