<?php
if (isset($_FILES["files"])) {
    $files = $_FILES["files"];

    foreach ($files["tmp_name"] as $key => $tmp_name) {
        $file_id = uniqid();
        $input_filepath = "../uploads/" . $file_id;
        $output_filepath = "../results/" . $file_id;

        // Determine the file type
        $file_type = mime_content_type($tmp_name);
        if ($file_type === "application/pdf" || $file_type === "text/plain") {
            if (move_uploaded_file($tmp_name, $input_filepath)) {
                // Choose between txt2pdf and pdf2txt
                if ($file_type === "application/pdf") {
                    $output_filepath .= ".txt";
                    $command = "java -jar ../java/QuickSwitch.jar -t pdf2txt " . $input_filepath . " " . $output_filepath;
                } else if ($file_type === "text/plain") {
                    $output_filepath .= ".pdf";
                    $command = "java -jar ../java/QuickSwitch.jar -t txt2pdf " . $input_filepath . " " . $output_filepath;
                }

                exec($command, $output, $return_code);

                if ($return_code === 0) {
                    $converted_files[$file_id] = array(
                        "filepath" => $output_filepath,
                        "extension" => pathinfo($output_filepath, PATHINFO_EXTENSION)
                    );
                } else {
                    implode("\n", $output);
                }
                // unlink($input_filepath);    // Uncomment this line to delete temporary uploads, kept in for demo
            } else {
                echo "Error: Unsupported file type.";
            }
        }
    }

    // Let the user download the files
    if (!empty($converted_files)) {
        echo "<h2>Download converted files</h2>";
        foreach ($converted_files as $file_id => $file_data) {
            // Get the file extension
            $file_extension = $file_data["extension"];
            echo "<a href='download.php?file_id=$file_id&extension=$file_extension'>Download $file_id.$file_extension</a><br>";
        }
    } else {
        echo "No files were converted.";
    }
} else {
    echo "No files uploaded!";
}
