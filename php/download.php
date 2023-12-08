<?php
if (isset($_GET["file_id"]) && isset($_GET["extension"])) {
    $file_id = $_GET["file_id"];
    $extension = $_GET["extension"];
    if ($extension === "pdf") {
        $output_filepath = "../results/" . $file_id . ".pdf";
    } else if ($extension === "txt") {
        $output_filepath = "../results/" . $file_id . ".txt";
    } else {
        exit("An error occured. Unsupported file extension.");
    }

    if (file_exists($output_filepath)) {
        // Set headers to force download
        header("Content-Description: File Transfer");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=" . basename($output_filepath));
        header("Expires: 0");
        header("Cache-Control: must-revalidate");
        header("Pragma: public");
        header("Content-Length: " . filesize($output_filepath));
        readfile($output_filepath);
    } else {
        echo "File not found.";
    }
} else {
    echo "Invalid download request.";
}
