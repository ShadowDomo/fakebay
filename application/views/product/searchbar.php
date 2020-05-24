
<!-- TODO make already sold items not appear in search results -->
<div class="container-fluid">
    <form method='get' action='<?php echo base_url().'Products/searchProducts' ;?>'>
    <div class="row">
        <div class="col-8">
            <div class="form-group">
                <!-- kunt-->
                <input type="text" name="search" class="form-control" id="search" placeholder="Search for anything">
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


