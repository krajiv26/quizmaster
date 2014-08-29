<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php $page = "manage-classified-info.php"; ?>

<!-- Query database for all users -->
<?php 
$cat = get_all_category();

$where = "";
$key = (isset($_GET['search']))? $_GET['search']:"";
if($key !="")
{
    $where .= " title like '%{$key}%'";
}
$users_list = get_all_classified($where);



?>





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
        <h1>Manage Classified Info <small>Control Panel</small></h1>
        <ol class="breadcrumb">
          <li><a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a></li>
          <li class="active"><i class="icon-file-alt"></i> Manage Classified Info</li>
        </ol>

        <!-- Display conditional messages -->
        <?php echo user_form_success_msg(); ?>
        <?php echo delete_failure_msg(); ?>

        <div class="clearfix"></div>

        <div class="row">
          <div class="col-lg-4 pull-right">
            <form role="form" name="search-form" method="get">
              <label>Find Info</label>
              <div class="form-group input-group">
                <input type="text" class="form-control" placeholder="Enter Title" name="search" value="<?php echo $key;?>">
                <span class="input-group-btn">
                  <button class="btn btn-default"  type="submit"><i class="fa fa-search"></i></button>
                </span>
              </div>
            </form>
          </div>
        </div>

        <h2>Classified Info List</h2>
        
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped tablesorter">
              <thead>
                <tr>
                  <th>ID <i class="fa fa-sort"></i></th>
                  <th>Title<i class="fa fa-sort"></i></th>
                  <th>Category</th>
                  <th>View</th>
                  <?php if(checkUserType() == 'admin') { ?>
                  <th>Edit</th>
                  <?php } ?>
                </tr>
              </thead>
              <tbody id="user-rows">     

              <?php

                while($row = mysqli_fetch_assoc($users_list)) {
               
              ?>
                  
                  <tr>
                    <td><?php echo $row['info_id']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $cat[$row['category']]; ?></td>
                    <td><a href="view-info.php?info_id=<?php echo htmlentities($row['info_id']); ?>" ><i class="fa fa-pencil fa-2x"></i> View</a>                
                    </td>
                    <?php if(checkUserType() == 'admin') { ?>
                    <td>
                        <a href="edit-classified-info.php?info_id=<?php echo htmlentities($row['info_id']); ?>" ><i class="fa fa-edit fa-2x"></i> Edit</a>                
                    </td>
                    <?php } ?>
                  </tr>
              <?php
                }
              ?>
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

<script>
$('#confirm-delete-modal').on('hidden.bs.modal', function () {
  $(this).removeData('bs.modal');
});
</script>

<?php require_once("../includes/db-connection-close.php"); ?>