<?php include('scripts/initialize.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
 <head>
  <title>Math Overload - Create Question</title>

  <meta http-quiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="description" content="A free Mathematics site that presents the material in an effective and fun way." />

  <link rel="stylesheet" href="styles/default.css" />

  <script type="text/javascript">

   function clearDefault(obj) {
    if(obj.value == obj.defaultValue) {
     obj.value = '';
    }
   }

   function restoreDefault(obj) {
    if(obj.value == '') {
     obj.value = obj.defaultValue;
    }
   }

   var prereqCount = 1;
   var courseCount = 1;
   function addTextInput(divName, name, defaultValue, searchOptions) {

    if(name == 'prereqID') {
     ++prereqCount;
     name += prereqCount;
    }

    if(name == 'courseID') {
     ++courseCount;
     name += courseCount;
    }

    var divNew = document.createElement('div');
    divNew.innerHTML = "<input type='text' name='" + name + "' value='" + defaultValue + "' onFocus=\"clearDefault(this); setEmbeddedSearchVars(this, 'id', " + searchOptions + "); unhideSearch()\" onBlur='restoreDefault(this); hideSearch()' onKeyUp='setSearchTimer()' /><br />";
    document.getElementById(divName).appendChild(divNew);
    //document.getElementById(divName).innerHTML += "<input type='text' name='" + name + "' value='" + defaultValue + "' onFocus=\"clearDefault(this); setEmbeddedSearchVars(this, 'id', 1); unhideSearch()\" onBlur='restoreDefault(this); hideSearch()' onKeyUp='setSearchTimer()' /><br />";
   }

   var wrongAnswerCount = 6;
   function addTextAreaInput(divName, name, defaultValue) {

    if(name == 'wronganswer') {
     ++wrongAnswerCount;
     name += wrongAnswerCount;
    }

    document.getElementById(divName).innerHTML += "<textarea name='" + name + "' rows='3' cols='40'  onFocus='clearDefault(this)' onBlur='restoreDefault(this)'>" + defaultValue + "</textarea><br />";
   }

  </script>

 </head>
 <body>
  <?php

   include('scripts/header.php');

  ?>

  <div class="page-wrapper">
  <?php

   include('scripts/page-header.php');

  ?>
   <p>
    <form method="post" action="addquestion.php">
     <input type="text" name="conceptID" value="conceptID" onFocus="clearDefault(this); setEmbeddedSearchVars(this, 'id', 1); unhideSearch()" onBlur="restoreDefault(this); hideSearch()" onKeyUp="setSearchTimer()" /><input type="text" name="noteID" value="noteID" onFocus="clearDefault(this); setEmbeddedSearchVars(this, 'id', 8); unhideSearch()" onBlur="restoreDefault(this); hideSearch()" onKeyUp="setSearchTimer()" /><br />
     <table>
      <tr>
       <td valign="top"><div id="prereqs"><input type="text" name="prereqID1" value="prereqID" onFocus="clearDefault(this); setEmbeddedSearchVars(this, 'id', 1); unhideSearch()" onBlur="restoreDefault(this); hideSearch()" onKeyUp="setSearchTimer()" /><br /></div></td>
       <td valign="top"><div id="courses"><input type="text" name="courseID1" value="courseID" onFocus="clearDefault(this); setEmbeddedSearchVars(this, 'id', 2); unhideSearch()" onBlur="restoreDefault(this); hideSearch()" onKeyUp="setSearchTimer()" /><br /></div></td>
      </tr>
      <tr>
       <td><a href="#" onClick="addTextInput('prereqs', 'prereqID', 'prereqID', 1)">[add prereq]</a></td>
       <td><a href="#" onClick="addTextInput('courses', 'courseID', 'courseID', 2)">[add course]</a></td>
      </tr>
     </table>
     <br />
     <textarea name="question" rows="8" cols="60" onFocus="clearDefault(this)" onBlur="restoreDefault(this)">Question.</textarea><br />
     <textarea name="answer" rows="3" cols="40" onFocus="clearDefault(this)" onBlur="restoreDefault(this)">Answer.</textarea><br />
     <br />
     <div id="wronganswers">
      <textarea name="wronganswer1" rows="3" cols="40" onFocus="clearDefault(this)" onBlur="restoreDefault(this)">Wrong answer.</textarea><br />
      <textarea name="wronganswer2" rows="3" cols="40" onFocus="clearDefault(this)" onBlur="restoreDefault(this)">Wrong answer.</textarea><br />
      <textarea name="wronganswer3" rows="3" cols="40" onFocus="clearDefault(this)" onBlur="restoreDefault(this)">Wrong answer.</textarea><br />
      <textarea name="wronganswer4" rows="3" cols="40" onFocus="clearDefault(this)" onBlur="restoreDefault(this)">Wrong answer.</textarea><br />
      <textarea name="wronganswer5" rows="3" cols="40" onFocus="clearDefault(this)" onBlur="restoreDefault(this)">Wrong answer.</textarea><br />
      <textarea name="wronganswer6" rows="3" cols="40" onFocus="clearDefault(this)" onBlur="restoreDefault(this)">Wrong answer.</textarea><br />
     </div>
     <a href="#" onClick="addTextAreaInput('wronganswers', 'wronganser', 'Wrong answer.')">[add wrong answer]</a><br />
     <br />
     <input type="submit" value="Add" />
    </form>
   </p>
  </div>

  <?php include('scripts/footer.php'); ?>
 </body>
</html>
