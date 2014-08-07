<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php $page = "test-results.php"; ?>

<!-- Query database for all users -->
<?php $users_list = get_all_users(); ?>

<?php include('../includes/layouts/header.php'); ?>

<body>

<div id="wrapper">

  <!-- Sidebar -->
  <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <?php include('../includes/layouts/admin-head.php'); ?>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
      <?php include('../includes/layouts/admin-side-nav.php'); ?>

      <?php include('../includes/layouts/admin-profile.php'); ?>
    </div><!-- /.navbar-collapse -->
  </nav>
  <div id="page-wrapper">
    
    <div class="row">
      <div class="col-lg-12">
        <h1>Test Results <small>Test Name</small></h1>
        <ol class="breadcrumb">
          <li><a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a></li>
          <li class="active"><i class="icon-file-alt"></i> Test Results</li>
        </ol>

        <!-- Display conditional messages -->
        <?php echo user_form_success_msg(); ?>
        <?php echo delete_failure_msg(); ?>

        <div class="clearfix"></div>

       <div class="clearfix"></div>
      <div class="col-lg-12">
        <div class="row">
        <div class="panel tbp-panel-inverse">
          <div class="panel-heading">
            <h3 class="panel-title"><strong>Collective Test Results</strong></h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-4"><strong>Total Participants: <span class="text-info">3</span></div>
              <div class="col-lg-4"><strong>Avg Time: <span class="text-info">1:12:30</span></div>
              <div class="col-lg-4"><strong>Average Score: <span class="text-info">79.2</span></div>
            </div>
            <div class="row">
              <div class="col-lg-4"><strong>Median Score: <span class="text-info">74.9</span></div>
              <div class="col-lg-4"><strong>Lowest Score: <span class="text-info">45.7</span></div>
              <div class="col-lg-4"><strong>Highest Score: <span class="text-info">92.6</span></div>
          </div>
        </div>
      </div>
             
           
        <h2>Individual Participant Results</h2>
        
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped tablesorter">
              <thead>
                <tr>
                  
                  <th>First Name<i class="fa fa-sort"></i></th>
                  <th>Last Name<i class="fa fa-sort"></i></th>
                  <th>Start Time <i class="fa fa-sort"></i></th>
                  <th>End Time <i class="fa fa-sort"></i></th>
                  <th>Percentage Correct</th>
                  <th>Letter Grade</th>
                  <th>Email Address</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody id="user-rows">     

              <?php

              $row = mysqli_fetch_assoc($users_list);

              ?>

                  <tr>
                    <td>John</td>
                    <td>Doe</td>
                    <td>10:30:02</td>
                    <td>11:53:32</td>
                    <td>82.3</td>
                    <td>B</td>
                    <td>jdoe@bingmail.com</td>
                    <td>
                        <a data-toggle="modal" data-target="#confirm-delete-modal"><i class="fa fa-trash-o fa-2x"></i> Delete</a>
                    </td>
                  </tr>

                  <tr>
                    <td>Jane</td>
                    <td>Doe</td>
                    <td>11:30:02</td>
                    <td>12:22:32</td>
                    <td>45.7</td>
                    <td>F</td>
                    <td>jandoee@example.com</td>
                    <td>
                        <a data-toggle="modal" data-target="#confirm-delete-modal"><i class="fa fa-trash-o fa-2x"></i> Delete</a>
                    </td>
                  </tr>

                  <tr>
                    <td>Jim</td>
                    <td>Jones</td>
                    <td>09:30:02</td>
                    <td>10:43:32</td>
                    <td>92.6</td>
                    <td>A</td>
                    <td>jjones@bingmail.com</td>
                    <td>
                        <a data-toggle="modal" data-target="#confirm-delete-modal"><i class="fa fa-trash-o fa-2x"></i> Delete</a>
                    </td>
                  </tr>

                

                  <!-- Confirm Deletion Modal -->
                  <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                          <h4 class="modal-title" id="confirm-delate-label">Warning!</h4>
                        </div>
                        <div class="modal-body">
                          You are about to delete a user. This action will be irreversible.<br />
                          Do you wish to proceed?
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                          <a href="delete-user.php?user_id=<?php echo $row["user_id"]; ?>" class="btn btn-primary">Yes</a>
                        </div>
                      </div>
                    </div>
                  </div><!-- /.modal fade -->
              
              <?php
                // Release the returned data
                mysqli_free_result($users_list);
              ?>
        
              </tbody>
            </table>
          </div><!-- /.table-responsive -->
      </div>
    </div><!-- /.row -->

  </div><!-- /#page-wrapper -->

<?php include('../includes/layouts/footer.php'); ?>
<?php require_once("../includes/db-connection-close.php"); ?>