<?php include('scripts/initialize.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
 <head>
  <title>Math Overload - Courses</title>

  <meta http-quiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="description" content="A free Mathematics site that presents the material in an effective and fun way." />

  <link rel="stylesheet" href="styles/default.css" />

  <script type="text/javascript">
  <!--

   function getScrollXOffset() {
    return window.pageXOffset;
   }

   function getScrollYOffset() {
    return window.pageYOffset;
   }

  //-->
  </script>

 </head>
 <body>
  <?php

   include('scripts/header.php');
   forceLogin('courses.php');

  ?>

  <div class="page-wrapper">
   <?php include('scripts/page-header.php'); ?>

   <div class="course-selector">
    <form method="post" action="savecourses.php">
     <table>
      <?php

       require_once('scripts/mo-courses.php');

       $courses = userCourses($userID);
       foreach($courses as $courseID => $courseInfo) {
        $courseName = stringDirty($courseInfo[0]);
        $courseDescription = stringDirty($courseInfo[1]);
        $courseSelected = $courseInfo[2];

        echo "<tr>\n" .
             "<td class=\"checkbox\"><input type=\"checkbox\" name=\"checkCourse$courseID\" value=\"1\" ";
        if($courseSelected == 1) {
         echo "checked ";
        }
        echo "/></td>\n" .
             "<td style=\"cursor: default\" onMouseOver=\"document.getElementById('divText$courseID').style.display='block';\" onMouseOut=\"document.getElementById('divText$courseID').style.display='none';\" onMouseMove=\"var item = document.getElementById('divText$courseID'); item.style.top = (event.clientY + getScrollYOffset() + 20) + 'px'; item.style.left = (event.clientX + getScrollXOffset() + 20) + 'px';\">$courseName<div id=\"divText$courseID\" class=\"tooltip\">$courseDescription</div></td>\n" .
             "</tr>\n";
       }

      ?>
      <tr>
       <td colspan="2" class="save"><input type="submit" value="Save" /></td>
      </tr>
     </table>
    </form>
   </div>

  </div>

  <?php include('scripts/footer.php'); ?>
 </body>
</html>
