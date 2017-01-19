<?php

namespace itunes;

/**
 * @Entity
 */
class Song
{

    public function __construct($id, Genre $genre, $rank)
    {
        $this->id = $id;
        $this->genre = $genre;
        $this->rank = $rank;
    }

    /** @Column(type="integer") @Id */
    private $id;

    /** @Column */
    private $rank;

    /** @Id @ManyToOne(targetEntity="Genre") */
    private $genre;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * @param mixed $rank
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    /**
     * @return mixed
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param mixed $genre
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;
    }


}