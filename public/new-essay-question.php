<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php include('../includes/layouts/header.php'); ?>

<?php $page = "new-essay-question.php"; 
$cat = get_all_category();

?>

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
        <h1>New Question <small>Control Panel</small></h1>
        <ol class="breadcrumb">
          <li><a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a></li>
          <li class="active"><i class="icon-file-alt"></i> New Question</li>
        </ol>
        <hr />
      </div><!-- /.col-lg-12 -->
    </div><!-- /.row -->

    <!-- Display conditional messages and/or errors -->
    <?php $error_messages = errors(); ?>
    <?php $form_values = form_history() ?>
    <?php echo display_form_errors($error_messages); ?>
    <?php echo user_form_info_msg(); ?>
    <?php echo user_form_failure_msg(); ?>
      
      
    <div class="clearfix"></div>
      <div class="row">
        <div class="col-lg-12">
          <h2 id="nav-tabs">Question Type</h2>
            <div class="bs-example">
              <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                <li class="active"><a href="#new-essay-question-form" data-toggle="tab">Essay</a></li>
              </ul>
              <div id="myTabContent" class="tab-content">
                <div class="tab-pane fade active in col-lg-offset-1 col-lg-6" id="new-essay-question-form">
                  <p>
                    <form class="form-horizontal" action="process-essay-question.php" method="post">
                      <div class="form-group">
                        <div class="col-sm-12">
                        <label for="question_text">Question Text</label>
                        <textarea class="form-control" rows="4" id="question_text" name="question_text" 
                          placeholder='Ex. If a six-string guitar is tuned in Standard E, what notes would each string be tuned to? List them from the lowest pitch to the highest.' class="input-xlarge"></textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-12">
                        <label for="answer control-label">Answer</label>
                        <textarea class="form-control" rows="10" id="answer" name="answer" 
                          placeholder="Ex. E, A, D, G, B, E." class="input-xlarge"></textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-12">
                        <label for="category control-label">Category</label>
                        <select name="category" class="form-control">
                        <?php foreach($cat as $k=>$v)
                        {
                                echo '<option value="'.$k.'">'.$v.'</option>';
                        }?>
                        </select>
                        </div>
                      </div>
                      <div class="form-group pull-right">
                        <label for="submit-new-user"></label>
                        <button type ="submit" id="submit-essay-question" name="submit-essay-question" value="submit-essay-question" class="btn btn-primary">Submit Question</button>
                        </div>

                    </form>
                  </p>
                </div>
              </div>
            </div>
        </div>
      </div>
  </div><!-- /#page-wrapper -->


<?php include('../includes/layouts/footer.php'); ?>
<?php require_once("../includes/db-connection-close.php"); ?>