var btns = document.getElementsByClassName('lineregister');

var btns = Array.from(btns);

for(var btn of btns){
  btn.addEventListener('click', function(){
    console.log('ok');
  })
}