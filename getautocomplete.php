<?php

// configuration
require("includes/config.php");

$DB = DB::getInstance();

$type = Input::get('type');
$term = Input::get('term');

if (!empty($type) && !empty($term)) {

    if ($type == "newConfName") {
        $results = $DB->assocQuery("SELECT title FROM conferences "
                . "WHERE title LIKE ? "
                . "UNION "
                . "SELECT title FROM suppconfrecords WHERE title LIKE ? "
                . "GROUP BY title", "%" . $term . "%", "%" . $term . "%")
                ->results();

        header("Content-type: application/json");
        print(json_encode(array_column($results, "title"), JSON_PRETTY_PRINT));
        
    } else if ($type == "newConfLocation") {
        $results = $DB->assocQuery("SELECT location FROM conferences "
                . "WHERE location LIKE ? "
                . "UNION "
                . "SELECT location FROM suppconfrecords WHERE location LIKE ? "
                . "GROUP BY location", "%" . $term . "%", "%" . $term . "%")
                ->results();

        header("Content-type: application/json");
        print(json_encode(array_column($results, "location"), JSON_PRETTY_PRINT));
        
    } else if ($type == "newTrainDesc") {
        $filter = Input::get("filter");
        
        $query = ["SELECT description FROM trainingrecords "
            . "WHERE description LIKE ? ", "%" . $term . "%"];
        if (!empty($filter)) {
            $query[0] .= "AND trainingid = ? ";
            array_push($query, $filter);
        }
        $query[0] .= "GROUP BY description";
        
        $results = $DB->runQuery(PDO::FETCH_ASSOC, null, $query)->results();

        header("Content-type: application/json");
        print(json_encode(array_column($results, "description"), JSON_PRETTY_PRINT));
        
    } else if ($type == "newPubDesc") {
        $results = $DB->assocQuery("SELECT title FROM publicationrecords "
                . "WHERE title LIKE ? GROUP BY title", $term . "%")->results();

        header("Content-type: application/json");
        print(json_encode(array_column($results, "title"), JSON_PRETTY_PRINT));
        
    } else if ($type == "newPubSource") {
        $results = $DB->assocQuery("SELECT source FROM publicationrecords "
                . "WHERE source LIKE ? GROUP BY source", $term . "%")->results();

        header("Content-type: application/json");
        print(json_encode(array_column($results, "source"), JSON_PRETTY_PRINT));
    }
}