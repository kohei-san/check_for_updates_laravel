var btns = document.getElementsByClassName('lineregister');

var btns = Array.from(btns);

btns.forEach(function(btn){
  btn.addEventListener('click', function(){
    var customer_id = btn.id;
    var innerText = btn.innerText;
    if(innerText.indexOf("未") > -1){
      var registered = false;
    }
    else{
      var registered = true;
    }

    // jsonに値登録
    var json = JSON.stringify( { customer_id: customer_id,
                                registered: registered});
    // console.log(json)　OK！^^

    var xhr = new XMLHttpRequest()
    xhr.onreadystatechange = function(){
      var completed = 4;
      var httpOK = 200;

      if( this.readyState == completed && this.status == 200){
        // レスポンスのモーダル表示
        alert( 'LINEステータスを変更しました！' );
      }
      else{
        // 問題なければ3度readyStateが変化している: alert(this.readyState) が 1, 2, 3 と表示される
        alert(this.readyState);
      }
    }

    // x-csrf追加
    var token = document.getElementsByName('csrf-token').item(0).content;
    xhr.open( 'POST', '/linepost');
    xhr.setRequestHeader( 'X-CSRF-TOKEN', token); //.open()の後、.sendの前に記述すること　複数呼び出し可能（その場合、マージされて単一のリクエストとなる）
    xhr.setRequestHeader( 'request', json);
    xhr.send();
  })
})