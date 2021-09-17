
window.allCustomers = @json;


const searchForm = document.getElementById('searchForm');
searchForm.onkeyup = function(datas){
  var input = searchForm.value;

  datas.forEach( data => {
    if(data.indexOf(input)){
      console.log(data);
  }

  });
}