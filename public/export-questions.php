<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php $page = "export-questions.php"; 
error_reporting(E_ALL);
ini_set("display_errors",1);
$cat = get_all_category();
?>
<!-- Query database for all users -->
<?php 
//where script Start
$where = "";
$cat_id = (isset($_GET['category']))? $_GET['category']:"";
if($cat_id !="")
{
	if($where != "")
		$where .= " AND category = {$cat_id}";
	else
		$where .= " category = {$cat_id}";
}

$ans = (isset($_GET['ans']))? intval($_GET['ans']): 0;


if($ans > 0)
{
	
	$ans = ($ans == 2)? 0 : $ans;
	if($where != "")
		$where .= " AND is_answered = {$ans}";
	else
		$where .= " is_answered = {$ans}";
	$ans = ($ans === 0)? 2 : $ans;
}


//where script ends
$questions_list = get_all_questions($where); 
$total = $questions_list->num_rows;


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
        <h1>Manage Questions <small>Control Panel</small></h1>
        <ol class="breadcrumb">
          <li><a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a></li>
          <li class="active"><i class="icon-file-alt"></i> Manage Questions</li>
        </ol>

        <?php echo user_form_success_msg(); ?>
        <?php echo delete_failure_msg(); ?>

        <div class="clearfix"></div>

       <div class="clearfix"></div>
      <div class="col-lg-12">
        <div class="row">
        <div class="panel tbp-panel-inverse">
          <div class="panel-heading">
            <h3 class="panel-title"><strong>Filter Questions</strong></h3>
          </div>
          <div class="panel-body">
            <div class="row">
	 <form name="search" id="search" action="" method="get">
              <div class="col-lg-3">
                <label class="control-label">Display</label>
                <div>
                <select class="form-control" name="ans" id="ans">
                  <option value="" >All</option>
                  <option value="1" <?php if($ans == 1) echo 'selected="selected"';?>>Answered</option>
                  <option value="2" <?php if($ans == 2) echo 'selected="selected"';?>>Un-answered</option>
                </select>
                </div>
              </div>

              <div class="col-lg-3">
                <label class="control-label">Category</label>
                <div>
                <select name="category" class="form-control" id="category">
					<option value="">All</option>
					<?php foreach($cat as $k=>$v)
					{
						$sel = ($k == $cat_id)? 'selected="selected"':'';
						echo '<option value="'.$k.'" '.$sel.'>'.$v.'</option>';
					}?>
				</select>
                </div>
              </div>

              <div class="col-lg-3">
                  <label class="control-label">&nbsp;</label>
                  <div class="input-group" style="margin-left:50%;">
                    <button type ="submit" id="submit-search" name="submit-search" value="submit-search" class="btn btn-primary pull-right tbp-flush-right" >Export Question</button>
                  </div>
              </div>
             
              <div class="col-lg-3">
                  <label class="control-label">&nbsp;</label>
                  <div class="input-group" style="margin-left:50%;">
                    <button type ="reset" id="reset-search" name="reset-search" value="reset-search" class="btn btn-primary pull-right tbp-flush-right" onclick="window.location.href = 'export-questions.php';">Reset Filter</button>
                  </div>
              </div>
               
	    </form>
            </div>
          
	</div>
		  
		  
        </div>
             
           
        
	
          </div><!-- /.table-responsive -->
      </div>
    </div><!-- /.row -->

  </div><!-- /#page-wrapper -->

<?php include('../includes/layouts/footer.php'); ?>
<?php require_once("../includes/db-connection-close.php"); ?>
