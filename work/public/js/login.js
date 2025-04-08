"use strict";
{
    const check = document.querySelector(".check");
    console.log(check.textContent);
    if(check.textContent === "ok"){
        alert("ログイン成功！ブックマーク一覧に遷移します");
        window.location.href = "../index.php";
    }   
    else if(check.textContent === "wj");
    else{
        alert("メールアドレスまたはパスワードが違います");
        window.location.href = "./login.php";
    } 
}