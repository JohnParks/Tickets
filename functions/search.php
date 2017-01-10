<?php


function get_filter_form($options){ 
    
    ?>
    <form role="search" action="" method="get" id="searchform-find-a-show">
        <input type="hidden" name="search_post_type" value="shows" /> <!-- // hidden 'products' value -->
        <input type="hidden" name="search_tosearch" value="" />
        <div class="genre-filter">
            <input type="hidden" name="genre" value="" />
            <?php if(isset($options['genre']['label']) && $options['genre']['label'] !== ""){ ?>
            <h4>Filter by Genre</h4>
            <?php } ?>
            <?php
            // Alright, let's try fetching a list of genres that have "include_filter" set to true (or 1)
            $genreArgs = array(
                "taxonomy"		=>	"genre",
                "fields"		=>	"all",
                "meta_query"	=>	array(
                    array(
                        "key"		=> "include_filter",
                        "value"		=>	true,
                        "compare"	=>	"="
                    )
                )
            );
            $terms = get_terms( $genreArgs );
            ?>
            <select id="genre-filter" <?php echo (isset($options['genre']['multi'])) ? "multiple" : ""; ?>>
                <option value="">All Genres</option>
                <?php foreach( $terms as $term ) { ?>
                <option value="<?php echo $term->slug; ?>" ><?php echo $term->name; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="city-filter">
            <input type="hidden" name="search_city" value="" />
            <?php if(isset($options['city']['label']) && $options['city']['label'] !== ""){ ?>
            <h4>Filter by City</h4>
            
            <?php }
            // let get a list of all cities in the DB, build a selector for each one
            $cities = get_posts( array( "post_type" => "city" ) );
            ?>
            <select id="city-filter" <?php echo (isset($options['city']['multi'])) ? "multiple" : ""; ?>>
                <option value="">All Cities</option>
                <?php foreach( $cities as $city ) { ?>
                <option value="<?php echo $city->ID; ?>" ><?php echo $city->post_title; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="month-filter">
            <input type="hidden" name="search_month" value="" />
            <?php if(isset($options['month']['label']) && $options['month']['label'] !== ""){ ?>
            <h4>Filter by Month</h4>
            <?php } ?>
            <select class="month-filter" id="month-filter" <?php echo (isset($options['month']['multi'])) ? "multiple" : ""; ?>  >
                <option value="0">January</option>
                <option value="1">February</option>
                <option value="2">March</option>
                <option value="3">April</option>
                <option value="4">May</option>
                <option value="5">June</option>
                <option value="6">July</option>
                <option value="7">August</option>
                <option value="8">September</option>
                <option value="9">October</option>
                <option value="10">November</option>
                <option value="11">December</option>
            </select>
                            <script type="text/javascript">
                $('.month-selector').val(new Date().getMonth());
            </script>
        </div>
    </form>
<script>
    $('form#searchform-find-a-show select').change(adjustForm);
    var timeOutvar;
    function adjustForm(e){
        
        clearTimeout(timeOutvar);
        
        var val = e.target.value;
        var field = e.target.id.split('-')[0];
        
        $('form#searchform-find-a-show input[name="search_'+field+'"]').val(val);
        
        
        timeOutvar = setTimeout(function(){
            console.log("submitting");
            $('#searchform-find-a-show').submit();
        },2000)
    }
</script>
        <?php
}

?>