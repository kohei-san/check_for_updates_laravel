function changeClass(btn, line_flg){
  if(btn.innerText.indexOf("通信中") > -1 && line_flg == true){
    btn.className = "cursor-pointer bg-transparent bg-green-400 font-semibold text-white py-2 px-4 rounded lineregister";
    btn.innerText = '登録済み';
  }
  else if(btn.innerText.indexOf("通信中") > -1 && line_flg == false){
    btn.className = "cursor-pointer lineregister";
    btn.innerText = '未登録';
  }
  else{
    btn.className = "bg-transparent bg-gray-500 font-semibold text-white py-2 px-4 rounded opacity-50";
    btn.innerText = '通信中...';
  }
}



// 処理スタート
var btns = document.getElementsByClassName('lineregister');

var btns = Array.from(btns);

btns.forEach(function(btn){
  btn.addEventListener('click', function(){
    // jsonに渡す値の準備
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

    var xmlHttpRequest = new XMLHttpRequest()
    xmlHttpRequest.onreadystatechange = function(){
      var completed = 4;
      var httpOK = 200;

      if( this.readyState == completed && this.status == 200){
        var response = JSON.parse(this.response);
        // 通信完了のクラス付与
        if(registered != response.line_flg){
          alert( 'LINEステータスを変更しました！' );
          changeClass(btn, response.line_flg)
        }
        else{
          alert( 'ステータスを変更できませんでした。画面をリロードしてください。' );
        }

        
      }
      else if(this.readyState == 1){
        // 通信中のクラス付与メソッド
          changeClass(btn);
      }
    }

    // x-csrf追加
    var token = document.getElementsByName('csrf-token').item(0).content;
    xmlHttpRequest.open( 'POST', '/linepost');
    xmlHttpRequest.setRequestHeader( 'X-CSRF-TOKEN', token); //.open()の後、.sendの前に記述すること　複数呼び出し可能（その場合、マージされて単一のリクエストとなる）
    xmlHttpRequest.setRequestHeader( 'request', json);
    xmlHttpRequest.send();
  })
})