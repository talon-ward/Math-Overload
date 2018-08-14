<?php

// MathJax latex configuration
echo "<script type=\"text/x-mathjax-config\">\n" .
     " MathJax.Hub.Config({\n" .
     "  extensions: [\"tex2jax.js\"],\n" .
     "  jax: [\"input/TeX\", \"output/HTML-CSS\"],\n" .
     "  tex2jax: {\n" .
     "   inlineMath: [ ['$','$'], [\"\\\\(\",\"\\\\)\"] ],\n" .
     "   displayMath: [ ['$$','$$'], [\"\\\\[\",\"\\\\]\"] ],\n" .
     "   processEscapes: true\n" .
     "  },\n" .
     "  \"HTML-CSS\": { availableFonts: [\"TeX\"] },\n" .
     "  TeX: {\n" .
     "   extensions: [\"AMSmath.js\"]\n" .
     "  }\n" .
     " });\n" .
     "</script>\n" .
     "<script type=\"text/javascript\" src=\"http://cdn.mathjax.org/mathjax/latest/MathJax.js\"></script>\n";

?>
