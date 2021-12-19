<?php

  if(!function_exists('searchStudent')){
      function searchStudent($query,$regexp,$field1){
          $query->where($field1,'regexp',$regexp);
      }
  }


