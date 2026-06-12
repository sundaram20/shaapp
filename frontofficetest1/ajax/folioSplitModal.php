<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Split Modal with Multi-select Filters</title>
    <style>
        /* Base font size for better scaling and general readability */
        
#myCstmSplitModal {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 12px;
    line-height: 1.5;
    color: #333;
}

#myCstmSplitModal * { font-size: 12px;
    box-sizing: border-box;
}
        /* Basic styling for the trigger button to make it visible */
       

        /* cstmSplitModal Overlay - Takes full viewport */
        .cstmSplitModal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw; /* Full viewport width */
            height: 100vh; /* Full viewport height */
            background-color: rgba(0, 0, 0, 0.6); /* Slightly lighter overlay */
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease; /* Faster transition */
            z-index: 9999999; /* Ensure it's above other content */
        }

        /* State when the drawer is active */
        .cstmSplitModal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* cstmSplitModal Container - Now taking 100% width/height of overlay, with max-height */
        .cstmSplitModal-container {
            background-color: #ffffff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); /* Slightly softer shadow */
            width: 100%; /* Take full width of parent (overlay) */
            height: 100%; /* Take full height of parent (overlay) */
            max-height: 700px; /* Still respecting this max height */
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Ensure content within is clipped */
            transform: translateY(15px); /* Smaller initial transform for animation */
            opacity: 0;
            transition: transform 0.3s ease, opacity 0.3s ease; /* Faster transition */
            position: relative; /* For close button positioning */
        }

        /* Animation for the container when active */
        .cstmSplitModal-overlay.active .cstmSplitModal-container {
            transform: translateY(0);
            opacity: 1;
        }

        /* Close Button within the drawer */
        .cstmSplitModal-close-button {
            position: absolute;
            top: 12px; /* Adjusted position */
            right: 12px; /* Adjusted position */
            background: none;
            border: none;
            font-size: 1.6rem; /* Slightly smaller close button size */
            color: #888;
            cursor: pointer;
            padding: 6px; /* Reduced padding for easier clicking */
            line-height: 1;
            transition: color 0.2s ease;
            z-index: 1001; /* Ensure close button is clickable */
        }

        .cstmSplitModal-close-button:hover {
            color: #333;
        }

        /* Drawer Header */
        .cstmSplitModal-header {
            flex-shrink: 0;
            padding: 18px 25px; /* Reduced padding */
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 55px; /* Reduced min height */
        }

        .cstmSplitModal-header h2 {
            margin: 0;
            font-size: 1.25rem; /* Slightly smaller header title size */
            color: #343a40;
            font-weight: 600;
        }

        /* Drawer Body */
        .cstmSplitModal-body {
            flex: 1;
            padding: 25px; /* Reduced padding */
            padding-top: 10px; /* Reduced padding */
            overflow-y: auto;
            color: #495057;
            display: flex; /* Use flex to stack filter and table */
            flex-direction: column;
        }

        /* Drawer Footer */
        .cstmSplitModal-footer {
            flex-shrink: 0;
            padding: 12px 25px; /* Reduced padding */
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px; /* Reduced gap */
            min-height: 60px; /* Reduced min height */
        }

        .cstmSplitModal-footer button {
            padding: 8px 18px; /* Reduced button padding */
            border: none;
            border-radius: 5px; /* Slightly smaller border radius */
            font-size: 0.95rem; /* Slightly smaller button font size */
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); /* Softer shadow */
        }

        .cstmSplitModal-footer button:hover {
            transform: translateY(-1px);
        }
        .cstmSplitModal-footer button:active {
            transform: translateY(0);
        }

        .cstmSplitModal-footer .btn-cancel {
            background-color: #e9ecef;
            color: #555;
        }

        .cstmSplitModal-footer .btn-cancel:hover {
            background-color: #dee2e6;
        }

        .cstmSplitModal-footer .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .cstmSplitModal-footer .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Global HTML/Body Overflow Control */
        html.cstmSplitModal-active, body.cstmSplitModal-active {
            overflow: hidden !important; /* Prevents background scrolling */
        }

        /* Filter Container Styles */
        .cstmSplitModal-filter-container {
            display: flex;
            flex-wrap: wrap; /* Allow wrapping on smaller screens */
            gap: 10px 20px; /* Space between filter elements */
            
            border-bottom: 1px solid #eee; /* Separator from table */
            padding-bottom: 15px; /* Added padding below filters */
        }

        .cstmSplitModal-filter-container .filter-group {
            flex: 0; /* Allow filter groups to grow */
            min-width: 25%; /* Minimum width for each filter group */
            position: relative; /* For dropdown positioning */
        }

        .cstmSplitModal-filter-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }

        /* Style for standard select inputs (now for Split Folio As) */
        .cstmSplitModal-filter-container select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            background-color: #fff;
            font-size: 1rem;
            height: 38px; /* Match desired height */
            box-sizing: border-box; /* Include padding and border in the element's total width and height */
            appearance: none; /* Remove default browser styling for select */
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20256%20256%22%3E%3Cpath%20fill%3D%22%23495057%22%20d%3D%22M128%2C192a8%2C8%2C0%2C0%2C1-5.66-2.34l-80-80a8%2C8%2C0%2C0%2C1%2C11.32-11.32L128%2C173.31l74.34-74.33a8%2C8%2C0%2C0%2C1%2C11.32%2C11.32l-80%2C80A8%2C8%2C0%2C0%2C1%2C128%2C192Z%22%3E%3C%2Fpath%3E%3C%2Fsvg%3E'); /* Custom arrow */
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 12px;
        }

        /* Style for select when it's focused */
        .cstmSplitModal-filter-container select:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Explanation text styles */
        .explanation-text {
            font-size: 0.85rem;
            color: #666;
            margin-top: 5px; /* Space between select and text */
            line-height: 1.4;
            max-width: 300px; /* Control width of the text */
        }
        /* Custom Multi-select Styles */
        .custom-multiselect {
            position: relative;
            width: 100%;
        }

        .select-box {
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 8px 12px;
            min-height: 38px;
            background-color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 1rem;
            box-sizing: border-box;
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20256%20256%22%3E%3Cpath%20fill%3D%22%23495057%22%20d%3D%22M128%2C192a8%2C8%2C0%2C0%2C1-5.66-2.34l-80-80a8%2C8%2C0%2C0%2C1%2C11.32-11.32L128%2C173.31l74.34-74.33a8%2C8%2C0%2C0%2C1%2C11.32%2C11.32l-80%2C80A8%2C8%2C0%2C0%2C1%2C128%2C192Z%22%3E%3C%2Fpath%3E%3C%2Fsvg%3E'); /* Custom arrow */
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 12px;
        }
        .select-box.active {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .dropdown-content {
            display: none;
            position: absolute;
            width: 100%;
            background-color: #fff;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
            border: 1px solid #ced4da;
            border-radius: 5px;
            margin-top: 5px;
            z-index: 100;
            max-height: 200px; /* Limit height for scroll */
            overflow-y: auto;
            box-sizing: border-box;
        }
        .dropdown-content.show {
            display: block;
        }

        .search-input {
            width: calc(100% - 20px);
            padding: 8px 10px;
            margin: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 0.95rem;
        }
        .search-input:focus {
            border-color: #007bff;
            outline: none;
        }

        .options-container {
            padding: 0 10px 10px 10px;
        }

        .option-item {
            padding: 8px 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        .option-item:hover {
            background-color: #f2f2f2;
        }
        .option-item input[type="checkbox"] {
            margin-right: 10px;
            transform: scale(1.1);
        }
        .option-item label {
            margin-bottom: 0;
            font-weight: normal;
            flex-grow: 1; /* Allow label to take available space */
            cursor: pointer;
        }

        /* Styles for the table */
        .cstmSplitModal-body table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0; /* No top margin as filter container handles space */
            font-size: 0.9rem; /* Slightly smaller for table content */
            color: #333;
            table-layout: fixed; /* Ensures columns are fixed width */
        }

        .cstmSplitModal-body th,
        .cstmSplitModal-body td {
            padding: 5px 12px; /* Reduced padding for table cells */
            border: 1px solid #e0e0e0;
            text-align: left;
            overflow: hidden; /* Hide overflow for fixed layout */
            text-overflow: ellipsis; /* Add ellipsis for overflowed text */
            white-space: nowrap; /* Prevent wrapping in cells */
        }

        .cstmSplitModal-body th {
            background-color: #f2f2f2;
            font-weight: 600;
            color: #555;
        }

        .cstmSplitModal-body tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .cstmSplitModal-body tr:hover {
            background-color: #f0f0f0;
        }

        /* Style for the select all checkbox in table header */
        .cstmSplitModal-body th #selectAllCheck {
            margin-right: 0; /* Remove default margin */
            vertical-align: middle;
            transform: scale(1.1);
            cursor: pointer;
        }

        .cstmSplitModal-body input[type="checkbox"] {
            transform: scale(1.05); /* Very slight adjustment */
            margin-right: 5px;
            vertical-align: middle;
        }

        .cstmSplitModal-body .total-col {
            font-weight: 700; /* Keep bold */
            color: inherit; /* Use default text color, not blue */
            text-align: right; /* Often totals are right-aligned */
        }
         /* Tariff */
        .cstmSplitModal-body td:nth-child(7), /* Tax */
        .cstmSplitModal-body td:nth-child(8) { /* Total */
            text-align: right; /* Align numerical columns to the right */
        }
 		.cstmSplitModal-body td:nth-child(6) { /* Total */
            text-align: left; /* Align numerical columns to the right */
        }


        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .cstmSplitModal-container {
                width: 100%; /* Now it's full width here too */
                height: 100%; /* Now it's full height here too */
                max-height: unset; /* Remove fixed max-height on smaller devices to ensure full height */
                border-radius: 0; /* Remove border-radius on small screens for true full screen */
            }

            .cstmSplitModal-header,
            .cstmSplitModal-body,
            .cstmSplitModal-footer {
                padding: 15px 20px;
            }

            .cstmSplitModal-header h2 {
                font-size: 1.15rem;
            }

            .cstmSplitModal-close-button {
                top: 10px;
                right: 10px;
                font-size: 1.5rem;
            }

            .cstmSplitModal-footer button {
                padding: 8px 15px;
                font-size: 0.9rem;
            }

            .cstmSplitModal-body th,
            .cstmSplitModal-body td {
                padding: 8px 10px;
                font-size: 0.85rem;
            }

            .cstmSplitModal-filter-container {
                flex-direction: column; /* Stack filters vertically */
                gap: 15px;
            }
            .cstmSplitModal-filter-container .filter-group {
                min-width: unset; /* No min-width when stacked */
                width: 100%;
            }
            .custom-multiselect .dropdown-content {
                position: static; /* Prevent dropdowns from overlapping other elements when stacked */
                width: auto;
                margin-top: 10px;
            }
        }

        @media (max-width: 480px) {
            .cstmSplitModal-container {
                height: 100vh;
                width: 100vw;
                border-radius: 0;
            }

            .cstmSplitModal-header,
            .cstmSplitModal-body,
            .cstmSplitModal-footer {
                padding: 10px 15px;
            }

            .cstmSplitModal-header h2 {
                font-size: 1rem;
            }

            .cstmSplitModal-close-button {
                top: 8px;
                right: 8px;
                font-size: 1.3rem;
            }

            .cstmSplitModal-footer {
                flex-direction: column;
                gap: 8px;
            }

            .cstmSplitModal-footer button {
                width: 100%;
                padding: 10px;
                font-size: 0.85rem;
            }

            .cstmSplitModal-body th,
            .cstmSplitModal-body td {
                padding: 6px 8px;
                font-size: 0.8rem;
            }
        }
		
		
		.split-folio-loader-overlay {
  display: none;
  position: fixed;
  top: 0; left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(255, 255, 255, 0.8);
  z-index: 9999;
  display: flex;
  justify-content: center;
  align-items: center;
}

.split-folio-loader-box {
  text-align: center;
  padding: 30px;
  background-color: #ffffff;
  border-radius: 10px;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
}

.spinner {
  width: 40px;
  height: 40px;
  margin: 0 auto 10px;
  border: 4px solid #ccc;
  border-top: 4px solid #007bff; /* Bootstrap blue */
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

.loader-text {
  font-size: 16px;
  font-weight: 500;
  color: #333;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
		.split-folio-loader-overlay {
  display: none;
  
}
    </style>
	
</head>
<body>

    <!-- <button id="openCstmSplitModalBtn">Open Split Modal</button> -->

<div id="myCstmSplitModal" class="cstmSplitModal-overlay">
	
	
	<div id="splitFolioLoader" class="split-folio-loader-overlay">
  <div class="split-folio-loader-box">
    <div class="spinner"></div>
    <div class="loader-text">Processing folio split...</div>
  </div>
</div>
	
    <div class="cstmSplitModal-container">
        <button class="cstmSplitModal-close-button" id="cancelCstmSplitModalBtn" aria-label="Close custom split modal">&times;</button>

        <div class="cstmSplitModal-header">
            <h2>Split Folio</h2>
        </div><?php // echo '<pre>';print_r($folioArray);print_r($_REQUEST);echo '</pre>';?>

        <div class="cstmSplitModal-body">
            <div class="cstmSplitModal-filter-container">
            
            
            <div class="filter-group">
                    <label for="splitFolioSelect">Split Folio As:</label>
                    <select id="splitFolioSelect" style="width: 100%;">
                        <option value="1" selected>Select</option>
                        <optgroup label="Extras Folio">
                            <option value="2">Split Roomwise </option>
                            <option value="3">Split Groupwise</option>
                        </optgroup>
                        <optgroup label="Main Folio">
                            <option value="4">Split Roomwise</option>
                            <option value="5">Split Groupwise</option>
                        </optgroup>
                    </select>
                     <div class="explanation-text" id="splitFolioExplanation">
                        </div>
                </div>
            
                <div class="filter-group">
                    <label for="roomFilter">Filter by Room Type:</label>
                    <div class="custom-multiselect" id="roomTypeMultiSelect">
                        <div class="select-box" tabindex="0">
                            <span class="selected-items-display">All Room Types</span>
                        </div>
                        <div class="dropdown-content">
                            <input type="text" class="search-input" placeholder="Search room types..." data-filter-target="roomTypeOptions">
                            <div class="options-container" id="roomTypeOptions">
                                </div>
                        </div>
                    </div>
                </div>
                <div class="filter-group">
                    <label for="sourceFilter">Filter by Source:</label>
                    <div class="custom-multiselect" id="sourceMultiSelect">
                        <div class="select-box" tabindex="0">
                            <span class="selected-items-display">All Sources</span>
                        </div>
                        <div class="dropdown-content">
                            <input type="text" class="search-input" placeholder="Search sources..." data-filter-target="sourceOptions">
                            <div class="options-container" id="sourceOptions">
                                </div>
                        </div>
                    </div>
                </div>
                
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;"><input type="checkbox" id="selectAllCheck"></th>
                        <th style="width: 25%;">Room Type/No</th>						
                        <th style="width: 20%;">Ref No#</th>
                        <th style="width: 20%;">Ref Date</th>
                        <th style="width: 15%;">Source</th>
                        <th style="width: 8%;">Tariff</th>
                        <th style="width: 8%;">Tax</th>
                        <th style="width: 10%;">Total</th>
                    </tr>
                </thead>
                <tbody id="tableSplitList">
                    </tbody>
            </table>
        </div>

        <div class="cstmSplitModal-footer">
            <button class="btn-cancel" id="cancelCstmSplitModalBtn">Cancel</button>
            <button class="btn-primary" id="confirmCstmSplitModalBtn">Save Changes</button>
        </div>
    </div>
</div>
	<?php 
	/*$folioArrayTransformed = [];
foreach ($folioArray as $roomName => $roomData) {
    foreach ($roomData as $row) {
        $folioArrayTransformed[] = [
            'id_table' => $row['id_table'],
            'table' => $row['Table'],
            'roomType' => $row['RoomType'],
            'referenceNo' => $row['InvoiceNo'],
            'referenceDate' => date('d-m-Y', strtotime($row['dated'])),
            'source' => $row['source'],
            'tariff' => (float)$row['tariff'],
            'tax' => (float)$row['tax'],
            'total' => (float)$row['Total'],
        ];
    }
}*/
	
	$folioArrayTransformed = [];

foreach ($folioArray as $roomName => $roomData) {
    foreach ($roomData as $row) {
        // Remove rows where is_room_owner = 1 and source = "Tariff"
        if (isset($row['is_room_owner']) && $row['is_room_owner'] == 1 && $row['source'] === 'Tariff') {
            continue;
        }

        $folioArrayTransformed[] = [
            'id_table' => $row['id_table'],
            'table' => $row['Table'],
            'roomType' => $row['RoomType'],
            'referenceNo' => $row['InvoiceNo'],
            'referenceDate' => date('d-m-Y', strtotime($row['dated'])),
            'source' => $row['source'],
            'tariff' => (float)$row['tariff'],
            'tax' => (float)$row['tax'],
            'total' => (float)$row['Total'],
			'id_mst_room_no_allocation' => $row['id_mst_room_no_allocation'],
			 'guest_name' => $row['guest_name'],
			'is_room_owner' => $row['is_room_owner'],
			 
        ];
    }
}
	?>
	<script>
    window.dummyTableData = <?php echo json_encode($folioArrayTransformed); ?>;

window.hotelSplitConfig = {
    id_fo_bill: <?php echo (int)$id_fo_bill; ?>,
    id_mst_guest: <?php echo (int)$id_mst_guest; ?>,
    id_resevation: <?php echo (int)$id_resevation; ?>,
    id_owner_room: <?php echo (int)$id_owner_room; ?>
};

</script>
	<script>
    // Get references to elements
    const openCstmSplitModalBtn = document.getElementById('openCstmSplitModalBtn');
    const myCstmSplitModal = document.getElementById('myCstmSplitModal');
    const closeCstmSplitModalBtn = document.querySelector('.cstmSplitModal-close-button');
    const cancelCstmSplitModalBtn = document.getElementById('cancelCstmSplitModalBtn');
    const confirmCstmSplitModalBtn = document.getElementById('confirmCstmSplitModalBtn');
    const tableSplitList = document.getElementById('tableSplitList');

    // Get references to custom multi-select elements
    const roomTypeMultiSelect = document.getElementById('roomTypeMultiSelect');
    const roomTypeSelectBox = roomTypeMultiSelect.querySelector('.select-box');
    const roomTypeDropdownContent = roomTypeMultiSelect.querySelector('.dropdown-content');
    const roomTypeSearchInput = roomTypeMultiSelect.querySelector('.search-input');
    //const roomTypeOptionsContainer = roomTypeMultiSelect.querySelector('.options-container');
    const roomTypeDisplay = roomTypeMultiSelect.querySelector('.selected-items-display');

    const sourceMultiSelect = document.getElementById('sourceMultiSelect');
    const sourceSelectBox = sourceMultiSelect.querySelector('.select-box');
    const sourceDropdownContent = sourceMultiSelect.querySelector('.dropdown-content');
    const sourceSearchInput = sourceMultiSelect.querySelector('.search-input');
    const sourceOptions = sourceMultiSelect.querySelector('.options-container');
    const sourceDisplay = sourceMultiSelect.querySelector('.selected-items-display');

    // Get reference to the Split Folio As select element
    const splitFolioSelect = document.getElementById('splitFolioSelect');
    // Get reference to the explanation text div
    const splitFolioExplanation = document.getElementById('splitFolioExplanation');


    // Get reference to the "Select All" checkbox in the table header
    const selectAllCheck = document.getElementById('selectAllCheck');

    // Get references to the html and body elements to control overflow
    const htmlElement = document.documentElement;
    const bodyElement = document.body;
</script>

<script> 
// This PHP block generates the dummyTableData array in JavaScript.
// Ensure your $folioArray is correctly populated in the PHP script
// that renders this HTML.

</script>

<script>
    // --- All Helper Functions (populateTable, setupFilters, open/close modal, etc.) ---
    // These functions define WHAT to do.
    
    function populateTable(data) {
        const tableSplitList = document.getElementById('tableSplitList');
        if (!tableSplitList) return;

        tableSplitList.innerHTML = '';
        data.forEach(row => {
            const tr = document.createElement('tr');
            tr.dataset.id_table = row.id_table;
            tr.dataset.table = row.table;
			 tr.dataset.is_room_owner = String(row.is_room_owner); 
			tr.dataset.id_mst_room_no_allocation = row.id_mst_room_no_allocation;
            tr.innerHTML = `
                <td><input type="checkbox" class="row-checkbox"></td>
                <td>${row.roomType}</td>				 
                <td>${row.referenceNo}</td>
                <td>${row.referenceDate}</td>
                <td>${row.source}</td>
                <td style="text-align: right;">${row.tariff.toFixed(2)}</td>
                <td style="text-align: right;">${row.tax.toFixed(2)}</td>
                <td class="total-col">${row.total.toFixed(2)}</td>
            `;
            tableSplitList.appendChild(tr);
        });
        updateSelectAllCheckbox();
    }

    function getUniqueRoomTypesWithNumbers(data) { 
        const uniqueSet = new Set();
        data.forEach(item => {
            uniqueSet.add(item.roomType);
        });
        return Array.from(uniqueSet).sort();
    }

    function populateMultiSelect(container, data, type) {
        if (!container) return;
        container.innerHTML = '';
        data.forEach(item => {
            const optionDiv = document.createElement('div');
            optionDiv.classList.add('option-item');
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.id = `${type}-${item.replace(/[^a-zA-Z0-9]/g, '-')}`;
            checkbox.value = item;
            checkbox.name = `${type}-option`;
            const label = document.createElement('label');
            label.htmlFor = checkbox.id;
            label.textContent = item;
            optionDiv.appendChild(checkbox);
            optionDiv.appendChild(label);
            container.appendChild(optionDiv);
        });
    }

    function updateSelectBoxText(type) { 
        let selectedValues = [];
        let displayElement;
        let optionsContainer;
        if (type === 'roomType') {
            optionsContainer = document.getElementById('roomTypeOptions');
            displayElement = document.getElementById('roomTypeDisplay');
        } else if (type === 'source') {
            optionsContainer = document.getElementById('sourceOptions');
            displayElement = document.getElementById('sourceDisplay');
        }
        if (!optionsContainer || !displayElement) return;
        const checkboxes = optionsContainer.querySelectorAll('input[type="checkbox"]:checked');
        checkboxes.forEach(checkbox => selectedValues.push(checkbox.value));
        if (selectedValues.length === 0) {
            displayElement.textContent = `All ${type === 'roomType' ? 'Room Types' : 'Sources'}`;
        } else if (selectedValues.length === 1) {
            displayElement.textContent = selectedValues[0];
        } else {
            displayElement.textContent = `${selectedValues.length} selected`;
        }
    }
   
    function setupFilters() { 
        // Assuming dummyTableData is globally available when this is called
        const roomTypeOptionsContainer = document.getElementById('roomTypeOptions');
        const sourceOptions = document.getElementById('sourceOptions');
        const uniqueRoomTypes = getUniqueRoomTypesWithNumbers(dummyTableData);
        populateMultiSelect(roomTypeOptionsContainer, uniqueRoomTypes, 'roomType');
        updateSelectBoxText('roomType');
        const uniqueSources = [...new Set(dummyTableData.map(item => item.source))].sort();
        populateMultiSelect(sourceOptions, uniqueSources, 'source');
        updateSelectBoxText('source');
    }

 /*   function filterTable() {  
        const roomTypeOptionsContainer = document.getElementById('roomTypeOptions');
        const sourceOptions = document.getElementById('sourceOptions');
        const tableSplitList = document.getElementById('tableSplitList');
        if (!roomTypeOptionsContainer || !sourceOptions || !tableSplitList) return;
        
        const selectedRoomTypes = Array.from(roomTypeOptionsContainer.querySelectorAll('input[type="checkbox"]:checked')).map(cb => cb.value);
        const selectedSources = Array.from(sourceOptions.querySelectorAll('input[type="checkbox"]:checked')).map(cb => cb.value);
        const rows = tableSplitList.querySelectorAll('tr');
        rows.forEach(row => {
            const fullRoomTypeAndNumber = row.children[1].textContent;
            const source = row.children[4].textContent;
            const rowCheckbox = row.querySelector('.row-checkbox');
            const roomMatch = selectedRoomTypes.length === 0 || selectedRoomTypes.includes(fullRoomTypeAndNumber);
            const sourceMatch = selectedSources.length === 0 || selectedSources.includes(source);
            if (roomMatch && sourceMatch) {
                row.style.display = '';
                if (rowCheckbox) { rowCheckbox.checked = true; }
            } else {
                row.style.display = 'none';
                if (rowCheckbox) { rowCheckbox.checked = false; }
            }
        });
		    
       //updateSelectAllCheckbox();
    }*/
	function filterTable() {
    const roomTypeOptionsContainer = document.getElementById('roomTypeOptions');
    const sourceOptions = document.getElementById('sourceOptions');
    const tableSplitList = document.getElementById('tableSplitList');
    if (!roomTypeOptionsContainer || !sourceOptions || !tableSplitList) return;

    const selectedRoomTypes = Array.from(roomTypeOptionsContainer.querySelectorAll('input[type="checkbox"]:checked')).map(cb => cb.value);
    const selectedSources = Array.from(sourceOptions.querySelectorAll('input[type="checkbox"]:checked')).map(cb => cb.value);

    const rows = tableSplitList.querySelectorAll('tr');

    rows.forEach(row => {
        if (row.style.display === 'none') {
            const checkbox = row.querySelector('.row-checkbox');
            if (checkbox) checkbox.checked = false;  // ✅ Uncheck hidden row
            return;
        }

        const fullRoomTypeAndNumber = row.children[1].textContent.trim();
        const source = row.children[4].textContent.trim();
        const rowCheckbox = row.querySelector('.row-checkbox');

        const roomMatch = selectedRoomTypes.length === 0 || selectedRoomTypes.includes(fullRoomTypeAndNumber);
        const sourceMatch = selectedSources.length === 0 || selectedSources.includes(source);

        if (roomMatch && sourceMatch) {
            row.style.display = '';
            if (rowCheckbox) rowCheckbox.checked = true;
        } else {
            row.style.display = 'none';
            if (rowCheckbox) rowCheckbox.checked = false;
        }
    });
}
     
    function toggleDropdown(multiSelectElement, selectBoxElement, dropdownContentElement, searchInputElement) { 
        const isShowing = dropdownContentElement.classList.contains('show');
		
            //selectBoxElement.classList.add('active');
		//dropdownContentElement.classList.add('show');
		
		// alert(isShowing);
        document.querySelectorAll('.dropdown-content.show').forEach(content => {
            if (content !== dropdownContentElement) {
                content.classList.remove('show');
                content.closest('.custom-multiselect').querySelector('.select-box').classList.remove('active');
            }
        });
        if (isShowing) { 
           // dropdownContentElement.classList.remove('show');
           // selectBoxElement.classList.remove('active');
        } else { 
            dropdownContentElement.classList.add('show');
            selectBoxElement.classList.add('active');
            if (searchInputElement) searchInputElement.focus();
        }
    }

    function applySearchFilter(optionsContainer, searchTerm) {
        if (!optionsContainer) return;
        const items = optionsContainer.querySelectorAll('.option-item');
        const lowerCaseSearchTerm = searchTerm.toLowerCase();
        items.forEach(item => {
            const labelText = item.querySelector('label').textContent.toLowerCase();
            item.style.display = labelText.includes(lowerCaseSearchTerm) ? 'flex' : 'none';
        });
    }
    
    function toggleAllCheckboxes(checked) {
        const tableSplitList = document.getElementById('tableSplitList');
        if (!tableSplitList) return;
        const rowCheckboxes = tableSplitList.querySelectorAll('.row-checkbox');
        rowCheckboxes.forEach(checkbox => {
            if (checkbox.closest('tr').style.display !== 'none') {
                checkbox.checked = checked;
            }
        });
    }

    function updateSelectAllCheckbox() {
        const tableSplitList = document.getElementById('tableSplitList');
        const selectAllCheck = document.getElementById('selectAllCheck');
        if (!tableSplitList || !selectAllCheck) return;
        const visibleRowCheckboxes = tableSplitList.querySelectorAll('tr:not([style*="display:none"]) .row-checkbox');
        const checkedVisibleCheckboxes = tableSplitList.querySelectorAll('tr:not([style*="display:none"]) .row-checkbox:checked');
        if (visibleRowCheckboxes.length > 0 && visibleRowCheckboxes.length === checkedVisibleCheckboxes.length) {
            selectAllCheck.checked = true;
            selectAllCheck.indeterminate = false;
        } else if (checkedVisibleCheckboxes.length > 0) {
            selectAllCheck.checked = false;
            selectAllCheck.indeterminate = true;
        } else {
            selectAllCheck.checked = false;
            selectAllCheck.indeterminate = false;
        }
    }
    
    function updateSplitFolioExplanation() {
        const splitFolioSelect = document.getElementById('splitFolioSelect');
        const splitFolioExplanation = document.getElementById('splitFolioExplanation');
        if (!splitFolioSelect || !splitFolioExplanation) return;
        const selectedOption = splitFolioSelect.value;
        let explanation = '';
        switch (selectedOption) {

            case 'Roomwise Split': explanation = 'Splits the folio based on individual rooms, creating a separate folio for each room selected.'; break;
            case 'Groupwise Split': explanation = 'Splits the folio based on the group, consolidating all selected items for the group into one folio.'; break;
            case 'Room New Main': explanation = 'Creates a new main folio for each selected room, moving the charges to these new folios.'; break;
            case 'Group New Main': explanation = 'Creates a single new main folio for the entire selected group, transferring all charges to it.'; break;
            default: explanation = 'Please select an option to see its explanation.'; break;
        }
        splitFolioExplanation.textContent = explanation;
    }
    
    function openCstmSplitModal() {
        const myCstmSplitModal = document.getElementById('myCstmSplitModal');
        const htmlElement = document.documentElement;
        const bodyElement = document.body;
        myCstmSplitModal.classList.add('active');
        htmlElement.classList.add('cstmSplitModal-active');
        bodyElement.classList.add('cstmSplitModal-active');
        // `dummyTableData` must be available in the scope where this function is called
        populateTable(dummyTableData); 
        setupFilters();
        const splitFolioSelect = document.getElementById('splitFolioSelect');
        if (splitFolioSelect) splitFolioSelect.value = "1";
        updateSplitFolioExplanation();
        filterTable();
        updateSelectAllCheckbox();
    }
    
    function closeCstmSplitModal() {
        const myCstmSplitModal = document.getElementById('myCstmSplitModal');
        const htmlElement = document.documentElement;
        const bodyElement = document.body;
        const tableSplitList = document.getElementById('tableSplitList');
        const selectAllCheck = document.getElementById('selectAllCheck');
        const splitFolioExplanation = document.getElementById('splitFolioExplanation');

        myCstmSplitModal.classList.remove('active');
        htmlElement.classList.remove('cstmSplitModal-active');
        bodyElement.classList.remove('cstmSplitModal-active');
        if (tableSplitList) tableSplitList.innerHTML = '';
        document.querySelectorAll('.dropdown-content.show').forEach(content => {
            content.classList.remove('show');
            content.closest('.custom-multiselect').querySelector('.select-box').classList.remove('active');
        });
        if (selectAllCheck) {
            selectAllCheck.checked = false;
            selectAllCheck.indeterminate = false;
        }
        if (splitFolioExplanation) splitFolioExplanation.textContent = '';
		
		
		
		
    }
let isSplitting = false;

document.addEventListener('click', function(event) {
    if (event.target.id === 'confirmCstmSplitModalBtn') {
        if (isSplitting) return; // 🔒 Prevent double-click

        isSplitting = true; // 🔒 Set lock
        const loader = document.getElementById('splitFolioLoader');
        const confirmBtn = document.getElementById('confirmCstmSplitModalBtn');

        loader.style.display = 'flex'; // 👁 Show loader
        confirmBtn.disabled = true;   // 🔒 Disable button

        const selectedSplitOption = document.getElementById('splitFolioSelect').value;
        const tableSplitList = document.getElementById('tableSplitList');

        const selectedTableRows = Array.from(tableSplitList.querySelectorAll('.row-checkbox:checked')).map(cb => {
            const row = cb.closest('tr');
            return {
                id_table: row.dataset.id_table,
                table: row.dataset.table,
                roomType: row.children[1].textContent.trim(),
                referenceNo: row.children[2].textContent.trim(),
                referenceDate: row.children[3].textContent.trim(),
                source: row.children[4].textContent.trim(),
                tariff: parseFloat(row.children[5].textContent),
                tax: parseFloat(row.children[6].textContent),
                total: parseFloat(row.children[7].textContent),
                id_mst_room_no_allocation: row.dataset.id_mst_room_no_allocation
            };
        });

        if (selectedTableRows.length === 0) {
            alert('Please select at least one item to split.');
            resetSplitUI();
            return;
        }

        if (selectedSplitOption === "1") {
            alert('Please select a valid split option.');
            resetSplitUI();
            return;
        }

        fetch('ajax/ajaxSplitMultiFolio.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
				splitOption: selectedSplitOption,
        id_mst_guest: window.hotelSplitConfig.id_mst_guest,
        id_resevation: window.hotelSplitConfig.id_resevation,
        id_fo_bill: window.hotelSplitConfig.id_fo_bill,
        id_owner_room: window.hotelSplitConfig.id_owner_room,
        selectedRows: selectedTableRows			
               
            })
        })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            resetSplitUI();

            if (data.status === 'success' && Array.isArray(data.folios)) {
                const folioList = data.folios.map(folio => `• ${folio.mdoc_no}`).join('\n');
                alert(`✅ Folio split successfully!\n\n📄 Total Folios Created: ${data.folioCount}\n${folioList}`);
            } else {
                alert('Folio split completed, but no folio numbers were returned.');
            }

            closeCstmSplitModal();
            Optional: window.location.reload();
        })
        .catch(error => {
            resetSplitUI();
            console.error('Error:', error);
            alert('An error occurred while splitting the folio. Please try again.');
        });
    }
});

// Helper function to reset UI state
function resetSplitUI() {
    isSplitting = false;
    const loader = document.getElementById('splitFolioLoader');
    const confirmBtn = document.getElementById('confirmCstmSplitModalBtn');

    loader.style.display = 'none'; // 🔁 Hide loader
    confirmBtn.disabled = false;   // 🔓 Enable button
}
    // ===================================================================
    // --- DELEGATED EVENT LISTENERS (AJAX-SAFE) ---
    // ===================================================================
    // These listeners are attached once to the document and handle all events
    // from elements that might be created or replaced by AJAX.

    document.addEventListener('click', function(event) {

        if (event.target.id === 'openCstmSplitModalBtn') {
            openCstmSplitModal();
        }

        if (event.target.id === 'closeCstmSplitModalBtn' || event.target.id === 'cancelCstmSplitModalBtn') {
            closeCstmSplitModal();
        }

        if (event.target.id === 'myCstmSplitModal' && event.target.classList.contains('active')) {
            closeCstmSplitModal();
        }

        const selectBox = event.target.closest('.select-box');
        if (selectBox) {
            event.stopPropagation();
            const multiSelect = selectBox.closest('.custom-multiselect');
            const dropdownContent = multiSelect.querySelector('.dropdown-content');
            const searchInput = multiSelect.querySelector('.search-input');
            toggleDropdown(multiSelect, selectBox, dropdownContent, searchInput);
            return; 
        }

        if (!event.target.closest('.custom-multiselect')) {
            document.querySelectorAll('.dropdown-content.show').forEach(openDropdown => {
                const multiSelect = openDropdown.closest('.custom-multiselect');
                const box = multiSelect.querySelector('.select-box');
                openDropdown.classList.remove('show');
                box.classList.remove('active');
            });
        }
	
	document.getElementById('splitFolioSelect').addEventListener('change', function () {
    updateSplitFolioExplanation();

    const selectedVal = this.value;
    const rows = document.querySelectorAll('#tableSplitList tr');

    const roomTariffMap = {};
    rows.forEach(row => {
        const room = row.children[1]?.textContent?.trim();
        const source = row.children[4]?.textContent?.trim().toLowerCase();
        if (!roomTariffMap[room]) {
            roomTariffMap[room] = false;
        }
        if (source === 'tariff') {
            roomTariffMap[room] = true;
        }
    });

   /* rows.forEach(row => {
        const room = row.children[1]?.textContent?.trim();
        const source = row.children[4]?.textContent?.trim().toLowerCase();
        const isRoomOwner = row.dataset.is_room_owner === '1';

      /*  if (selectedVal === '2' || selectedVal === '3') {
            // Extras Folio: hide tariff rows
            row.style.display = (source === 'tariff') ? 'none' : '';
        } else if (selectedVal === '4' || selectedVal === '5') {
            // Main Folio: show only tariff rows that are NOT owned
            if (roomTariffMap[room] && isRoomOwner==='true') {
                row.style.display = '';alert('4444');
            } else {  alert('5555');
                row.style.display = 'none';
            }
        } else { alert(isRoomOwner);
            row.style.display = '';
        }*/
		/* if (selectedVal === '2' || selectedVal === '3') {
            row.style.display = (source === 'tariff') ? 'none' : '';
        } else if (selectedVal === '4' || selectedVal === '5') { alert(isRoomOwner);
            row.style.display = roomTariffMap[room] ? '' : 'none';
        } else {
            row.style.display = '';
        }
    });*/
		rows.forEach(row => {
    const room = row.children[1]?.textContent?.trim();
    const source = row.children[4]?.textContent?.trim().toLowerCase();
    const isRoomOwner = row.dataset.is_room_owner === '1'; // boolean
    const isTariffRow = source === 'tariff';

    if (selectedVal === '2' || selectedVal === '3') {
        // Extras Folio: hide tariff rows
        row.style.display = isTariffRow ? 'none' : '';
    } else if (selectedVal === '4' || selectedVal === '5') {
        // Main Folio: show only tariff rows that are NOT owned
        if (roomTariffMap[room] && !isRoomOwner) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    } else {
        // Show all rows
        row.style.display = '';
    }
});
		

    // Refresh filters based on visible rows
    const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
    const visibleRoomTypes = [...new Set(visibleRows.map(row => row.children[1].textContent.trim()))].sort();
    const visibleSources = [...new Set(visibleRows.map(row => row.children[4].textContent.trim()))].sort();

    const roomTypeOptionsContainer = document.getElementById('roomTypeOptions');
    const sourceOptionsContainer = document.getElementById('sourceOptions');

    populateMultiSelect(roomTypeOptionsContainer, visibleRoomTypes, 'roomType');
    populateMultiSelect(sourceOptionsContainer, visibleSources, 'source');

    roomTypeOptionsContainer.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = true);
    sourceOptionsContainer.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = true);

    updateSelectBoxText('roomType');
    updateSelectBoxText('source');

    filterTable();
});

    
       /* if (event.target.id === 'confirmCstmSplitModalBtn') {
			 const loader = document.getElementById('splitFolioLoader');
			loader.style.display = 'flex'; // 👈 Show loader
             const selectedSplitOption = document.getElementById('splitFolioSelect').value;
             const tableSplitList = document.getElementById('tableSplitList');
             const selectedTableRows = Array.from(tableSplitList.querySelectorAll('.row-checkbox:checked')).map(cb => {
                 const row = cb.closest('tr');
                 return {
                     id_table: row.dataset.id_table, table: row.dataset.table,
                     roomType: row.children[1].textContent, referenceNo: row.children[2].textContent,
                     referenceDate: row.children[3].textContent, source: row.children[4].textContent,
                     tariff: parseFloat(row.children[5].textContent), tax: parseFloat(row.children[6].textContent),
                     total: parseFloat(row.children[7].textContent),
					 id_mst_room_no_allocation: row.dataset.id_mst_room_no_allocation, 
                 };
             });

             if (selectedTableRows.length === 0) {
                 alert('Please select at least one item to split.');
                 return;
             }
             if (selectedSplitOption === "1") {
                 alert('Please select a valid split option.');
                 return;
             }
             
             console.log('Selected Split Folio Option:', selectedSplitOption);
             console.log('Selected Table Rows for Splitting:', selectedTableRows);
             
             // --- FULL FETCH CALL RE-INTEGRATED ---
             fetch('ajax/ajaxSplitMultiFolio.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    splitOption: selectedSplitOption,
                    id_mst_guest: <?php echo (int)$id_mst_guest; ?>,
                    id_resevation: <?php echo (int)$id_resevation; ?>,
                    id_fo_bill: <?php echo (int)$id_fo_bill; ?>,
                    id_owner_room: <?php echo (int)$id_owner_room; ?>,
                    selectedRows: selectedTableRows
                })
             })
             .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
             })
             .then(data => {
                console.log('Success:', data);
               // alert('Folio split successfully!');
				  loader.style.display = 'none'; // 👈 Hide loader
    if (data.status === 'success' && Array.isArray(data.folios)) {
        let folioList = data.folios.map(folio => `• ${folio.mdoc_no}`).join('\n');
        alert(`✅ Folio split successfully!\n\n📄 Total Folios Created: ${data.folioCount}\n${folioList}`);
    } else {
        alert('Folio split completed, but no folio numbers were returned.');
    }
                closeCstmSplitModal();
				// window.location.reload(); 
                // Consider reloading the page or the specific AJAX content here
                // e.g., window.location.reload(); 
             })
             .catch((error) => {
				  loader.style.display = 'none'; // 👈 Hide loader
                console.error('Error:', error);
                alert('An error occurred while splitting the folio. Please try again.');
             });
        }*/
    });

    document.addEventListener('change', function(event) {
        
        const filterContainer = event.target.closest('#roomTypeOptions, #sourceOptions');
        if (filterContainer) {
            filterTable();
            updateSelectBoxText(filterContainer.id === 'roomTypeOptions' ? 'roomType' : 'source');
        }

        if (event.target.classList.contains('row-checkbox')) {
            updateSelectAllCheckbox();
        }
        
        if (event.target.id === 'selectAllCheck') {
            toggleAllCheckboxes(event.target.checked);
        }
        
        if (event.target.id === 'splitFolioSelect') {
            updateSplitFolioExplanation();
        }
    });

    document.addEventListener('keyup', function(event) { 
        if (event.target.id === 'roomTypeSearchInput') {
            applySearchFilter(document.getElementById('roomTypeOptions'), event.target.value);
        }
        if (event.target.id === 'sourceSearchInput') {
            applySearchFilter(document.getElementById('sourceOptions'), event.target.value);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && document.getElementById('myCstmSplitModal')?.classList.contains('active')) {
            closeCstmSplitModal();
        }
    });

</script>
	
</body>
</html>