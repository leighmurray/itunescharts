<?php

class DataBroker {

	private $con;
	private $dbHost = 'localhost';
	private $dbUsername = "root";
	private $dbPassword = "";
	private $dbDatabaseName = "itunes_charts";

	public function Connect () {
		$this->con = mysqli_connect($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbDatabaseName);

		// Check connection
		if (mysqli_connect_errno($this->con))
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
			return false;
		}
		else{
			return true;
		}
	}

	public function GetSong ($songID, $genreID) {
		$result = mysqli_query($this->con, "SELECT * FROM song WHERE id='$songID' AND id_genre='$genreID'");
		return mysqli_fetch_array($result);
	}

	public function InsertSong($songID, $genreID, $rank) {

		$songID = mysqli_real_escape_string($this->con, $songID);
		$rank = mysqli_real_escape_string($this->con, $rank);

		//echo "Inserting ID: $entryID with rank: $rank\n";
		mysqli_query($this->con,"INSERT INTO song (id, id_genre, rank) VALUES ('$songID', '$genreID', '$rank') ON DUPLICATE KEY UPDATE rank='$rank'");
	}

	public function RemoveSongsWhereIDNotIn ($idArray, $genreID) {
		$queryString = "DELETE FROM song WHERE id_genre='$genreID' AND id NOT IN ('" . implode("','", $idArray) ."')";
		mysqli_query($this->con, $queryString);
	}

	public function GetPages () {
		$result = mysqli_query($this->con, "SELECT * FROM page");
		$pageArray = [];
		while ($row = $result->fetch_assoc()) {
			// do what you need.
			$pageArray[] = $row;
		}
		return $pageArray;

	}

	public function InsertGenre ($genreID, $genreName) {
		$genreID = mysqli_real_escape_string($this->con, $genreID);
		$genreName = mysqli_real_escape_string($this->con, $genreName);

		$queryString = "INSERT INTO genre (id,name) VALUES ('$genreID', '$genreName') ON DUPLICATE KEY UPDATE name='$genreName'";

		$result =  mysqli_query($this->con, $queryString);

		if ($result === false) {
			echo "Update Genre Table Query Failed\n";
		}else
		{
			echo "it worked I guess.\n";
		}
	}
}


?>