

$(document).ready(function()
{
	$(".register-form p").on("click",hideRegForm);
	$(".login-form p").on("click",showRegForm);
		
	$(".register-form").hide();
});

function hideRegForm(event)
{
	$(".register-form").hide();
	$(".login-form").show();
}

function showRegForm(event)
{
	$(".register-form").show();
	$(".login-form").hide();
}