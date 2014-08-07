<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php $page = "manage-groups.php"; ?>

<!-- Query database for all users -->
<?php $users_list = get_all_users(); ?>
<?php $groups_list = get_all_groups(); ?>

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
        <h1>Manage Groups <small>Control Panel</small></h1>
        <ol class="breadcrumb">
          <li><a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a></li>
          <li class="active"><i class="icon-file-alt"></i> Manage Groups</li>
        </ol>

        <!-- Display conditional messages -->
        <?php echo user_form_success_msg(); ?>
        <?php echo delete_failure_msg(); ?>

        <div class="clearfix"></div>

        <div class="row">
          <div class="col-lg-4 pull-right">
            <form role="form">
              <label>Find Group by Name</label>
              <div class="form-group input-group">
                <input type="text" class="form-control" placeholder="Enter Group Name">
                <span class="input-group-btn">
                  <button class="btn btn-default"  type="button"><i class="fa fa-search"></i></button>
                </span>
              </div>
            </form>
          </div>
        </div>

        <h2>Group List</h2>
        
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped tablesorter">
              <thead>
                <tr>
                  
                  <th>Group ID <i class="fa fa-sort"></i></th>
                  <th>Group Name <i class="fa fa-sort"></i></th>
                  <th>Number of Members <i class="fa fa-sort"></i></th>
                  <th>Assign Quiz</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody id="user-rows">     

              <?php
                while($row = mysqli_fetch_assoc($groups_list))
                {
              ?>

                  <tr>
                    <td><?php echo $row['grp_id'];?></td>
                    <td><?php echo $row['grp_name'];?></td>
                    <td>27</td>
                    <td>
                        <a href="assign-group.php" ><i class="fa fa-user fa-2x"></i> Assign</a>                
                    </td>
                    <td>
                        <a href="edit-group.php?user_id=<?php echo htmlentities($row['user_id']); ?>" ><i class="fa fa-edit fa-2x"></i> Edit</a>                
                    </td>
                    <td>
                        <!--<a data-toggle="modal" data-target="#confirm-delete-modal"><i class="fa fa-trash-o fa-2x"></i> Delete</a>-->
                    </td>
                  </tr>
			<?php
			}
			?>

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
