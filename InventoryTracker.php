<!--Test Oracle file for UBC CPSC304 2018 Winter Term 1
  Created by Jiemin Zhang
  Modified by Simona Radu
  Modified by Jessica Wong (2018-06-22)
  This file shows the very basics of how to execute PHP commands
  on Oracle.
  Specifically, it will drop a table, create a table, insert values
  update values, and then query for values

  IF YOU HAVE A TABLE CALLED "demoTable" IT WILL BE DESTROYED

  The script assumes you already have a server set up
  All OCI commands are commands to the Oracle libraries
  To get the file to work, you must place it somewhere where your
  Apache server can run it, and you must rename it to have a ".php"
  extension.  You must also change the username and password on the
  OCILogon below to be your ORACLE username and password -->

  <html>
    <head>
        <title>CPSC 304 PHP</title>
    </head>

    <body>
        <h2>Reset</h2>
        <p>If you wish to reset the table press on the reset button. If this is the first time you're running this page, you MUST use reset</p>

        <form method="POST" action="InventoryTracker.php">
            <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
            <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
            <p><input type="submit" value="Reset" name="reset"></p>
        </form>

        <hr />

        <h2>Insert Values into Items</h2><!--line 209-->
        <form method="POST" action="InventoryTracker.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertItemRequest" name="insertItemRequest">
            itemID: <input type="text" name="itemIDInsertion"> <br /><br />
            amount: <input type="text" name="amountInsertion"> <br /><br />
            barcode: <input type="text" name="barcodeInsertion"> <br /><br />
            description: <input type="text" name="descriptionInsertion"> <br /><br />


            <input type="submit" value="Insert" name="insertSubmit"></p>
        </form>

        <hr />

        <h2>Update Item Information in Items</h2>
        <p>The values are case sensitive and if you enter in the wrong case, the update statement will not do anything.</p>

        <form method="POST" action="InventoryTracker.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
            ItemId (insert the Item's id you want to modify): <input type="text" name="UpdateItemID"> <br /><br />
            amount: <input type="text" name="UpdateAmount"> <br /><br />
            barcode: <input type="text" name="UpdateBarcode"> <br /><br />
            description: <input type="text" name="UpdateDescription"> <br /><br />

            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        <hr />
        <h2>Delete the Tuples in Items</h2>
        <form method="POST" action="InventoryTracker.php"> <!--refresh page when submitted-->
            <input type="hidden" id="DeleteItemRequest" name="DeleteItemRequest">
            itemID: <input type="text" name="itemIDDelete"> <br /><br />

            <input type="submit" value="Delete" name="deleteSubmit"></p>
        </form>

        <hr />

        <h2>Count the Tuples in Items</h2>
        <form method="GET" action="InventoryTracker.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countTupleRequest" name="countTupleRequest">
            <input type="submit" name="countTuples"></p>
        </form>

        <h2>Select Items with amount below:</h2>
        <form method="POST" action="InventoryTracker.php"> 
            <input type="hidden" id="selectionRequest" name="selectionRequest">
            <input type="text" name="amountSelection"> <br /><br />


            <input type="submit" value="select" name="selectionSubmit"></p>
        </form>

        <hr />

        <h2>Projection on Items</h2>
        <form method="POST" action="InventoryTracker.php"> 
            <input type="hidden" id="projectionRequest" name="projectionRequest">
            itemID: <input type="checkbox" name="itemIDProjection"> <br /><br />
            amount: <input type="checkbox" name="amountProjection"> <br /><br />
            barcode: <input type="checkbox" name="barcodeProjection"> <br /><br />
            description: <input type="checkbox" name="descriptionProjection"> <br /><br />


            <input type="submit" value="project" name="projectionSubmit"></p>
        </form>

        <hr />
        <h2>[Join] find the ItemName, Price, inventoryNum and inventory size it stores</h2>
        <form method="Get" action="InventoryTracker.php"> <!--refresh page when submitted-->
            <input type="hidden" id="JoinItemRequest" name="JoinItemRequest">
            itemID: <input type="text" name="itemIDJoin"> <br /><br />

            <input type="submit" value="Find" name="joinSubmit"></p>
        </form>

        <hr />
        <h2>[Aggregation]find the item with max/min amount</h2>
        <form method="Get" action="InventoryTracker.php"> <!--refresh page when submitted-->
            <input type="hidden" id="aggregationItemRequest" name="aggregationItemRequest">
            <select name="aggregationSelection">
            <option value="MAX">Max</option>
            <option value="MIN">Min</option>
            </select>
            <input type="submit" value="Find" name="AggregationSubmit"></p>
        </form>

        <h2>[Nest]Find those item barcode for which their total item amount is strictly below average:</h2>
        <form method="GET" action="InventoryTracker.php"> 
            <input type="hidden" id="nestedAggregationRequest" name="nestedAggregationRequest">
            <input type="submit" value="Submit" name="nestedAggregationSubmit"></p>
        </form>

        <hr />
        <h2>[Division]find all the inventories store all items</h2>
        <form method="Get" action="InventoryTracker.php"> <!--refresh page when submitted-->
            <input type="hidden" id="DivisionItemRequest" name="DivisionItemRequest">
            <input type="submit" value="Find" name="DivisionSubmit"></p>
        </form>


        <?php
		//this tells the system that it's no longer just parsing html; it's now parsing PHP

        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }

        function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
            //echo "<br>running ".$cmdstr."<br>";
            global $db_conn, $success;

            $statement = OCIParse($db_conn, $cmdstr);
            //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
                echo htmlentities($e['message']);
                $success = False;
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
                echo htmlentities($e['message']);
                $success = False;
            }

			return $statement;
		}

        function executeBoundSQL($cmdstr, $list) {
            /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
		See the sample code below for how this function is used */

			global $db_conn, $success;
			$statement = OCIParse($db_conn, $cmdstr);

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn);
                echo htmlentities($e['message']);
                $success = False;
            }

            foreach ($list as $tuple) {
                foreach ($tuple as $bind => $val) {
                    //echo $val;
                    //echo "<br>".$bind."<br>";
                    OCIBindByName($statement, $bind, $val);
                    unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
				}

                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                    echo htmlentities($e['message']);
                    echo "<br>";
                    $success = False;
                }
            }
        }

        function printResult($result) { //prints results from a select statement
            echo "<br>Retrieved data from table demoTable:<br>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Name</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["NAME"] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function connectToDB() {
            global $db_conn;

            // Your username is ora_(CWL_ID) and the password is a(student number). For example,
			// ora_platypus is the username and a12345678 is the password.
            $db_conn = OCILogon("ora_hongtao7", "a32513350", "dbhost.students.cs.ubc.ca:1522/stu");

            if ($db_conn) {
                debugAlertMessage("Database is Connected");
                return true;
            } else {
                debugAlertMessage("Cannot connect to Database");
                $e = OCI_Error(); // For OCILogon errors pass no handle
                echo htmlentities($e['message']);
                return false;
            }
        }

        function disconnectFromDB() {
            global $db_conn;

            debugAlertMessage("Disconnect from Database");
            OCILogoff($db_conn);
        }

        function handleUpdateRequest() {
            global $db_conn;

            $selectItemID = $_POST['UpdateItemID'];
            $amount = $_POST['UpdateAmount'];
            $barcode = $_POST['UpdateBarcode'];
            $description = $_POST['UpdateDescription'];

            // you need the wrap the old name and new name values with single quotations
            executePlainSQL("UPDATE Items SET amount=$amount, barcode='$barcode', description='$description' WHERE itemID='$selectItemID'");
            // executePlainSQL("UPDATE Items SET name='" . $new_name . "' WHERE name='" . $old_name . "'");
            OCICommit($db_conn);
        }

        function handleDeleteRequest() {
            global $db_conn;

            $itemIDdelete = $_POST['itemIDDelete'];
            executePlainSQL("DELETE FROM Items WHERE itemID='$itemIDdelete'");
            OCICommit($db_conn);
        }

        function handleResetRequest() {
            global $db_conn;
            // Drop old table
            // executePlainSQL("DROP TABLE demoTable");///////////////////////////
            echo"resetting";
            // Create new table
            echo "<br> creating new table...... <br>";
            $sqls = explode(';', file_get_contents('database.sql'));
            foreach ($sqls as $sql) {
                if (strlen($sql) > 0 && strlen(trim($sql)) != 0) {
                    executePlainSQL($sql);
                }
            }
            //executePlainSQL("start bookbiz.sql");
            OCICommit($db_conn);
        }

        function handleInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $itemID = $_POST['itemIDInsertion'];
            $amount = $_POST['amountInsertion'];
            $barcode = $_POST['barcodeInsertion'];
            $description = $_POST['descriptionInsertion'];

            executePlainSQL("insert into Items
            values('$itemID', $amount, '$barcode', '$description')", $alltuples);
            OCICommit($db_conn);
        }

        function handleCountRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT Count(*) FROM Items");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The number of tuples in Items: " . $row[0] . "<br>";
            }
            OCICommit($db_conn);
        }

        function handleJoinRequest() {
            global $db_conn;
            $ItemIDJoin = $_GET['itemIDJoin'];

            $result = executePlainSQL("SELECT ib.itemName, ib.price, inv.inventoryNum, inv.inventorySize FROM Items i, ItemBarcode ib, Store s, Inventory inv 
            WHERE '$ItemIDJoin'=s.itemID AND i.itemID=s.itemID AND s.inventoryNum=inv.inventoryNum AND i.barcode=ib.barcode");
            echo "<table>";
            echo "<tr><th>itemName</th><th>price</th><th>inventoryNum</th><th>inventorySize</th>";
            while (($row = oci_fetch_row($result))) {
                echo "<tr>";
                foreach($row as $value) {
                    echo "<td>" .$value. "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }

        function handleAggregationRequest() {
            global $db_conn;
            $chosen = $_GET['aggregationSelection'];
            $result = executePlainSQL("SELECT itemID, itemName, amount, Items.barcode FROM Items, ItemBarcode 
            WHERE Items.barcode=ItemBarcode.barcode AND Items.amount=(SELECT $chosen(amount) FROM Items)");
            echo "<table>";
            echo "<tr><th>itemID</th><th>itemName</th><th>amount</th><th>barcode</th>";
            while (($row = oci_fetch_row($result))) {
                echo "<tr>";
                foreach($row as $value) {
                    echo "<td>" .$value. "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }

        function handleDivisionRequest() {
            global $db_conn;
            $result = executePlainSQL("SELECT Inventory.inventoryNum FROM Inventory 
            WHERE NOT EXISTS (SELECT Items.itemID FROM Items MINUS 
            (SELECT Items.itemID 
            FROM Items, Store 
            WHERE Items.itemID=Store.itemID AND Store.inventoryNum=Inventory.inventoryNum))");
            echo "<table>";
            echo "<tr><th>InventoryNum</th>";
            while (($row = oci_fetch_row($result))) {
                echo "<tr>";
                foreach($row as $value) {
                    echo "<td>" .$value. "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }

        function handleSelectionRequest() {
            global $db_conn;
            $amount = $_POST['amountSelection'];
        
            $tuples = executePlainSQL("SELECT itemID, amount FROM Items WHERE amount < $amount");
            echo "<table>";
            echo "<tr> <th>itemID</th> <th>amount</th> </tr>";
            while($row = oci_fetch_row($tuples)) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td> " . $value . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
            OCICommit($db_conn);
        }

        function handleProjectionRequest() {
            global $db_conn;
            $projectionList = "";
            echo "<table> <tr>";
            if(isset($_POST['itemIDProjection'])){
                $projectionList = $projectionList . "itemID, ";
                echo "<th>itemID</th>";
            }
            if(isset($_POST['amountProjection'])){
                $projectionList = $projectionList . "amount, ";
                echo "<th>amount</th>";
            }
            if(isset($_POST['barcodeProjection'])){
                $projectionList = $projectionList . "barcode, ";
                echo "<th>barcode</th>";
            }
            if(isset($_POST['descriptionProjection'])){
                $projectionList = $projectionList . "description, ";
                echo "<th>decription</th>";
            }
            $projectionList = substr($projectionList, 0, -2);
            if($projectionList == "") {
                $tuples = executePlainSQL("SELECT * FROM Items");
                echo "<th> itemID </th> <th> amount </th> <th> barcode </th> <th> decription </th>";
            } else {
                $tuples = executePlainSQL("SELECT " . $projectionList . " FROM Items");
            }
            echo "</tr>";
            while($row = oci_fetch_row($tuples)) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td> " . $value . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
            OCICommit($db_conn);
        }

        function handleNestedAggregationRequest() {
            global $db_conn;
            $tuples = executePlainSQL("WITH Temp AS (SELECT I.barcode, SUM(I.amount)sum
                                                    FROM Items I
                                                    GROUP BY I.barcode)
                                        SELECT Temp.barcode, Temp.sum 
                                        FROM Temp
                                        WHERE Temp.sum < (SELECT AVG(Temp.sum)
                                                        FROM Temp)");
            echo "<table>";
            echo "<tr> <th>barcode</th> <th>amount</th> </tr>";
            while($row = oci_fetch_row($tuples)) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td> " . $value . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
            OCICommit($db_conn);
        }

        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('resetTablesRequest', $_POST)) {
                    handleResetRequest();
                } else if (array_key_exists('updateQueryRequest', $_POST)) {
                    handleUpdateRequest();
                } else if (array_key_exists('insertItemRequest', $_POST)) {
                    handleInsertRequest();
                } else if (array_key_exists('DeleteItemRequest', $_POST)) {
                    handleDeleteRequest();
                } else if (array_key_exists('selectionRequest', $_POST)) {
                    handleSelectionRequest();
                } else if (array_key_exists('projectionRequest', $_POST)) {
                    handleProjectionRequest();
                }
                disconnectFromDB();
            }
        }

        // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('countTuples', $_GET)) {
                    handleCountRequest();
                } else if (array_key_exists('JoinItemRequest', $_GET)) {
                    handleJoinRequest();
                } else if (array_key_exists('aggregationItemRequest', $_GET)){
                    handleAggregationRequest();
                } else if (array_key_exists('DivisionItemRequest', $_GET)){
                    handleDivisionRequest();
                } else if (array_key_exists('nestedAggregationSubmit', $_GET)) {
                    handleNestedAggregationRequest();
                }

                disconnectFromDB();
            }
        }

		if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit']) || isset($_POST['deleteSubmit']) || isset($_POST['selectionSubmit'])|| isset($_POST['projectionSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['countTupleRequest']) || isset($_GET['joinSubmit']) || isset($_GET['AggregationSubmit']) || isset($_GET['DivisionSubmit']) || isset($_GET['nestedAggregationRequest'])) {
            handleGETRequest();
        }
		?>
	</body>
</html>
