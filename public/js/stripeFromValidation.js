$(document).ready(function(){
  $("#paymentForm").validate({
          rules: {
              amount: {
                  required: true,
                  min: 1
              },
          },
          messages: {
              amount: {
                  required: "Amount field is required",
                  min: "The amount must be greater than 0."
              },
          },
        errorPlacement: function (error, element) {
            error.addClass("text-danger"); 
            error.insertAfter(element);
        },
        highlight: function (element) {
            $(element).addClass("error-border");
        },
        unhighlight: function (element) {
            $(element).removeClass("error-border");
        },
        submitHandler: function(form) {
            form.submit();
        }
      });
});