<?php
    function makeApiRequest($url, $method = 'GET', $data = null, $headers = []) {
        // Initialize cURL session
        $curl = curl_init();

        // Set common options
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true  // Enable SSL verification in production
        ];

        // Set method-specific options
        if ($method === 'POST') {
            $options[CURLOPT_POST] = true;
            if ($data) {
                $options[CURLOPT_POSTFIELDS] = is_array($data) ? http_build_query($data) : $data;
            }
        }

        // Set custom headers if provided
        if (!empty($headers)) {
            $options[CURLOPT_HTTPHEADER] = $headers;
        }

        // Apply all options
        curl_setopt_array($curl, $options);

        // Execute request
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // Close cURL session
        curl_close($curl);

        // Handle errors
        if ($err) {
            throw new Exception("cURL Error: " . $err);
        }

        // Check HTTP status code
        if ($httpCode >= 400) {
            throw new Exception("HTTP Error: " . $httpCode);
        }

        // Return decoded response
        return json_decode($response, true);
    }