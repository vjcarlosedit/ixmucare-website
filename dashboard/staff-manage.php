<?php include 'main/header.php'; 
session_start();
if (!isset($_SESSION['isLoggedIn'])) {
    echo '<script>window.location="login.php"</script>';
}


?>
<body>
    <section class="body">
        <?php include 'main/top-bar.php'; ?>
        <div class="inner-wrapper">
            <?php include 'main/left-bar.php'; ?>
            <section role="main" class="content-body">
                <header class="page-header">
                    <h2>Staff List</h2>
                </header>

                <section class="panel">
                    <header class="panel-heading">
                        
                        <h2 class="panel-title">All Staff</h2>
                    </header>
                    <div class="panel-body">

						<table class="table table-bordered table-striped mb-none" id="datatable-tabletools" data-swf-path="assets/vendor/jquery-datatables/extras/TableTools/swf/.swf">
				
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>First Name</th>
                                    <th>Email</th>
                                    <th>Mobile No</th>
                                    <th>Job Title</th>
                                    <th>Address</th>
                                    <th>Date of Birth</th>
                                    <th>Status</th>
                                    <th class="hidden-phone">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include 'dbCon.php';
                                $con = connect();
                                $sql = "SELECT * FROM `staff`;";
                                $result = $con->query($sql);
                                $count = 1;
                                foreach ($result as $r) {
                                ?>
                                <tr class="gradeX">
                                    <td class="center hidden-phone"><?php echo $count; ?></td>
                                    <td><?php echo $r['firstName']; ?></td>

                                    <td><?php echo $r['email']; ?></td>
                                    <td><?php echo $r['mobileNo']; ?></td>
                                    <td><?php echo $r['jobTitle']; ?></td>
                                    <td><?php echo $r['addr']; ?></td>
                                    <td><?php echo $r['dob']; ?></td>
                                    <td id="status-<?php echo $r['empId']; ?>">
                                        <button class="btn 
                                            <?php 
                                                if ($r['status'] == 1) {
                                                    echo 'btn-success';
                                                } elseif ($r['status'] == 9) {
                                                    echo 'btn-danger';
                                                } elseif ($r['status'] == 0) {
                                                    echo 'btn-warning';
                                                } 
                                            ?> btn-toggle"
                                            onclick="confirmToggle(<?php echo $r['empId']; ?>, <?php echo $r['status']; ?>)">
                                            <i class="fa 
                                                <?php 
                                                    if ($r['status'] == 1) {
                                                        echo 'fa-check';
                                                    } elseif ($r['status'] == 9) {
                                                        echo 'fa-times';
                                                    } else {
                                                        echo 'fa-clock';
                                                    } 
                                                ?>"></i>
                                            <?php 
                                                if ($r['status'] == 1) {
                                                    echo " Active";
                                                } elseif ($r['status'] == 9) {
                                                    echo " Inactive";
                                                } else {
                                                    echo " Pending";
                                                }
                                            ?>
                                        </button>
                                    </td>
                                    <td class="center hidden-phone">
                                        <a href="staff-edit.php?id=<?php echo $r['empId']; ?>" class="btn btn-primary">Edit</a>
                                        <a href="staff-delete.php?empId=<?php echo $r['empId']; ?>"" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                                    </td>
                                </tr>
                                <?php $count++; } ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </section>
        </div>
    </section>

    <!-- JavaScript to handle status toggling -->
    <script>
        function confirmToggle(empId, currentStatus) {
            let newStatus;
            let statusText;

            // Determine new status based on current status
            if (currentStatus === 1) {
                newStatus = 9; // Change to Inactive
                statusText = "Inactive";
            } else if (currentStatus === 9) {
                newStatus = 1; // Change to Active
                statusText = "Active";
            } else if (currentStatus === 0) {
                // Allow changing Pending to Active or Inactive
                newStatus = prompt("Enter new status (1 for Active, 9 for Inactive):");
                if (newStatus != 1 && newStatus != 9) {
                    alert("Invalid input. Please enter 1 for Active or 9 for Inactive.");
                    return; // Prevent further action
                }
                statusText = newStatus == 1 ? "Active" : "Inactive";
            } else {
                alert("Invalid status.");
                return; // Prevent further action
            }

            // Confirm the change
            if (confirm(`Are you sure you want to change status to ${statusText}?`)) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "toggle-status.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        location.reload(); // Reload the page to reflect changes
                    }
                };
                xhr.send("empId=" + empId + "&status=" + newStatus);
            }
        }
    </script>

    <style>
        .btn-toggle {
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .btn-toggle:hover {
            opacity: 0.8;
        }
    </style>
</body>
</html>
	<!-- Vendor -->
	<script src="assets/vendor/jquery/jquery.js"></script>
	<script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
	<script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
	<script src="assets/vendor/nanoscroller/nanoscroller.js"></script>
	<script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="assets/vendor/magnific-popup/magnific-popup.js"></script>
	<script src="assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
	
	<!-- Specific Page Vendor -->
	<script src="assets/vendor/select2/select2.js"></script>
	<script src="assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
	<script src="assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
	<script src="assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
	
	<!-- Theme Base, Components and Settings -->
	<script src="assets/javascripts/theme.js"></script>
	
	<!-- Theme Custom -->
	<script src="assets/javascripts/theme.custom.js"></script>
	
	<!-- Theme Initialization Files -->
	<script src="assets/javascripts/theme.init.js"></script>

	<!-- Examples -->
	<script src="assets/javascripts/tables/examples.datatables.default.js"></script>
	<script src="assets/javascripts/tables/examples.datatables.row.with.details.js"></script>
	<script src="assets/javascripts/tables/examples.datatables.tabletools.js"></script>