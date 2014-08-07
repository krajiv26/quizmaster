<?php require_once("../includes/sessions.php"); ?>
<?php include('../includes/layouts/header.php'); ?>
<?php $page = "dashboard.php"; ?>

<body>

<div id="wrapper">

  <!-- Sidebar -->
  <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <?php include('../includes/layouts/admin-head.php'); ?>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
      <?php include("../includes/layouts/admin-side-nav.php"); ?>

      <?php include('../includes/layouts/admin-profile.php'); ?>
    </div><!-- /.navbar-collapse -->
  </nav>
  <div id="page-wrapper">

    <div class="row">
      <div class="col-lg-12">
        <h1>Blank Page <small>A Blank Slate</small></h1>
        <ol class="breadcrumb">
          <li class="active"><a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a></li>
        </ol>
		<?php echo user_form_success_msg(); ?>
		<div class="clearfix"></div>
      </div>
    </div><!-- /.row -->
  </div><!-- /#page-wrapper -->

<?php include('../includes/layouts/footer.php'); ?>