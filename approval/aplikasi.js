$(document).ready(function(){

    loadApproval();

    function loadApproval()
    {
        $("#data-approval").load("approval.data.php");
    }

    $(document).on("click",".btn-approve",function(){

        var id = $(this).data("id");

        $.post(
            "approval.input.php",
            {
                id:id
            },
            function(res)
            {
                alert(res);
                loadApproval();
            }
        );

    });

    $(document).on("click",".btn-reject",function(){

        var id = $(this).data("id");

        $.post(
            "approval.input2.php",
            {
                id:id
            },
            function(res)
            {
                alert(res);
                loadApproval();
            }
        );

    });

});