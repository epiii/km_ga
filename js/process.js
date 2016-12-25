function runGA() {
  $.ajax({  
    url:'process.php',
    data:'mode=ga',
    dataType:'json',
    type:'post',
    success:function(dt){
      // alert(dt);
      $('#kmgaDV').html('under construction');
      // console.log(dt);
    },
  });
}

function runKM() {
  $.ajax({  
    url:'process.php',
    data:'mode=kmeans',
    dataType:'json',
    type:'post',
    success:function(dt){
      // $('#kmDV').html('<img src="loader.gif" alt="" />');
      $('#kmDV').html('loading ....');
      setTimeout(function(){
        $('#kmDV').html(dt.data);
      },300);
    },
  });
}