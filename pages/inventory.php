<?php 
session_start();

if(!isset($_SESSION['volunteer_id'])){
    header("Location: ../index.php");
    exit();
}

require_once '../services/db_connect.php';

$stmt = null;
$error_msg = null;

try {
    // get the inventory
    $stmt = $pdo->prepare("select item_id, item_name, quantity, expire_date, shelf_stable from inventory order by item_name asc");
    $stmt->execute();
} catch (PDOException $e) {
    $error_msg = "Could not load inventory data.";
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title> Manage Inventory</title>
    <!-- style to be moved later-->
     <style>

       .modal-overlay {
            display: none; 
            position: fixed; 
            width: 100%; 
            height: 100%;
        }

        .modal-window {
            background: #fff;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px; 
            height: 600px;
            overflow-y: auto;
            padding: 20px;
            border: 1px solid #333;
        }

        .modal-close-btn {
            position: absolute;
            right: 15px;
            top: 10px;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="../CSS/styles.css">
    <link rel="stylesheet" type="text/css" href="../CSS/table.css">
</head>
<body>

<div class="layout">

    <main class="main-content">
        <?php include '../includes/header.php'; ?>

        <div class="workspace-container">
            <div class="manage-workspace">
                
                <div class="recipient-actions-grid">
                    <button type="button" class="action-btn-trigger" data-target="addInventoryModal">
                        <div class="action-item-box">
                            <span class="action-icon">+</span><br>
                            <span class="action-label">Add Item</span>
                        </div>
                    </button>
                    
                    <button type="button" class="action-btn-trigger" data-target="deleteInventoryModal">
                        <div class="action-item-box">
                            <span class="action-icon">-</span><br>
                            <span class="action-label">Remove Item</span>
                        </div>
                    </button>
                </div>
                
                <div class="search-container">
                    <label for="tableSearch">Search Inventory: </label>
                    <input type="text" id="tableSearch" placeholder="Type an item name..." onkeyup="filterTable()" style="padding: 6px; width: 250px; margin-bottom: 15px;">
                </div>

                <div class="recipent-table-container">
                    <?php 
                        if (isset($error_msg)) {
                            echo "<p class='error-message'>" . htmlspecialchars($error_msg) . "</p>";
                        }
                    ?>

                    <table class="recipient-table" id="inventoryTable">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Expiration Date</th>
                                <th>Shelf Stable</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $inventoryRows = [];
                                if ($stmt) {
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $inventoryRows[] = $row;

                                        // make shelf stable yes for true and no for false
                                        $stable_text = $row['shelf_stable'] ? "Yes" : "No";
                                        //if no expiration date
                                        $display_date = !empty($row['expire_date']) ? htmlspecialchars($row['expire_date']) : "<em>None listed</em>";

                                        echo "<tr class='table-row'>";
                                        echo "<td class='cell-fname'>" . htmlspecialchars($row['item_name']) . "</td>";
                                        echo "<td class='cell-age'>" . htmlspecialchars($row['quantity']) . "</td>";
                                        echo "<td class='cell-allergies'>" . $display_date . "</td>";
                                        echo "<td class='cell-contact'>" . $stable_text . "</td>";
                                        
                                        // the edit action
                                        echo "<td class='cell-action'>";
                                        echo "<button type='button' onclick='openEditModal(" . json_encode($row) . ")'>Edit</button>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </main>

    <?php include '../includes/sidebar.php'; ?>

</div>

<div id="addInventoryModal" class="modal-overlay">
    <div class="modal-window">
        <button type="button" class="modal-close-btn">&times;</button>
        <h3 class="modal-title">Add New Inventory Item</h3>
        
        <form action="../services/register_inventory.php" method="POST" class="modal-form">
            <div class="form-options">
                <label>Item Name *</label><br>
                <input type="text" name="item_name" required>
            </div>
            <br>
            <div class="form-options">
                <label>Quantity *</label><br>
                <input type="number" name="quantity" required>
            </div>
            <br>
            <div class="form-options">
                <label>Expiration Date (Optional)</label><br>
                <input type="date" name="expire_date">
            </div>
            <br>
            <div class="form-options checkbox-container">
                <input type="checkbox" name="shelf_stable" id="add_shelf_stable" value="1">
                <label for="add_shelf_stable">This item is shelf stable</label>
            </div>
            <br>
            <button type="submit">Add to Inventory</button>
        </form>
    </div>
</div>

<div id="deleteInventoryModal" class="modal-overlay">
    <div class="modal-window extended-width">
        <button type="button" class="modal-close-btn">&times;</button>
        <h3 class="modal-title">Remove Inventory Item</h3>

        <?php 
            if (isset($error_msg)) {
                echo "<p class='error-message'>" . $error_msg . "</p>";
            }
        ?>

        <table class="recipient-display-table" border="1">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Expiration Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach ($inventoryRows as $row) {
                        echo "<tr class='table-row'>";
                        echo "<td>" . htmlspecialchars($row['item_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['expire_date'] ?? 'N/A') . "</td>";
                        echo "<td style='text-align:center;'>";
                        echo "<form action='../services/delete_inventory.php' method='POST' onsubmit=\"return confirm('Delete this item?');\">";
                        echo "<input type='hidden' name='item_id' value='" . $row['item_id'] . "'>";
                        echo "<button type='submit' class='table-delete-btn'>Delete</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div id="editInventoryModal" class="modal-overlay">
    <div class="modal-window">
        <button type="button" class="modal-close-btn">&times;</button>
        <h3 class="modal-title">Edit Inventory Details</h3>
        
        <form action="../services/edit_inventory.php" method="POST" class="modal-form">
            <input type="hidden" name="item_id" id="edit_item_id">

            <div class="form-options">
                <label>Item Name *</label><br>
                <input type="text" name="item_name" id="edit_item_name" required>
            </div>
            <br>
            <div class="form-options">
                <label>Current Quantity Status *</label><br>
                <input type="number" name="quantity" id="edit_quantity" required>
            </div>
            <br>
            <div class="form-options">
                <label>Expiration Date</label><br>
                <input type="date" name="expire_date" id="edit_expire_date">
            </div>
            <br>
            <div class="form-options checkbox-container">
                <input type="checkbox" name="shelf_stable" id="edit_shelf_stable" value="1">
                <label for="edit_shelf_stable">This item is shelf stable</label>
            </div>
            <br>
            <button type="submit">Save Details</button>
        </form>
    </div>
</div>

<script>
    // search bar for inventory
    function filterTable() {
        const input = document.getElementById('tableSearch');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('inventoryTable');
        const rows = table.getElementsByClassName('table-row');

        for (let i = 0; i < rows.length; i++) {
            // get text content
            const rowText = rows[i].textContent || rows[i].innerText;

            // check if match otherwise hide
            if (rowText.toLowerCase().indexOf(filter) > -1) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }

    //<!-- MODAL script-->
    function openEditModal(itemData) {
        //edit modal part: to inject data based on what the user clicks on-
        //close other modals if open
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.style.display = 'none';
        });

        //fill fields
        document.getElementById('edit_item_id').value = itemData.item_id;
        document.getElementById('edit_item_name').value = itemData.item_name;
        document.getElementById('edit_quantity').value = itemData.quantity;
        document.getElementById('edit_expire_date').value = itemData.expire_date ?? '';
        
        // Handle boolean checkbox state accurately (1 = checked, 0 = unchecked)
        document.getElementById('edit_shelf_stable').checked = parseInt(itemData.shelf_stable) === 1;

        //redering
        document.getElementById('editInventoryModal').style.display = 'block';
    }

    //general modal things. what to do when clicking where stuff
    // open modal when trigger button is clicked
    //find all buttons to open first
    document.querySelectorAll('.action-btn-trigger').forEach(button => {
        button.addEventListener('click', () => {
            //close all modals before opening
            document.querySelectorAll('.modal-overlay').forEach(modal => {
                modal.style.display = 'none';//hide
                const form = modal.querySelector('form');
                if (form) { form.reset(); } //reset fields
            });
            // show the targeted modal by id
            const targetId = button.getAttribute('data-target');
            document.getElementById(targetId).style.display = 'block';
        });
    });

    // close modal when close button is clicked
    document.querySelectorAll('.modal-close-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const modal = e.target.closest('.modal-overlay');
            modal.style.display = 'none'; //hide
            const form = modal.querySelector('form');
            if (form) { form.reset(); } //clear any form data when closing
        });
    });

    // close modal when clicking outside the modal content
    window.addEventListener('click', (e) => {
        // check if they clicked outside the modal (in the overlay)
        if (e.target.classList.contains('modal-overlay')) {
            e.target.style.display = 'none'; //hide
            const form = e.target.querySelector('form');
            if (form) { form.reset(); } //clear
        }
    });
</script>

</body>
</html>