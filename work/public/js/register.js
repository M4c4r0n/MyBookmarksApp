"use strict";
{
    console.log("aaa");
    const check = document.querySelector(".check");
    console.log(check.textContent);
    if(check.textContent === "ok"){
        alert("登録成功！ログイン画面に遷移します");
        window.location.href = "../auth/login.php";
    }   
    else{

    } 
}