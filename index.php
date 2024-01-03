<!DOCTYPE html>
<html>
<head>
    <title>Bulk Email Checker</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }

        .loader {
            display: none;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
            margin: 0 auto;
        }

        .valid {
            color: green;
        }

        .invalid {
            color: red;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <form method="post" action="">
        <label for="emails">Email Addresses (comma-separated or new line separated):</label><br>
        <textarea name="emails" rows="4" cols="50" required></textarea><br>
        <input type="submit" value="Check">
        <input type="reset" value="Reset">
    </form>

    <div class="loader" id="loader"></div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get email addresses from the form data and convert them to an array
        $emailsInput = $_POST["emails"];
        $emails = preg_split('/[\s,]+/', $emailsInput);

        // API endpoint URL
        $apiEndpoint = 'http://127.0.0.1:9292';
        //http://127.0.0.1 = localhost

        // Headers
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: xxxyyy' // Replace with actual authorization token
        );

        // Initialize an array to store API responses
        $responses = array();

        // Show the loader while processing
        echo '<script>document.getElementById("loader").style.display = "block";</script>';

        // Iterate through the email addresses and send API requests
        foreach ($emails as $email) {
            // Trim whitespace from email address
            $email = trim($email);

            // API URL for the current email address
            $url = $apiEndpoint . '?email=' . urlencode($email);

            // Initialize cURL session
            $ch = curl_init();

            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Execute cURL session and get the response
            $response = curl_exec($ch);

            // Check for cURL errors
            if (curl_errno($ch)) {
                $responses[$email] = 'Curl error: ' . curl_error($ch);
            } else {
                // Decode JSON response to PHP array
                $data = json_decode($response, true);
                // Store 'Valid' if the response is true, otherwise 'Invalid'
                $status = $data['success'] ? 'Valid' : 'Invalid';
                $responses[$email] = $status;
            }

            // Close cURL session
            curl_close($ch);
        }

        // Hide the loader after processing
        echo '<script>document.getElementById("loader").style.display = "none";</script>';

        // Output the API responses in a table with status coloring
        echo '<table>';
        echo '<tr><th>Email Address</th><th>Status</th></tr>';
        foreach ($responses as $email => $status) {
            $class = $status === 'Valid' ? 'valid' : 'invalid';
            echo '<tr><td>' . htmlspecialchars($email) . '</td><td class="' . $class . '">' . htmlspecialchars($status) . '</td></tr>';
        }
        echo '</table>';
        echo '<button onclick="copyTableData()">Copy Table Data</button>';
    }
    ?>

    <script>
        function copyTableData() {
            const table = document.querySelector('table');
            const range = document.createRange();
            range.selectNode(table);
            window.getSelection().addRange(range);
            document.execCommand('copy');
            window.getSelection().removeAllRanges();
            alert('Table data copied to clipboard!');
        }
    </script>
</body>
</html>
