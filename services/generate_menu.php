<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //check for amount of recipients
    require_once 'db_connect.php';
        $estimated_recipients = isset($_POST['estimated_recipients'])
        ? (int)$_POST['estimated_recipients']
        : 0;

    if ($estimated_recipients <= 0) {
        die("Error: Please enter a valid estimated recipient total.");
    }

    try {
        $stmt = $pdo->prepare("select item_id, item_name, quantity, expire_date, shelf_stable, category
            from inventory
            where quantity > 0
            order by expire_date is null, expire_date asc, item_name asc 
        ");//shelf stable stuff to be used last thats why order by expire is null


        $stmt->execute();
        $inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //if no inventory items are available, return an error
        if (empty($inventory)) {
            die("Error: There are no available inventory items.");
        }

        //temporary test
        //echo "<pre>";
        //print_r($inventory);
        //echo "</pre>";

        // convert inventory to json
        $inventory_json = json_encode($inventory, JSON_PRETTY_PRINT);
        //if any conversion error
        if ($inventory_json === false) {
            die("Error: Could not convert inventory data to JSON.");
        }

        // temporary test
        //echo "<pre>";
        //echo htmlspecialchars($inventory_json);
        //echo "</pre>";

        //instructions for gemini
        $system_prompt = "
            You are creating a Monday through Friday lunch menu for a summer lunch program.
            Use the provided inventory to create one lunch menu for each weekday.
            The estimated number of recipients per day is {$estimated_recipients}.
            Food categories represent ingredients, not complete meals.
            
            sandwich
            - ingredients used to build the main sandwich.
            - examples: bread, tortillas, turkey, ham, cheese, peanut butter, jelly.

            fruit
            - served as a side.

            vegetable
            - served as a side.

            dairy
            - served as a side (examples: yogurt, cheese stick).

            drink
            - beverage.

            sweet
            - dessert.

            salty
            - chips, crackers, pretzels, etc.

            When creating a sandwich:
            - each bread loaf can make 12 sandwiches.
            - combine compatible ingredients from the sandwich category.
            - for example, bread + ham + cheese = ham and cheese sandwich.
            - bread + peanut butter + jelly = peanut butter and jelly sandwich.
            - bread + turkey + cheese = turkey and cheese sandwich.

            If one ingredient is missing, you may substitute another compatible ingredient from inventory.
            If no suitable combination exists, recommend the minimum number of new sandwich ingredients needed.

            Requirements:
            - inventory quantities represent the total available for the entire Monday through Friday week
            - track the total use of each inventory item across all five days
            - do not treat the available quantity as available again for each new day
            - never exceed the total available quantity across the entire week
            - calculate ingredient quantities for {$estimated_recipients} recipients per day
            - prioritize using items with the closest expiration dates first
            - use shelf stable items whenever they are appropriate
            - do not use more than the available quantity of any inventory item
            - maximize the use of existing inventory before suggesting new not in stock items
            - if a required food category cannot be filled from the inventory, you may recommend a new item to purchase
            - recommend only the minimum number of new items necessary for {$estimated_recipients} recipients per day
            - each day should include:
                - one sandwich
                - one fruit
                - one vegetable
                - one dairy item
                - one drink
                - one sweet or salty side
            - create balanced menus that avoid repeating the exact same meal every day whenever possible

            - for ingredients already in inventory, return the exact item_id and exact item_name provided
            - for ingredients that must be purchased, return item_id as null

            Return JSON using exactly this structure:
            {
                \"days\": [
                    {
                        \"menu_date\": \"YYYY-MM-DD\",
                        \"meal_name\": \"descriptive lunch name\",
                        \"items\": [
                            {
                                \"category\": \"sandwich\",
                                \"menu_item_name\": \"Ham and Cheese Sandwich\",
                                \"ingredients\": [
                                    {
                                        \"item_id\": 1,
                                        \"ingredient_name\": \"Whole Wheat Bread (Loaves)\",
                                        \"required_quantity\": 3,
                                        \"unit\": \"loaves\"
                                    }
                                ]
                            },
                            {
                                \"category\": \"fruit\",
                                \"menu_item_name\": \"Apples\",
                                \"ingredients\": [
                                    {
                                        \"item_id\": 8,
                                        \"ingredient_name\": \"Apples\",
                                        \"required_quantity\": {$estimated_recipients},
                                        \"unit\": \"pieces\"
                                    }
                                ]
                            }
                        ]
                    }
                ]
            }

            Return exactly five day objects.

            Return only valid JSON.
            Do not include markdown, comments, code fences, or explanations.
            ";
            
            //test
            $full_prompt = "Reply with only the word hello.";

            //$full_prompt = $system_prompt . "Current inventory:" . $inventory_json;
            
            // get gemini api key from render
            $api_key = getenv('GEMINI_API_KEY');
            // if any api key errors
            if (!$api_key) {
                die("Error: Gemini API key is not configured.");
            }

            // gemini url
            $api_url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent";

            // build request payload
            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $full_prompt
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json'
                ]
            ];

            $payload_json = json_encode($payload);

            if ($payload_json === false) {
                die("Error: Could not build Gemini request.");
            }

            // start curl request
            $ch = curl_init($api_url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'x-goog-api-key: ' . $api_key
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload_json);

            // send request
            $response = curl_exec($ch);

            // if curl errors
            if ($response === false) {
                $curl_error = curl_error($ch);
                curl_close($ch);

                die("Error: Gemini request failed. " . htmlspecialchars($curl_error));
            }

            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            // temporary test
            echo "<p>HTTP Status: " . htmlspecialchars((string)$http_code) . "</p>";
            echo "<pre>";
            echo htmlspecialchars($response);
            echo "</pre>";

        } catch (PDOException $e) {
            die("Error: Could not load inventory data.");
        }

    } else {
        header("Location: ../pages/menu.php");
        exit();
    }
?>