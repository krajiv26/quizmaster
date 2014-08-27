<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php $page = "manage-essay-questions.php"; 
error_reporting(E_ALL);
ini_set("display_errors",1);
$cat = get_all_category();
$_SESSION["get_query"] = "";
$_SESSION["get_query"] = $_SERVER["QUERY_STRING"];
?>
<!-- Query database for all users -->
<?php 
//where script Start
$where = "";
$key = (isset($_GET['key']))? $_GET['key']:"";
if($key !="")
{
	if($where != "")
		$where .= " AND question_text like '%{$key}%'";
	else
		$where .= " question_text like '%{$key}%'";
}

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
$questions_list = get_all_essay_questions($where); 
$total = $questions_list->num_rows;

parse_str($_SERVER["QUERY_STRING"], $output);
unset($output['page']);
$get_query = http_build_query($output, '', '&amp;');



$targetpage = "manage-essay-questions.php?$get_query"; //your file name
$limit = 10; //how many items to show per page
$p = (isset($_GET['page']))? $_GET['page']:"";

if($p)
	$start = ($p - 1) * $limit; //first item to display on this page
else
	$start = 0;


$questions_list = get_all_essay_questions_limit($start,$limit,$where);

$pagination_html = pagination($targetpage,$total,$limit,$p);
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
                  <label class="control-label">Search</label>
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search by Keyword" name="key" id="key" value="<?php echo $key;?>">
                    <span class="input-group-btn">
                      <button class="btn btn-default"  type="button" onclick="document.getElementById('search').submit();" style="height:35px !important;background-color:#3276B1;"><i class="fa fa-search"></i></button>
                    </span>
                  </div>
              </div>
             
              <div class="col-lg-3">
                  <label class="control-label">&nbsp;</label>
                  <div class="input-group" style="margin-left:50%;">
                    <button type ="reset" id="reset-search" name="reset-search" value="reset-search" class="btn btn-primary pull-right tbp-flush-right" onclick="window.location.href = 'manage-essay-questions.php';">Reset Filter</button>
                  </div>
              </div>
               
	    </form>
            </div>
          
	</div>
		  
		  
        </div>
             
           
        <h2>Question List (<?php echo $total;?>) </h2>
        <?php echo $pagination_html;  ?>
	<form name="table_select" id="table_select" action="assign-question.php" method="post">
            <div style="float:right;margin-bottom:10px;">
                <button type ="button"  id="show-answer" name="show-answer" value="show-answer" class="btn btn-primary pull-right tbp-flush-right">Show All Answer</button>
            </div>
            <div class="table-responsive" style="clear:both;">
            <table class="table table-bordered table-hover table-striped tablesorter">
              <thead>
                <tr>
                  <th>ID</i></th>
                  <th>Question Text <i class="fa fa-sort"></i></th>
                  <th>Category</th>
                  <th>Ans</th>
                  <?php if(checkUserType() == 'admin') { ?>
                  <th>Edit</th><?php } ?>
                  <!--<th>Delete</th>-->
                </tr>
              </thead>
              <tbody id="user-rows">     

              <?php
              $answered[0] = "<span style='color:#ff0000;'>No</span>";
              $answered[1] = "Yes";
	      $cnt = 0;
              while($row = mysqli_fetch_assoc($questions_list)) {
		$cnt++;
              ?>

                  <tr>
                    <td><?php echo $row['eq_id']; ?></td>
                    <td><a style ="cursor:pointer;" onclick="$('#ans_<?php echo $row['eq_id'];?>').toggle();"><?php echo checkAnsFormat($row['question_text']); ?></a></td>
                    <td><?php echo $cat[$row['category']]; ?></td>
                    <td><?php echo $answered[$row['is_answered']]; ?></td>
                    <?php if(checkUserType() == 'admin') { ?>
                    <td>
                        <a href="edit-essay-question.php?eq_id=<?php echo htmlentities($row['eq_id']); ?>" ><i class="fa fa-edit fa-2x"></i></a>                
                    </td><?php } ?>
                  </tr >
                    <tr id="ans_<?php echo $row['eq_id'];?>" style="display:none;">
                    <td colspan="6"><?php echo $row['answer'];?></td>
                    </tr>
              
              <?php
                }
              ?>
              <?php
                // Release the returned data
                mysqli_free_result($questions_list);
              ?>
        
              </tbody>
            </table>
          </form>
        <?php echo $pagination_html;  ?>
          </div><!-- /.table-responsive -->
      </div>
    </div><!-- /.row -->

  </div><!-- /#page-wrapper -->

<?php include('../includes/layouts/footer.php'); ?>
    <script>
  $(document).ready(function() {

   $('#show-answer').click(function(event) {  //on click
      if($(this).html() == "Show All Answer"){
          $(this).html("Hide All Answer");
          $( "tr[id^='ans_']" ).show();
      }
      else {
          $(this).html("Show All Answer");
          $( "tr[id^='ans_']" ).hide();
      }
    });
});
  </script>
<?php require_once("../includes/db-connection-close.php"); ?>
