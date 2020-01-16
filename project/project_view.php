<?php

// get file information
$title_project_short = basename($_SERVER['SCRIPT_FILENAME'], '.php');
$username_hbdi = $_SESSION['username_hbdi'];

///// get project info from DB
$stmt = $pdo->prepare(" SELECT id_project, title_project, title_project_short, project_description FROM projects WHERE title_project_short = '$title_project_short' ");
$stmt->execute();
$result = $stmt->fetch();
$id_project = $result['id_project'];
$title_project = $result['title_project'];
//$title_project_short = $result['title_project_short'];  // just get this from basename
$project_description = $result['project_description'];

$user_dir = $_SERVER['DOCUMENT_ROOT'] . "/hbdi/projects/$username_hbdi";
$prj_dir = $user_dir . '/' . $title_project_short . '_files';
//$test_file = $prj_dir . '/' . 'test.txt';
//unlink($test_file);
?>

<!-- Container -->
<div class="container" style="width: 90%; max-width: 900px; ">
    <div id="msg"></div>
    <!-- project Headers Wrapper: compliances, publish, titles, description, keywords, members -->
    <div class="section-wrap">

        <!-- Title - Short -->
        <button style="display: inline-block; float: left; padding: 0 5px; border-radius: 8px; border: 2px solid #5b5b5b; background-color: #777777; color: white">
            <?php echo "<span> $title_project_short   </span>"; ?>
        </button>
        <span style="color: white"><?php echo "(PID:$id_project)"; ?></span>
        <!-- Compliance Icons & Publish Project -->
        <div style=" border: ; text-align: right;">
            <i class="fas fa-notes-medical"></i>
            <a href="#" class="expand_collapse" aria-hidden="true" tabindex="1"><i
                        class="fas fa-lock"></i></a>
            <i class="fas fa-unlock-alt "></i>
            <span style="vertical-align: middle "><i
                        class="fab fa-creative-commons-pd "></i></span>
            <button style="padding: 0 5px; border-radius: 8px; border: 2px solid #782f40; background-color: #915664; ">
                <a style="color: #FFFFFF; border-radius: 25px; height: 20px; "
                   data-toggle="modal"
                   data-target="#publishModal">
                    Publish Project</a>
            </button>
            <!--            --><?php //echo '<a class="load-modal" href="project_view.php" data-toggle="modal" data-target="#publishModal"
            //               title="Click To View"> TEST </a> '; ?>
        </div>


        <!--Publish Modal-->
        <div class="modal" id="publishModal">
            <div class="modal-dialog"
                 style="width: 90%; max-width: 900px; max-height: available ;overflow: auto">
                <div class="modal-content">

                    <!-- Modal header -->
                    <div class="modal-header">
                        <h4 class="modal-title"> Project Publishing Viewer </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;
                        </button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        // TD: Show Project Content with only Public Datasets.
                        <div id="content-php"></div>

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <div style="text-align: right; ">
                            <button type="button" class="" data-dismiss="modal" style=" font-weight: 500; color: #FFFFFF;
            padding: 3px 5px; border-radius: 8px; margin: 4px; border: 2px solid #782f40; background-color: #915664; text-transform: none; height: 30px; vertical-align: top">
                                Close Viewer
                            </button>
                            <button type="submit" id="pubProjBtn" style=" font-weight: 500; color: #EEEEEE;
            padding: 3px 5px; border-radius: 8px; margin: 4px; border: 2px solid #915664; background-color: #782f40;  ">
                                <a href="<?php echo $p ?>/project/publish_viewer.php"
                                   style="text-decoration-line: none; color: #FFFFFF; height: 20px; "
                                   target="_blank">
                                    Publish Project
                                </a>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!--        enf of publish model -->

        <script>
            $('button#pubProjBtn').submit(function (e) {
                e.preventDefault();
                // Coding
                $('#publishModal').modal('toggle'); //or  $('#IDModal').modal('hide');
                return false;
            });
        </script>
        <script type="text/javascript">
            $("#icon_events_header").click(function () {
                $("#content-php").load("./project_view.php");
            });
        </script>
        <!-- end of Publish Modal-->

        <!-- project Headers: Titles, keywords, members -->
        <div style="border: ; ">
            <!-- Project Titles -->
            <div style="border: ; font-size: 1.8em; ; text-align: center; padding: 10px ">
                <?php echo $title_project; ?>
            </div>

        </div>
    </div>


    <!-- Ajax Update Project Description-->
    <?php
    // https://w3lessons.info/html5-inline-edit-with-php-mysql-jquery-ajax/
    if (!empty($_POST['prjDescription'])) {
        $prjDescription = $_POST['prjDescription'];
        {
            $prjDescription = strip_tags(trim($prjDescription));
            //update the values
            $sql = "UPDATE projects SET project_description = '$prjDescription' WHERE id_project = '$id_project' ";
            $stmt = $pdo->prepare($sql)->execute();
            echo "Updated";
        }
    } else {
//        echo "Empty";
    }
    ?>
    <script type="text/javascript"
            src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script>
        $(function () {
            //acknowledgement message
            var message_status_prjDescription = $("#status_PrjDescription");
            $("#prjDescription[contenteditable=true]").blur(function () {
                var prjDescription = $(this).attr("id");
                var value = $(this).text();
                $.post('', prjDescription + "=" + value, function (data) {
                    if (data != '') {
                        message_status_prjDescription.show();
                        // message_status.text(data);
                        message_status_prjDescription.text("Updating...");
                        //hide the message
                        setTimeout(function () {
                            message_status_prjDescription.hide()
                        }, 1000);
                    }
                });
            });
        });
    </script>
    <style>
        #status_PrjDescription { padding: 10px; background: #efefef; color: #000; font-weight: bold; font-size: 12px; margin-bottom: 10px; display: none; width: 100px; }
    </style>


    <!-- Project Description -->
    <div class="section-wrap">
        <div class="section-pane" style="width: 100%">
            <div class="pane-header">
                <span class="title">Project Description </span>
                <span onclick="hidePrjDescription()">[-]</span>
            </div>
            <div class="pane-content">
                <div id="status_PrjDescription"></div>
                <div id="prjDescription" contenteditable="true">

                    <?php echo $project_description ?>
                </div>
            </div>
            <!--            <form name="prjDescription" method="POST">-->
            <!--                <input type="hidden" name="id_project"-->
            <!--                       value="-->
            <?php //echo $_GET['id_project']; ?><!--">-->
            <!--                <input type="submit" name="updatePrjDescription" id="updatePrjDescription"-->
            <!--                       value="Update">-->
            <!--            </form>-->
        </div>
    </div>
    <!-- End of project HEADERs (Title, Description, Keywords, Members -->
    <script type="text/javascript">
        function hidePrjDescription() {
            var x = document.getElementById("prjDescription");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    </script>


    <!-- Keyword & Project Members Wrapper-->
    <div class="section-wrap">

        <!-- Keywords pane-->
        <div class="section-pane" style="width: 49.75%">
            <div class="pane-header">
                <div class="title"> Keywords</div>
                <!-- add keyword icon-->
                <span style="#915664; background-color: transparent; border: ; color: #888888"
                      data-toggle="modal" data-target="#memberModal">&nbsp;
                    <i class="far fa-edit"></i>
                </span>
            </div>
            <!-- list keywords -->
            <div class="pane-content">
                <?php
                $stmt = $pdo->prepare(" SELECT keyword FROM project_keyword WHERE id_project = '$id_project' ");
                $stmt->execute();
                $result = $stmt->fetchAll();
                foreach ($result as $row) {
                    echo $row['keyword'] . ", ";
                }
                ?>
            </div>
        </div>
        <!--        end of Listing KEYwords -->


        <!--Keywords Modal-->
        <div class="modal" id="kwModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal header -->
                    <div class="modal-header">
                        <h4 class="modal-title"> Edit Keywords </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;
                        </button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        // TD: Edit keywords.
                    </div>


                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!--    Project MEMBERS-->
        <div class="section-pane" style="width: 49.75%">
            <div class="pane-header">
                <span class="title"> Contributors </span>

                <!-- add project member icon-->
                <span style="background-color: transparent; border: none; color: #888888"
                      data-toggle="modal" data-target="#memberModal">&nbsp;
                        <i class="far fa-edit"></i>
                    </span>
            </div>

            <!-- list project members/ Contributors -->
            <div class="pane-content">
                <?php
                $stmt = $pdo->prepare(" SELECT DISTINCT u.id_user, u.name_first, u.name_last FROM user u
 JOIN project_user pu
 ON u.id_user = pu.lead OR u.id_user = pu.member OR u.id_user = pu.guest
 WHERE id_project = '$id_project';
 ");
                $stmt->execute();
                $result = $stmt->fetchAll();
                foreach ($result as $row) {
                    echo $row['name_first'] . " " . $row['name_last'] . ";  " . " ";
                }
                ?>

            </div>
            <!--            End of members listing -->

            <!-- The Member Modal -->
            <!--        https://www.w3schools.com/bootstrap4/bootstrap_modal.asp-->
            <div class="modal" id="memberModal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Member </h4>
                            <button type="button" class="close" data-dismiss="modal">
                                &times;
                            </button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            // TD: Edit member.
                            <!--                            // show existing members of the project + remove member-->
                            <!--                            --><?php //$stmt = $pdo->prepare("SELECT  ") ?>
                            <!--                            // add member-->
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger"
                                    data-dismiss="modal">Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of Member Modal -->
        </div>
        <!-- End of project MemBERS PANE-->
    </div>
    <!-- end of KEYwords and PRoject members SECTION -->


    <!--    TASKS & Wiki Wrapper -->
    <div class="section-wrap">

        <!-- begin task section pane -->
        <div class="section-pane" style=" width: 49.75%; min-height: 100px ">
            <!-- task header -->
            <div class="pane-header">
                <span class="title">
                    Project Tasks
                </span>
                <span style="display: inline; position: relative;float: ; padding-top: 0 ; margin: 0 2px">
                    <button style="background-color: transparent; border: none; color: #888888"
                            data-toggle="modal"
                            data-target="#taskModal"><i class="fas fa-plus-circle"></i>
                    </button>
                </span>
                <span style="display: none; margin-left: 5px; color: dimgrey"
                      id="taskMenu"> Acknowledge | Completed | Message </span>
            </div>


            <!-- begin list Tasks -->
            <div class="pane-content">
                <div>
                    <div class='content-header'
                         style='padding: 0; width: 11px; margin-right:0; '
                         type='checkbox' name='fileCheck' id='$id_file' value='$id_file'
                         onclick='loadTaskMenu()'></div>
                    <div class='content-header' style='width: 54.5%;'> Title</div>
                    <div class='content-header' style='width: 18%;'>Owner</div>
                    <span class='content-header' style='width: 20%;'>Due in </span>
                </div>
                <?php
                $stmt = $pdo->query(" 
 SELECT title_task, id_project, assigned_to, date_due  FROM task WHERE (assigned_to = '$uid_hbdi' OR created_by = '$uid_hbdi') AND id_project = '$id_project'");
                while ($row = $stmt->fetch()) {

                    $assigned = $row['assigned_to'];
                    $date_due = $row['date_due'];
                    $now = time();
                    $days_due = floor(($date_due - $now) / 86400);
                    if ($days_due <= 3) {
                        $days_due = "<span style='color: red'> $days_due </span>";
                    }


                    $stmt2 = $pdo->query(" SELECT name_first FROM user WHERE id_user = '$assigned' ");
                    foreach ($stmt2 as $row2) {
                        $name = $row2['name_first'];
                        $title_task = $row['title_task'];
                        echo "
                            <div class='content-item-wrap'>
                            <input class='content-header' style='margin: 5px 2px 0 0; width: 15px; '
                           type='checkbox' name='fileCheck' id='' value=''
                           onclick='loadTaskMenu()'>
                            <div class='content-item' style='width: 54%; '>  $title_task  </div> 
                            <div class='content-item' style='width: 18%; border: '>  $name  </div> 
                            <div class='content-item' style='width: 20%; '>  $days_due   days  </div> 
                            </div>
                            ";
                    }
                }
                ?>

            </div>
            <!-- end of pane content: list TASKs -->
        </div>
        <!-- end of task Section PANE -->


        <!--  begin Wiki header section Pane -->
        <?php
        // https://w3lessons.info/html5-inline-edit-with-php-mysql-jquery-ajax/
        if (!empty($_POST['update_wiki'])) {
            $update_wiki = $_POST['update_wiki'];
            {
                $update_wiki = strip_tags(trim($update_wiki));
                //update the values
                $sql = "UPDATE projects SET wiki = '$update_wiki' WHERE id_project = '$id_project' ";
                $stmt = $pdo->prepare($sql)->execute();
//                echo "Updated";
            }
        } else {
//            echo "Problem updating wiki content.";
        }
        ?>
        <script type="text/javascript"
                src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script>
            $(function () {
                //acknowledgement message
                var message_status_wiki = $("#status_wiki");
                $("#update_wiki[contenteditable=true]").blur(function () {
                    var update_wiki = $(this).attr("id");
                    var value = $(this).text();
                    // $.post('', prjDescription + "=" + value, function (data) {
                    $.post('', update_wiki + "=" + value, function (data) {
                        if (data != '') {
                            message_status_wiki.show();
                            // message_status.text(data);
                            message_status_wiki.text("Updating...");
                            // document.getElementsById("status_wiki").innerHTML = "Updating...";
                            // document.getElementsById("status_wiki").text = "Updating...";
                            //hide the message
                            setTimeout(function () {
                                message_status_wiki.hide()
                            }, 1000);
                        }
                    });
                });
            });
        </script>


        <style>
            #status_wiki { padding: 10px; background: #eeeeee; color: #000; font-weight: bold; font-size: 12px; margin-bottom: 10px; display: none; width: 100px; }
        </style>

        <div class="section-pane" style="width: 49.75%; min-height: 100px;">
            <div class="pane-header">
                <span class="title">
                    Project Wiki
                </span>
                <span style="display: inline; position: relative; margin: 0 2px">
                    <button style="background-color: transparent; border: none; color: #888888"
                            data-toggle="modal"
                            data-target="#wikiModal">
<!--                    <i class="far fa-edit"></i>-->
                    </button>
                </span>
            </div>
            <div class="pane-content">
                <div id="status_wiki"></div>
                <div>
                    <?php
                    $stmt = $pdo->prepare(" SELECT wiki FROM projects WHERE title_project_short = '$title_project_short' ");
                    $stmt->execute();
                    $result = $stmt->fetch();
                    $wiki = $result['wiki'];
                    ?>

                    <div id="update_wiki"
                         contenteditable="true"> <?php echo $wiki; ?> </div>

                </div>
            </div>
        </div>


        <!-- The Task Modal -->
        <div class="modal" id="taskModal">

            <!-- Task Modal dialog-->
            <div class="modal-dialog" style="height: 750px">
                <div class="modal-content">

                    <!-- Modal header -->
                    <div class="modal-header">
                        <h4 class="modal-title"> Add a Task </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;
                        </button>
                    </div>

                    <!-- Task Modal body -->
                    <div class="modal-body">
                        <section style=" margin-top: 5px; width: 280px;">


                            <!-- beginning of TASK PHP -->
                            <?php
                            if (isset($_POST['formTaskSubmit'])) {
                            $postedToken = $_POST['token'];

                            // prevent resubmission source: https://stackoverflow.com/questions/4614052/how-to-prevent-multiple-form-submission-on-multiple-clicks-in-php
                            // generate token
                            function getToken()
                            {
                                $token = sha1(mt_rand());
                                if (!isset($_SESSION['tokens'])) {
                                    $_SESSION['tokens'] = array($token => 1);
                                } else {
                                    $_SESSION['tokens'][$token] = 1;
                                }
                                return $token;
                            }

                            // check token
                            function isTokenValid($token)
                            {
                                if (!empty($_SESSION['tokens'][$token])) {
                                    unset($_SESSION['tokens'][$token]);
                                    return true;
                                }
                                return false;
                            }

                            // Check if a form has been sent
                            if (isset($_POST['formTaskSubmit'])) {
                                $postedToken = filter_input(INPUT_POST, 'token');
                                if (!empty($postedToken)) {
                                    if (isTokenValid($postedToken)) {
                                        // Process form
                                        $created_by = $_POST['created_by'];
                                        $title_task = $_POST['title_task'];
                                        $assigned_to = $_POST['assigned_to'];
                                        $date_due = $_POST['date_due'];
                                        $taskDescription = $_POST['taskDescription'];
                                        $resource = $_POST['resource'];
                                        $remark = $_POST['remark'];
                                        $id_project = $_POST['id_project'];

                                        $stmt = $pdo->prepare("INSERT INTO task (created_by, title_task, assigned_to, date_due, taskDescription, resource, remark, id_project) 
                                    VALUES ('$uid_hbdi', '$title_task', '$assigned_to', '$date_due', '$taskDescription', '$resource', '$remark', '$id_project') ");
                                        $stmt->execute();
                                        echo "<meta http-equiv=REFRESH CONTENT=1;url=$p/projects/" . $username_hbdi . "/" . $title_project_short . ".php>";
                                    } else {
                                        echo "Do something about the error";
                                    }
                                }
                            }
                            // Get a token for the form we're displaying
                            $token = getToken();

                            ?>
                            <!-- End of TASK PHP -->


                            <!-- Begin TASK FORM Task Modal -->
                            <form id="formTaskSubmit" method="POST" action="">
                                <input type="hidden" name="token"
                                       value="<?php echo $token; ?>"/>
                                <input type="hidden" name="created_by"
                                       value="<?php echo $uid_hbdi ?>">
                                <input type="hidden" name="id_project"
                                       value="<?php echo $id_project ?>">
                                <div>
                                    <input type="text" name="title_task"
                                           placeholder="Title of task... "
                                           class="signup_row"
                                           required>
                                </div>
                                <div>
                                    <input name="assigned_to"
                                           placeholder="Assign task to... "
                                           class="signup_row"
                                           required>
                                </div>
                                <div>
                                    <input name="date_due"
                                           placeholder="Task due date... "
                                           class="signup_row"
                                           required>
                                </div>
                                <div>
                                    <input name="taskDescription" id="taskDescription"
                                           placeholder="Task description"
                                           class="signup_row" required>
                                </div>
                                <div>
                                    <input name="resource" placeholder="Resources"
                                           class="signup_row" required>
                                </div>
                                <div>
                                    <input name="remark" placeholder="Remark"
                                           class="signup_row" required>
                                </div>
                                <span style=" display: inline-block; margin-top: 12px">
                                    <input type="submit" name="formTaskSubmit"
                                           id="formTaskSubmit"
                                           style="padding: 0 10px; height: 40px; border-radius: 4px; border: solid 1px grey"
                                           value="Submit">
                                </span>
                            </form>


                            <!--                                 Modal footer-->
                            <!--                            <span class="modal-footer">-->
                            <!--                                    <button type="button" class="btn btn-danger"-->
                            <!--                                            data-dismiss="modal"-->
                            <!--                                            style="background-color: #7f7f7f; border: 1px solid #BBBBBB">-->
                            <!--                                        Close-->
                            <!--                                    </button>-->
                            <!--                                </span>-->


                        </section>
                    </div>
                    <!-- end of Task modal Body-->
                    <?php } ?>

                </div>
                <!-- end of modal content-->
            </div>
            <!-- end of modal dialog -->
        </div>
        <!-- end of task Modal -->


        <!-- begin The Wiki Modal -->
        <div class="modal" id="wikiModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal header -->
                    <div class="modal-header">
                        <h4 class="modal-title"> Edit Wiki Entry </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;
                        </button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        // TD: Edit wiki entry.
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <!--        End of Wiki modeL-->

    </div>
    <!-- end of tasks and wiki wrapper -->


    <!--  Project FILES Wrapper -->
    <div class="section-wrap">

        <!-- beginning of File Pane -->
        <div class="section-pane" style="width: 100%">
            <div class="pane-header">
                <span class="title"> Datasets & Files </span>
                <span style="display: inline; position: relative;float: ; padding-top: 0 ; margin: 0 2px">
                        <button style="background-color: transparent; border: none; color: #888888"
                                data-toggle="modal"
                                data-target="#fileModal"><span style="color: #">
                                        <i class="fas fa-folder-plus fa-lg"
                                           style="size: .9em; color: #888888"> </i>
                        </button>
                </span>
                <span style="display: none; color: dimgrey; margin-left: 5px"
                      id="fileMenu"> Download | Load (Compute) | Move | Label (Metadata) | Delete  </span>
            </div>

            <!--        begin showing List of FILES -->
            <div class="pane-content">
                <div class='content-header' style='padding: 0; width: 13px;'
                     type='checkbox' name='fileCheck' id='$id_file' value='$id_file'
                     onclick='loadFileMenu()'></div>
                <div class='content-header' style='width: 50%'> Filename</div>
                <div class='content-header' style='width: 26%'> Compliance</div>
                <div class='content-header' style='width: 15%'> Uploaded</div>
                <div class='content-header' style='width: 4.5%'> FID</div>


                <!--                ######### PROBLEM ######### -->
                <?php
                echo "<form id='fileChkBox'>";
                if (!isset($title_project_short)) {
                    error_log("\$title_project_short is empty.", 0);
                } else {
                    $path = $_SERVER['DOCUMENT_ROOT'] . "/hbdi/projects/$username_hbdi/$title_project_short" . "_files/";
                }
                $files = scandir($path);
                foreach ($files as $filename) {
                    if (($filename != ".") AND ($filename != "..")) {
                        try {
                            $stmt = $pdo->prepare("
SELECT id_file, date_uploaded, compliance, id_project 
FROM files 
WHERE name_file = '$filename' AND id_project = '$id_project'");
                            $stmt->execute();
                            $result = $stmt->fetch();
                            $id_file = $result['id_file'];
                            $date_uploaded = $result['date_uploaded'];
                            $compliance = $result['compliance'];
                        } catch (PDOException $e) {
                            echo "Oops!";
                            echo $e->getMessage();
                            exit();
                        }
                        $date_time = (date("m-d-y H:i:s", $date_uploaded));
                        //                    if ($date_uploaded > 1980) {
                        if ($id_file) {


                            echo "
                        <div class='content-item-wrap'>
                            <input class='content-item' style='margin-right: 2px; width: 15px;' type='checkbox' name='fileCheck' id='$id_file' value='$id_file' onclick='loadFileMenu()'> 
                            <div class='content-item' style='width: 50%;'>  $filename  </div> 
                            <div class='content-item' style='width: 26%;'>  $compliance  </div> 
                            <div class='content-item' style='width: 15%;'>  $date_time  </div> 
                            <div class='content-item' style='width: 5%;'>  $id_file  </div>   
                        </div>
                            </form>";
                        }

                    }
                };

                ?>
            </div>

        </div>
        <!-- end of listing FILES -->
    </div>
    <!-- end of file pane -->

    <script>
        function loadFileMenu() {
            var checks = [];
            var text = document.getElementById("fileMenu");
            var text2 = "";
            var i;

            $("input:checkbox[name=fileCheck]:checked").each(function () {
                checks.push($(this).val());
            })
            if (checks.length > 0) {
                text.style.display = "inline-block";

            } else {
                text.style.display = "none";
            }
        }
    </script>

    <script type="text/javascript">
        function loadTaskMenu() {
            var checks = [];
            var text = document.getElementById("taskMenu");
            var text2 = "";
            var i;

            $("input:checkbox[name=fileCheck]:checked").each(function () {
                checks.push($(this).val());
            })
            if (checks.length > 0) {
                text.style.display = "inline-block";

            } else {
                text.style.display = "none";
            }
        }


    </script>
    <!--    end of File PANE-->
    <!--########## End of Problem  -->

    <!-- The File Modal: uploader -->
    <div class="modal" id="fileModal">
        <div class="modal-dialog" style="height: 750px">
            <div class="modal-content" style="height: 200px">

                <!-- Modal header -->
                <div class="modal-header" style="height: 50px">
                    <!--                    <h4 class="modal-title">Add Files </h4>-->
                    <h4 class="">Drag and Drop or Select Files </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;
                    </button>
                </div>


                <!-- Modal body -->
                <div class="modal-body" style="height: 100px">
                    Click on the Choose file button.
                </div>


                <?php


                if (isset($_POST['submitFile'])) {
                    $ttt = print_r($_FILES);
                    error_log("print_r _FILES: $ttt", 0);
                    $file_test = basename($_FILES["userfile"]["name"]);
                    if (isset($file_test)) {
                        error_log("the file: $file_test", 0);
                        $tt = print_r($file_test);
                        error_log("test print_r file_test: $tt", 0);
                    } else {
                        error_log("error: _FILES basename test", 0);
                    }
                    error_log("form submitted (POSTed).", 0);
                    $id_project_from_form = $_POST['id_project'];
                    $title_project_short = $_POST['title_project_short'];
                    $username_hbdi = $_POST['username_hbdi'];
                    error_log("id_project: $id_project; title_project_short: $title_project_short; username_hbdi: $username_hbdi. Variables passed", 0);

                    if (isset($_FILES['userfile']['size']) > 0) {  // this can catch the problem
//                    if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
                        if (empty($_FILES['userfile']['name'])) { //name is the file name on the client machine
                            echo "FIle name is empty!";
                            error_log("File name is empty!", 0);
                            exit;
                        }
                        $upload_file_name = $_FILES['userfile']['name'];
                        $id_project = $_POST['id_project'];
//                    check URL against short project title to make sure the project is correct
//                        $tps = $_POST['title_project_short']; // get a different name because it's from the form
                        $tps = $title_project_short; // trying the one from the basename of script
                        $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/hbdi/projects/' . $username_hbdi . '/' . $tps . '_files';
                        //                    $compliance = implode("; ", $_POST['compliance']); // for later...
//    check Files Directory
//   create Files Directory if non-existent
//                        if ($_SERVER['HTTP_HOST'] == 'tychen.us') {
//                        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/hbdi/projects/" . $username_hbdi . "/" . $tps . "_files";
                        if (file_exists($target_dir)) {
//                        echo "Directory " . $tps . "_files does not exist." .
//                            "Strange. It should have been reaced with the project." .
//                            "Anyway, creating the files directory... <br > ";
                            error_log("Target Directory: $target_dir", 0);
                        } else {
//                            exit();
                            mkdir($target_dir, 0755, true) or die("Error creating directory $target_dir . <br>");
                            error_log("Target Directory $target_dir created.", 0);
                        }
//                        }


                        $passed_file = $_FILES['userfile']['name'];
                        chmod($passed_file, 0644);
                        $tmp_file = $_FILES['userfile']['tmp_name'];
                        error_log("Target file: $passed_file", 0);

                        $target_file = $target_dir . "/" . $passed_file;
                        error_log("Target path+file: $target_file", 0);
                        $uploadOk = 1;
//                        $target_file = $target_dir . $file;

                        // Check if file already exists
                        if (file_exists($target_file)) {
                            error_log("Target File $target_file already exists.", 0);
                            $uploadOk = 0;
                        }
//                        elseif
//                            // Check file size
//                        ($_FILES["userfile"]["size"] > 500000000) {
//                            echo "Your file " . $file . " is too large in size . ";
//                            $uploadOk = 0;
//                        }

//                        if (isset($target_file)) {
//                            error_log("Target file ($target_file) exists", 0);
//                        } else {
//                            error_log("Target file does NOT exist", 0);
//                        }

//                    $file2 = preg_replace("/[^ \w-_.()]/", "_", $file);

//                    echo "File:  $file <br>";
//                    echo "File2: $file2 <br>";

//                    if ($file != $file2) {
//                        echo "Please do not use space or special characters (\"-\", \"_\", \".\", and \"()\" are okay) in a file name. <br>";
//                        echo "Redirecting you to the Project page... <br>";
//                        echo "<meta http-equiv=REFRESH CONTENT=5;url=$p/projects/" . $username_hbdi . "/" . $tps . ".php>";
//                        exit();
//                    }


                        // Check if $uploadOk is set to 0 by an error
                        elseif ($uploadOk == 0) {
                            echo "Your file was not uploaded." . "<br>" . "Redirecting you to the Project page.";
                            echo "<meta http-equiv=REFRESH CONTENT=1;url=$p/projects/" . $username_hbdi . "/" . $tps . ".php>"; // works
                            // if everything is ok, try to upload file and record time of upload
                        } else {
                            if (move_uploaded_file($tmp_file, $target_file)) {

                                $date_uploaded = time();
                                if ($_FILES["userfile"]["error"] == 0) {
                                    error_log("userfile success.", 0);
                                    echo
                                        "The file has been successfully uploaded . <br > " .
                                        "Inserting metadata to database... <br > ";
                                } else {
                                    error_log("Something went wrong... (code: ['userfile']['error'])", 0);
                                }
                            } else {
                                echo "There was an error uploading your file." . "<br>" .
                                    "Redirecting to Project page...";
                                echo "<meta http-equiv=REFRESH CONTENT=5;url=$p/projects/$username_hbdi/$tps.php>";
                                error_log("File upload failed.", 0);
                                exit();
                            }
                            $sql = $pdo->prepare("INSERT INTO files (id_project, uploaded_by, name_file, date_uploaded, compliance)
                        VALUES('$id_project', '$uid_hbdi', '$passed_file', '$date_uploaded', '$compliance') ");
                            if ($sql->execute()) {
                                echo "Metadata inserted successfully.";
                                error_log("Metadata insertion successful. Redirecting to Project page.", 0);
                                echo "<meta http-equiv=REFRESH CONTENT=1;url=$p/projects/$username_hbdi/$tps.php>";
                                exit();
                            } else {
                                echo "Error inserting file information into the database. <br > 
                                      File upload is successful, though. <br>
                                      Redirecting to the Project page.";
                                echo "<meta http-equiv=REFRESH CONTENT=5;url=$p/projects/$username_hbdi/$tps.php>";
                                exit();
                            }
                        }

                    } else {
                        error_log("_FILES['file']['name'] is empty", 0);
                        $err = $_FILES['userfile']['error'];
                        error_log("_FILES error: $err", 0);
                    }
                }
                //                    else {
                //                        error_log("Form not submitted.", 0);
                //                    }
                //                } else {
                //                    error_log("Request Method is not POST.", 0);
                //                }
                ?>


                <div style="padding-left: 15px">
                    <form enctype="multipart/form-data" action='' method='POST'>
                        <input name="userfile" type="file" value="Your file">
                        <div style="padding-left: 90px">
                            <div><input type="checkbox" name="compliance[]"
                                        value="HIPAA"> File contains HIPAA data
                            </div>
                            <div><input type="checkbox" name="compliance[]"
                                        value="human_subject"> File contains human subject data
                            </div>
                            <div><input type="checkbox" name="compliance[]"
                                        value="protected"> File contains protected data
                            </div>
                            <div><input type="checkbox" name="compliance[]"
                                        value="FDA-part11"> File contains FDA - part 11 data
                            </div>
                            <div><input type="checkbox" name="compliance[]"
                                        value="private"> File contains private data
                            </div>
                            <div><input type="checkbox" name="compliance[]"
                                        value="public"> File is open to the public
                            </div>

                            <input type="hidden" name="id_project" value="<?php echo $id_project; ?>">
                            <input type="hidden" name="title_project_short" value="<?php echo $title_project_short; ?>">
                            <input type="hidden" name="username_hbdi" value="<?php echo $username_hbdi; ?>">
                            <!--                            --><?php //error_log("id_project: $id_project; title_project_short: $title_project_short; username_hbdi: $username_hbdi", 0); ?>
                            <!--                        </div>-->
                            <input type="submit" formmethod="post" value="Upload Now" name="submitFile"
                                   style="width: 90px; height: 24px; margin: 10px 20px">

                    </form>

                </div>


                <!-- Modal footer -->
                <!--                    <div class="modal-footer">-->
                <!--                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>-->
                <!--                    </div>-->

                <!--                            <form action="" class="form-container">-->
                <!--                                <input type="text" placeholder="Enter name or mail to search..." name="search" required>-->
                <!--                                <button type="submit" class="btn" style="width: 60px; padding: 1px 5px ">Add</button>-->
                <!--                            </form>-->
            </div>
        </div>
    </div>
    <!-- end of File Modal-->


</div>
<!-- END of Container: Project Viewer -->

</body>