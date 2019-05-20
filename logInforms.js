$("#registerForm").click(function(event){
    $("#logIn").hide();
    $("#signUp").show();
    event.preventDefault();
});
  
$("#logInForm").click(function(event){
   $("#signUp").hide();
   $("#logIn").show();
   event.preventDefault();
});