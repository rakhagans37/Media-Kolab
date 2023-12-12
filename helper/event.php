<?php
require_once __DIR__ . "/getConnectionMsqli.php";
require_once __DIR__ . "/getConnection.php";
require_once __DIR__ . "/cloudinary.php";
require_once __DIR__ . "/hash.php";

function setNewEvent($eventId, $eventTitle, $eventContent, $eventUrl, $dateRelease, $dateEvent, $videoUrl, $categoryId, $linkGoogleMap, $editorId, $image)
{
    $conn = getConnection();
    // Menyimpan data ke database
    $sql = "INSERT INTO tb_event (event_id, event_title, event_content, event_url, date_release, date_event, 
        video_url, category_id, editor_id, link_google_map, image_url) 
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $request = $conn->prepare($sql);
    // Bind the values to the placeholders
    $request->bindParam(1, $eventId);
    $request->bindParam(2, $eventTitle);
    $request->bindParam(3, $eventContent);
    $request->bindParam(4, $eventUrl);
    $request->bindParam(5, $dateRelease);
    $request->bindParam(6, $dateEvent);
    $request->bindParam(7, $videoUrl);
    $request->bindParam(8, $categoryId);
    $request->bindParam(9, $editorId);
    $request->bindParam(10, $linkGoogleMap);
    $request->bindParam(11, $image);
    // Execute the prepared statement
    $request->execute();

    $conn = null;
}

function getEventByEditor($editorId)
{
    $conn = getConnection();

    $sql = "SELECT * FROM tb_event WHERE editor_id = :editorId";

    $request = $conn->prepare($sql);
    $request->bindParam("editorId", $editorId);
    $request->execute();

    if ($result = $request->fetchAll()) {
        $conn = null;
        return $result;
    } else {
        $conn = null;
        return array();
    }
}

function getSearchEventByEditor($editorId, $searchParam)
{
    $conn = getConnection();

    $sql = "SELECT * FROM tb_event WHERE editor_id = :editorId AND event_title LIKE '%$searchParam%'";

    $request = $conn->prepare($sql);
    $request->bindParam("editorId", $editorId);
    $request->execute();

    if ($result = $request->fetchAll()) {
        $conn = null;
        return $result;
    } else {
        $conn = null;
        return array();
    }
}

function deleteEvent($eventId)
{
    $conn = getConnection();

    $sql = "DELETE FROM tb_event WHERE event_id = ?";
    $request = $conn->prepare($sql);
    $request->bindParam(1, $eventId);
    $request->execute();

    $conn = null;
}

function deleteEventTag($eventId)
{
    $conn = getConnection();

    $sqlDelete = "DELETE FROM tb_event_tag WHERE event_id = ?";
    $request = $conn->prepare($sqlDelete);
    $request->bindParam(1, $eventId);
    $request->execute();

    $conn = null;
}

function updateEventTitle($eventId, $newTitle)
{
    $conn = getConnection();

    $sqlUpdate = "UPDATE tb_event SET 
					event_title = :updateTitle
					WHERE event_id = :eventId";

    $request = $conn->prepare($sqlUpdate);
    $request->bindParam("updateTitle", $newTitle);
    $request->bindParam("eventId", $eventId);
    $request->execute();

    $conn = null;
}

function updateEventCategory($eventId, $newCategory)
{
    $conn = getConnection();

    $sqlUpdate = "UPDATE tb_event SET 
					category_id = :categoryId
					WHERE event_id = :eventId";

    $request = $conn->prepare($sqlUpdate);
    $request->bindParam("categoryId", $newCategory);
    $request->bindParam("eventId", $eventId);
    $request->execute();

    $conn = null;
}

function setEventImageToNull($eventId)
{
    $conn = getConnection();
    $newImage = null;

    $sqlUpdate = "UPDATE tb_event SET 
					image_url = :newImage
					WHERE event_id = :eventId";

    $request = $conn->prepare($sqlUpdate);
    $request->bindParam("newImage", $newImage);
    $request->bindParam("eventId", $eventId);
    $request->execute();

    $conn = null;
}

function deleteEventImage($eventId)
{
    $conn = getConnection();

    $sqlGetImage = "SELECT image_url FROM tb_event WHERE event_id = :eventId";

    $request = $conn->prepare($sqlGetImage);
    $request->bindParam("eventId", $eventId);
    $request->execute();
    $result = $request->fetch();

    if (!is_null($result['image_url'])) {
        deleteImageNews($result['image_url']);

        // Set image to null
        setEventImageToNull($eventId);
    }

    $conn = null;
}
