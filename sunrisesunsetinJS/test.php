<?php 
   $test =  $_COOKIE['name'];
   echo $test;
   $str = exec("python test.py $test");
 ?>

<h1 id = "test" onclick = "fun()">test</h1>

<script>
    
  function fun(){
  
      console.log();
      document.getElementById("test").innerHTML = "<?php echo $str ?>";
  }
</script>