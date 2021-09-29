function changeClass(btn, line_flg){
  if(btn.innerText.indexOf("通信中") > -1 && line_flg == true){
    btn.className = "cursor-pointer bg-transparent bg-green-400 hover:bg-yellow-500 font-semibold text-white py-2 px-4 rounded lineregister";
    btn.innerText = '登録済み';
  }
  else if(btn.innerText.indexOf("通信中") > -1 && line_flg == false){
    btn.className = "cursor-pointer py-2 px-4 rounded hover:bg-gray-500 hover:text-white hover:opacity-50 lineregister";
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
    // クリックイベント発生時のダブルクリック対策
    if(btn.innerText.indexOf("通信中") > -1){
      return
    }

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

      if( this.readyState == completed && (this.status >= 200 && this.status <= 299)){ //status = 201はリクエストは成功し、その結果新たなリソースが作成されたことを示します。これは一般的に、 POST リクエストや、一部の PUT リクエストを送信した後のレスポンスになります。
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
      else{
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