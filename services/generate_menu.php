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
            
            Quantity and serving rules:
            - quantities in inventory represent the amount currently available, but package sizes may not equal individual servings
            - do not assume that one bag, one tub, one jar, or one pound equals one serving
            - use known conversion rules when they are provided
            - bread rule: one loaf makes 12 sandwiches
            - for individually portioned items such as juice boxes, yogurt cups, string cheese, cookies, peach cups, or individually packaged chips, use one item per recipient unless the inventory name indicates otherwise
            - for bulk items such as bags of carrots, celery, grapes by the pound, meat by the pound, peanut butter jars, jelly jars, or large tubs of snacks, estimate a reasonable amount needed for the group rather than assigning one package per recipient
            - when estimating bulk quantities, use practical whole or decimal quantities and avoid obviously excessive amounts
            - if an exact serving conversion cannot be determined from the inventory name, make a conservative estimate

            When creating a sandwich:
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
            - calculate realistic ingredient quantities needed to serve {$estimated_recipients} recipients per day
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
                - one sweet item
                - one salty item
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
            //$full_prompt = "Reply with only the word hello.";

            $full_prompt = $system_prompt . "Current inventory:" . $inventory_json;
            
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
            //echo "<p>HTTP Status: " . htmlspecialchars((string)$http_code) . "</p>";
            //echo "<pre>";
            //echo htmlspecialchars($response);
            //echo "</pre>";

            // decode gemini resp
            $response_data = json_decode($response, true);

            if ($response_data === null) {
                die("Error: Could not decode Gemini response.");
            }

            // get the menu json text
            $menu_json = $response_data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$menu_json) {
                die("Error: Gemini did not return menu data.");
            }

            // decode
            $menu_data = json_decode($menu_json, true);

            if ($menu_data === null) {
                die("Error: Could not decode generated menu.");
            }

            // temporary test
            //echo "<pre>";
            //print_r($menu_data);
            //echo "</pre>";
            //exit();

            // prep menu insert
            $insert_menu = $pdo->prepare("
                insert into menu
                (menu_date, meal_name, estimated_recipients)
                values
                (:menu_date, :meal_name, :estimated_recipients)
            ");

            // prep menu item insert
            $insert_menu_item = $pdo->prepare("
                insert into menu_items
                (menu_id, item_id, category, menu_item_name, req_quant, unit)
                values
                (:menu_id, :item_id, :category, :menu_item_name, :req_quant, :unit)
            ");

            // get first and last date of generated week
            $week_start = $menu_data['days'][0]['menu_date'];
            $week_end = $menu_data['days'][count($menu_data['days']) - 1]['menu_date'];

            // remove old menu if making new one for the same week
            $delete_week = $pdo->prepare("
                delete from menu
                where menu_date between :week_start and :week_end
            ");

            $delete_week->execute([
                'week_start' => $week_start,
                'week_end' => $week_end
            ]);

            // go through each day
            foreach ($menu_data['days'] as $day) {

                // insert the daily menu
                $insert_menu->execute([
                    'menu_date' => $day['menu_date'],
                    'meal_name' => $day['meal_name'],
                    'estimated_recipients' => $estimated_recipients
                ]);

                // get the id of the menu that was just inserted
                $menu_id = $pdo->lastInsertId();
                
                // prepare ingredient insert
                $insert_ingredient = $pdo->prepare("
                    insert into menu_ingredients
                    (menu_item_id, item_id, ingredient_name, req_quant, unit)
                    values
                    (:menu_item_id, :item_id, :ingredient_name, :req_quant, :unit)
                ");

                // go through each menu item for that day
                foreach ($day['items'] as $item) {

                    $item_id = null;

                    // if the menu item has only one inventory item
                    // link it directly to that inventory item
                    if (
                        count($item['ingredients']) === 1 &&
                        isset($item['ingredients'][0]['item_id'])
                    ) {
                        $item_id = $item['ingredients'][0]['item_id'];
                    }

                    // insert finished menu item
                    $insert_menu_item->execute([
                        'menu_id' => $menu_id,
                        'item_id' => $item_id,
                        'category' => $item['category'],
                        'menu_item_name' => $item['menu_item_name'],
                        'req_quant' => $estimated_recipients,
                        'unit' => 'servings'
                    ]);

                    // get the id of the menu item just inserted
                    $menu_item_id = $pdo->lastInsertId();

                    // insert each ingredient for this menu item
                    foreach ($item['ingredients'] as $ingredient) {

                        $insert_ingredient->execute([
                            'menu_item_id' => $menu_item_id,
                            'item_id' => $ingredient['item_id'],
                            'ingredient_name' => $ingredient['ingredient_name'],
                            'req_quant' => $ingredient['required_quantity'],
                            'unit' => $ingredient['unit']
                        ]);
                    }
                }
            }

            // go back with the gen week
            header(
                "Location: ../pages/menu.php?week_start=" .
                urlencode($week_start)
            );
            exit();

        } catch (PDOException $e) {
            die("Error: Could not load inventory data.");
        }

    } else {
        header("Location: ../pages/menu.php");
        exit();
    }
?>