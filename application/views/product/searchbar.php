<script>


$(document).ready(function() {

        $("#search").keyup(function (e) { 

        // autocomplete 
        var searchterm = $("#search").val();
            $.ajax({
                type: "post",
                url: `<?php echo base_url(); ?>Products/getSearchResultsOnly?searchterm=` + searchterm,
                data: "data",
                success: function (response) {
                    html = $.parseHTML(response);
                    $("#results").html("");
                    try {
                        var results = JSON.parse(response);
                        results.forEach(element => {
                            // console.log(element[0].product_name);
                            $("#results").append(`<option value="${element[0].product_name}">`);
                        });
                    } catch (error) {
                    }
                }
            });
        });


});




</script>
<!-- TODO make already sold items not appear in search results -->
<div class="container-fluid">
    <form method='get' action='<?php echo base_url().'Products/searchProducts' ;?>'>
    <div class="row">
        <div class="col-8">
            <div class="form-group">
                <!-- kunt-->
                <input list="results" name="search" class="form-control" id="search" placeholder="Search for anything">
                <datalist id="results">
                </datalist>
            </div>
        
        </div>
        <div class="col-3">
            <div class="row justify-content-between ">
                <button  type="submit" class="btn btn-primary ml-3">Search</button>
            </div>
        </div>
    </div>
    </form>
</div>


