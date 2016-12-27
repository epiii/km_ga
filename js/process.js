function runGA() {
  $.ajax({  
    url:'process.php',
    data:'mode=ga',
    dataType:'json',
    type:'post',
    success:function(dt){
      $('#kmgaDV').html('loading ....');
      setTimeout(function(){
        // centroid ----------------
        var tb='';
        tb+='<table class="table table-bordered">'
          +'<tr class="text-center" style="background-color:black;color:white;">'
            +'<td>Cluster</td>'
            +'<td>centroid SL</td>'
            +'<td>centroid SW</td>'
            +'<td>centroid PL</td>'
            +'<td>centroid PW</td>'
          +'</tr>';
        var no=1;
        $.each(dt.data.centDec, function (id,item) {
          clr=id==0?'warning':(id%2==0?'info':'success');
          tb+='<tr  class="text-center '+clr+'">'
              +'<td>'+(no)+'</td>'
              +'<td>'+item[0]+'</td>'
              +'<td>'+item[1]+'</td>'
              +'<td>'+item[2]+'</td>'
              +'<td>'+item[3]+'</td>'
            +'</tr>';
            no++;
        });
        tb+='</table>';      

        // distance each cluster ---------------
        tb+='<div style="overflow-y: auto;height: 300px;">'
          +'<table class="table table-bordered">'
          +'<tr class="text-center" style="background-color:black;color:white;">'
            +'<td>Data</td>'
            +'<td>distance C1</td>'
            +'<td>distance C2</td>'
            +'<td>distance C3</td>'
        +'</tr>';

        var nox=1;
        $.each(dt.data.dist, function (id,item) {
          tb+='<tr class="text-center" >'
              +'<td>Data-'+(nox)+'</td>'
              +'<td class="warning">'+item[0]+'</td>'
              +'<td class="success">'+item[1]+'</td>'
              +'<td class="info">'+item[2]+'</td>'
            +'</tr>';
            nox++;
        });
        tb+='</table>'
        +'</div><br />';

        //minimum distance ------------------
        tb+='<div style="overflow-y: auto;height: 300px;">'
          +'<table class="table table-bordered">'
          +'<tr class="text-center" style="background-color:black;color:white;">'
            +'<td>Data</td>'
            +'<td>Selected Cluster</td>'
        +'</tr>';

        var nox=1;
        $.each(dt.data.distMin, function (id,item) {
          clust=(parseInt(item.index)+1);
          clr=clust==1?'warning':(clust%2==0?'info':'success');
          tb+='<tr class="text-center '+clr+'" >'
              +'<td>Data-'+(nox)+'</td>'
              +'<td>'+clust+'</td>'
            +'</tr>';
            nox++;
        });
        tb+='</table>'
        +'</div>';

        //SSE ------------------
        tb+='<br /><div style="overflow-y: auto;height: 300px;">'
          +'<table class="table table-bordered">'
          +'<tr class="text-center" style="background-color:black;color:white;">'
            +'<td>Data</td>'
            +'<td>SSE</td>'
        +'</tr>';

        var nox=1;
        $.each(dt.data.sse, function (id,item) {
          clust=(parseInt(item.index)+1);
          clr=clust==1?'warning':(clust%2==0?'info':'success');
          tb+='<tr class="text-center '+clr+'" >'
              +'<td>Data-'+(nox)+'</td>'
              +'<td>'+item+'</td>'
            +'</tr>';
            nox++;
        });
        tb+='</table>'
        +'</div>';

        // MSE - Fitness  ------------
        tb+='<h3 class="text-left">- MSE : '+dt.data.mse+'</h3>'
        +'<h3 class="text-left">- Fitness : '+dt.data.fitness+'</h3>'
        +'<hr />';

        // parent 1 & 2  ------------
        tb+='<h3 class="text-center">- Biner :</h3>';
        tb+='<h3 class="text-left">- Parent 1 :</h3>';
        $.each(dt.data.binParent1, function (id,item) {
          tb+='<p class="text-left">- - Cluster '+(parseInt(id)+1)+' : '+item+'</p>';
        });
        tb+='<h3 class="text-left">- Parent 2:</h3>';
        $.each(dt.data.binParent2, function (id,item) {
          tb+='<p class="text-left">- - Cluster '+(parseInt(id)+1)+' : '+item+'</p>';
        });
        tb+='<hr />';
        
        //dec parent 1 & 2  ------------
        tb+='<h3 class="text-center">- Decimal :</h3>';
        tb+='<h3 class="text-left">- Parent 1  :</h3>';
        $.each(dt.data.decParent1, function (id,item) {
          tb+='<p class="text-left">- - Cluster '+(parseInt(id)+1)+' : '+item+'</p>';
        });
        tb+='<h3 class="text-left">- Parent 2:</h3>';
        $.each(dt.data.decParent2, function (id,item) {
          tb+='<p class="text-left">- - Cluster '+(parseInt(id)+1)+' : '+item+'</p>';
        });
        tb+='<hr />';

        // hasil crossover (Par1 x Par2) ---------        
        tb+='<h3 class="text-center">- Hasil crossover (biner) :</h3>';
        tb+='<h3 class="text-left">- child 1  :</h3>';
        $.each(dt.data.binChild1, function (id,item) {
          tb+='<p class="text-left">- - Cluster '+(parseInt(id)+1)+' : '+item+'</p>';
        });
        tb+='<h3 class="text-left">- child 2  :</h3>';
        $.each(dt.data.binChild2, function (id,item) {
          tb+='<p class="text-left">- - Cluster '+(parseInt(id)+1)+' : '+item+'</p>';
        });
        tb+='<hr />';

        // hasil crossover decimal
        tb+='<h3 class="text-center">- Hasil crossover (decimal) :</h3>';
        tb+='<h3 class="text-left">- child 1 :</h3>';
        $.each(dt.data.decChild1, function (id,item) {
          tb+='<p class="text-left">- - Cluster '+(parseInt(id)+1)+' : '+item+'</p>';
        });
        tb+='<h3 class="text-left">- child 2 :</h3>';
        $.each(dt.data.decChild2, function (id,item) {
          tb+='<p class="text-left">- - Cluster '+(parseInt(id)+1)+' : '+item+'</p>';
        });
        tb+='<hr />';

        // -------

        $('#kmgaDV').html(tb);
      },300);
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