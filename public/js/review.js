/*
関数概要: アクティブコールボタンの配色を変更
*/
function changeClass(btn, flg){
  if(btn.innerText.indexOf("通信中") > -1 && flg == true){
    btn.className = "cursor-pointer bg-transparent bg-blue-500 hover:bg-green-500 font-semibold text-white py-2 px-4 rounded";
    btn.innerText = '登録しました';
    btn.dataset.registered = 1;
  }
  else if(btn.innerText.indexOf("通信中") > -1 && flg == false){
    btn.className = "cursor-pointer py-2 px-4 rounded hover:bg-gray-500 hover:text-white hover:opacity-50 ";
    btn.innerText = '削除しました';
    btn.dataset.registered = 0;
  }
  else{
    btn.className = "bg-transparent bg-gray-500 font-semibold text-white py-2 px-4 rounded opacity-50";
    btn.innerText = '通信中...';
    btn.dataset.registered = 3;
  }
}

/*
関数概要: コントローラーへcustomer_idとステータスの送信
引数: btn (customer_id, user_idを含んだノード)
*/
function sendRequest(){
  console.log(btn)
  if(btn.dataset.registered == 3){ // クリックイベント発生時のダブルクリック対策
    return
  }
  // jsonに渡す値の準備
  var customer_id = btn.dataset.customerid;
  var registered = btn.dataset.registered;
  
  // jsonに値登録
  var json = JSON.stringify({ customer_id: customer_id,
                              registered: registered,
                            });

  var xmlHttpRequest = new XMLHttpRequest()
  xmlHttpRequest.onreadystatechange = function(){
    var completed = 4;

    if( this.readyState == completed && (this.status >= 200 && this.status <= 299)){ //status = 201はリクエストは成功し、その結果新たなリソースが作成されたことを示します。これは一般的に、 POST リクエストや、一部の PUT リクエストを送信した後のレスポンスになります。
      var response = JSON.parse(this.response);
      // 通信完了のクラス付与
      if(registered){
        alert( '口コミのステータスを変更しました！' );
        changeClass(btn, response.review_flg);
      }
      else{
        alert( 'ステータスを変更できませんでした。画面をリロードしてください。' );
        changeClass(btn, response.review_flg);
      }
    }
    else{
      // 通信中のクラス付与メソッド
        changeClass(btn);
    }
  }

  var token = document.getElementsByName('csrf-token').item(0).content; // x-csrf追加
  xmlHttpRequest.open( 'POST', '/review');
  xmlHttpRequest.setRequestHeader( 'X-CSRF-TOKEN', token); //.open()の後、.sendの前に記述すること　複数呼び出し可能（その場合、マージされて単一のリクエストとなる）
  xmlHttpRequest.setRequestHeader( 'request', json);
  xmlHttpRequest.send();
}

// 処理スタート
var btn = document.getElementById('review');

btn.addEventListener('click', sendRequest);