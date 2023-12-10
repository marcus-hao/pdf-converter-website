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
                // unlink($input_filepath); // Uncomment this line to delete temporary uploads, kept in for demo
            } else {
                echo "Error: Unsupported file type.";
            }
        }
    }

    // Let the user download the files
    if (empty($converted_files)) {
        exit("No files were converted!");
    }
} else {
    echo "No files uploaded!";
}
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Download Converted Files</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="banner">
        <div class="navbar">
            <img src="../img/logo.png" class="logo">
            <ul>
                <li> <a href="/index.html">PDF to Text</a></li>
                <li> <a href="/text_to_pdf.html">Text to PDF</a></li>
            </ul>
        </div>
        <div class="content">
            <h2>Download Converted Files</h2>
            <ul class="download-list">
                <?php foreach ($converted_files as $file_id => $file_data) : ?>
                    <?php $file_extension = $file_data["extension"]; ?>
                    <li>
                        <a href="download.php?file_id=<?php echo $file_id; ?>&extension=<?php echo $file_extension; ?>" class="download-link">
                            Download <?php echo $file_id . '.' . $file_extension; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>

</html>