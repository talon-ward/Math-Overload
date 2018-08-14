<?php

 echo "<div class=\"page-header\">\n" .
      " <table>\n" .
      "  <tr>\n" .
      "   <td>\n" .
      "    <span class=\"page-logo\"><a href=\"index.php\"><img alt=\"Math Overload\" src=\"images/logo-small.png\" /></a></span>\n" .
      "   </td>\n" .
      "<td><a href=\"courses.php\">Courses</a> | <a href=\"progress.php\">Progress</a> | <a href=\"study.php\">Study</a> | <a href=\"notes.php\">Notes</a> | <a href=\"exercises.php\">Exercises</a></td>\n" .
      "   <td>\n" .
      "    <span class=\"page-search\">\n" .
      "     <form method=\"get\" action=\"#\">\n" .
      "      <input type=\"text\" name=\"q\" id=\"q\" onKeyUp=\"setSearchTimer()\" autocomplete=\"off\" onBlur=\"hideSearch()\" onFocus=\"setSearchVars(); unhideSearch()\" />\n" .
      "      <input type=\"hidden\" name=\"t\" id=\"t\" value=\"link\" />" .
      "      <input type=\"hidden\" name=\"d\" id=\"d\" value=\"12\" /> " .
      "      <input type=\"hidden\" name=\"i\" id=\"i\" value=\"q\" />" .
      "     </form>\n" .
      "     <div id=\"search-output\" class=\"search-box\"></div>" .
      "    </span>\n" .
      "   </td>\n" .
      "  </tr>\n" .
      " </table>\n" .
      " <div style=\"clear:both;\"></div>\n" .
      "</div>\n";

?>
