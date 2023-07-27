<?php
/*
Plugin Name: EltaCourier Tracking
Description: A plugin to track shipments using the ELTA Courier API. shortcode: [elta_courier_tracking]
Version: 1.0
Author: Kira
*/

// Shortcode callback function
function elta_courier_tracking_shortcode($atts) {
    // Enqueue the plugin stylesheet
    wp_enqueue_style('tracking-plugin-style', plugins_url('style.css', __FILE__));

    // Check if the form has been submitted
    if (isset($_POST['tracking_number'])) {
        $tracking_number = $_POST['tracking_number'];
        $result = get_tracking_data($tracking_number);

        // Output the tracking data as a table
        ob_start();
        if ($result && isset($result['status']) && $result['status'] == 1) {
            $tracking_data = $result['result'][$tracking_number]['result'];
            if (!empty($tracking_data)) {
                echo '<table>';
                echo '<tr><th>Ημερομηνία</th><th>Χρόνος</th><th>Τοποθεσία</th><th>Κατάσταση</th></tr>';
                foreach ($tracking_data as $item) {
                    echo '<tr>';
                    echo '<td>' . $item['date'] . '</td>';
                    echo '<td>' . $item['time'] . '</td>';
                    echo '<td>' . $item['place'] . '</td>';
                    echo '<td>' . $item['status'] . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '<strong>Δεν υπάρχουν διαθέσιμες πληροφορίες παρακολούθησης για τον κωδικό αυτό</strong> κάντε επαναφόρτιση της σελίδας για να δοκιμάσετε εκ νέου';
            }
        } else {
            echo 'Μη έγκυρος αριθμός παρακολούθησης ή παρουσιάστηκε σφάλμα.';
        }
        return ob_get_clean();
    }

    // Output the tracking form
    ob_start();
    ?>
    <form method="post">
        <label for="tracking_number">Εισαγάγετε τον αριθμό παρακολούθησης:</label>
        <input type="text" name="tracking_number" id="tracking_number" required>
        <br></br>
        <input type="submit" class="submit-elta" value="Εντοπισμός">
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('elta_courier_tracking', 'elta_courier_tracking_shortcode');

// Function to get tracking data using the ELTA Courier API
function get_tracking_data($tracking_number) {
    // You can use Python or PHP libraries to make the API request here
    // For simplicity, let's assume we already have the API response in JSON format
    // Replace $api_response with the actual API response
    $api_response = '{"status":1,"result":{"NZ990003254GR":{"status":1,"result":[{"date":"28-03-2022","time":"11:29","place":"ΠΥΘΑΓΟΡΕΙΟΥ","status":"Αποστολή παραδόθηκε"},{"date":"05-08-2022","time":"14:46","place":"ΚΟΡΥΔΑΛΛΟΥ 2","status":"Αποστολή παραδόθηκε"},{"date":"08-05-2023","time":"09:12","place":"ΟΜΒΡΙΑΚΗ","status":"Αποστολή παραδόθηκε"}]}}}';
    return json_decode($api_response, true);
}
