$(document).ready(
  function()
  {
    $("#toggelType").click(
      function()
      {
        $("#registerForm").toggle(500);
        $("#loginForm").toggle(500);
        if($("#toggelType").text()=="Login")
        {
          $("#toggelType").text("Regestriren");
        }
        else
        {
          $("#toggelType").text("Login");
        }
      }
    );
  }
);
